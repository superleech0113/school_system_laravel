<?php

namespace App\Http\Controllers;

use App\Attendances;
use App\Books;
use App\BookStudents;
use App\Checkin;
use App\ClassUsage;
use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
use App\Jobs\CalculateClassUsage;
use App\Schedules;
use App\Students;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TerminalController extends Controller
{
    public function index()
    {
        return view('terminal.index');
    }

    public function checkin()
    {
        return view('terminal.checkin');
    }

    public function checkinSubmit(Request $request)
    {
        $res = [];

        $student = $this->getStudentByRFID($request->rfid_token);

        if(!$student)
        {
            $res['status'] = 0;
            $res['message'] = __('messages.unverified-rfid');
            return $res;
        }

        $checkin = Checkin::where([
            'student_id' => $student->id,
        ])->whereDate('date_time', Carbon::today()->toDateString())
            ->orderBy('date_time', 'desc')->first();


        if ($checkin && $checkin->status == 0){
            return $this->checkoutSubmit($request);
        }


        $date = Carbon::now(CommonHelper::getSchoolTimezone())->format('Y-m-d');
        $yoyakus = $student->yoyakus()->where('waitlist', 0)
            ->where('date',$date)
            ->where('status',0)
            ->whereHas('schedule', function ($query) {
                $query->whereIn('type', Schedules::CLASS_TYPES);
            })
            ->get();

        if($yoyakus->count() == 0)
        {
            if (Session::has('already-checked')) {
                $res['message'] = __('messages.you-have-already-checked-in');
            } else {
                $res['message'] = __('messages.no-class-found-to-checkin');
            }
            Session::put('already-checked',1);
            $res['status'] = 0;
            return $res;
        }

        foreach($yoyakus as $yoyaku)
        {
            $attendance = new Attendances([
                'customer_id' => $yoyaku->customer_id,
                'yoyaku_id' => $yoyaku->id,
                'class_id' => $yoyaku->schedule->class_id,
                'teacher_id' => $yoyaku->schedule->teacher_id,
                'schedule_id' => $yoyaku->schedule_id,
                'payment_plan_id' => $yoyaku->schedule->class->payment_plan_id,
                'start_date' => $yoyaku->start_date,
                'end_date' => $yoyaku->end_date,
                'date' => $date
            ]);

            $attendance->save();

            if($yoyaku->taiken == '1') {
                $student->status = 1;
                $student->save();
            }

            $yoyaku->status = 1;
            $yoyaku->save();

            ClassUsage::ClassUsed($yoyaku);
        }

        $now = Carbon::now();
        $checkin = new Checkin();
        $checkin->student_id = $student->id;
        $checkin->date_time = (clone $now)->format('Y-m-d H:i:s');
        $checkin->status = 0;
        $checkin->save();

        $date_time = (clone $now)->setTimezone(CommonHelper::getSchoolTimezone())->format('Y-m-d H:i:s');
        NotificationHelper::sendCheckinNotification($student, $date_time);

        $message = '<p>'.__('messages.successfully-checkedin-as').' '.$yoyaku->student->full_name.'</p>';
        if($yoyaku->student->image)
        {
            $message .= '<img src="'.$yoyaku->student->getImageUrl().'" style="max-height:100px">';
        }

        $res['status'] = 1;
        $res['title'] = __('messages.successfully-checkedin');
        $res['message'] = $message;

        return $res;
    }

    public function checkoutSubmit(Request $request)
    {
        $res = [];

        $student = $this->getStudentByRFID($request->rfid_token);
        if(!$student)
        {
            $res['status'] = 0;
            $res['message'] = __('messages.unverified-rfid');
            return $res;
        }

        $now = Carbon::now();
        $checkin = new Checkin();
        $checkin->student_id = $student->id;
        $checkin->date_time = (clone $now)->format('Y-m-d H:i:s');
        $checkin->status = 1;
        $checkin->save();

        $date_time = (clone $now)->setTimezone(CommonHelper::getSchoolTimezone())->format('Y-m-d H:i:s');
        NotificationHelper::sendCheckoutNotification($student, $date_time);

        $message = '<p>'.__('messages.successfully-checkedout-as').' '.$student->full_name.'</p>';
        if($student->image)
        {
            $message .= '<img src="'.$student->getImageUrl().'" style="max-height:100px">';
        }

        $res['status'] = 1;
        $res['title'] =  __('messages.successfully-checkedout');
        $res['message'] = $message;
        return $res;
    }

    public function makeReservation(Request $request)
    {
        session(['loggedin_via_terminal' => 1]);
        if(\Auth::user())
        {
            $user = \Auth::user();
            $user_role = $user->get_role();

            if($user_role)
            {
                return redirect($user_role->login_redirect_path);
            }
            else
            {
                return redirect('/');
            }
        }
        else
        {
            return redirect('/');
        }
    }

    public function checkoutBookSubmit(Request $request)
    {
        $student = $this->getStudentByRFID($request->rfid_token);
        if (!$student) {
            $res['status'] = 0;
            $res['message'] = __('messages.unverified-rfid');
            return $res;
        } else {
            if (!$request->barcode) {
                $res['status'] = 1;
                $res['barcode'] = 0;
                $res['rfid_token'] = $request->rfid_token;
                $res['message'] =  'token verified';
                return $res;
            } else {
                try {
                    $book = Books::get_by_barcode($request->barcode);
                    $book->check_quantity();

                    $date = Carbon::now(CommonHelper::getSchoolTimezone())->format('Y-m-d');
                    $book_student = new BookStudents([
                        'book_id' => $book->id,
                        'student_id' => $student->id,
                        'checkout_date' => $date,
                        'status' => 0,
                    ]);
                    $book_student->save();

                    $book->decrease_quantity();

                    $res['status'] = 1;
                    $res['barcode'] = 1;
                    $res['message'] =  __('messages.book-checkedout');
                    return $res;
                } catch (\Exception $e) {
                    $res['status'] = 1;
                    $res['barcode'] = 0;
                    $res['errorBarcodeMessage'] = $e->getMessage();
                    return $res;
                }
            }
        }
    }

    private function getStudentByRFID($rfid_token)
    {
        if(!$rfid_token)
        {
            return NULL;
        }
        return Students::where('rfid_token',$rfid_token)->first();
    }
}
