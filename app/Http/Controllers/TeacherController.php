<?php

namespace App\Http\Controllers;

use App\Attendances;
use App\ClassesOffDays;
use App\CourseSchedules;
use App\CustomFields;
use App\CustomFieldValue;
use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
use App\Http\Requests\TeacherRequest;
use App\Http\Controllers\Controller;
use App\Schedules;
use DB;
use App\Teachers;
use App\User;
use App\Settings;
use App\Yoyaku;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teachers::with('user')->where('status','=',0)->get();
        $archivedTeachers = Teachers::with('user')->where('status','=',1)->get();
        $today_date = \Carbon\Carbon::now(CommonHelper::getSchoolTimezone())->format('Y-m-d');
        return view('teacher.list', array(
            'teachers' => $teachers,
            'archivedTeachers' => $archivedTeachers,
            'today_date' => $today_date
        ));
    }

    public function create()
    {
        return view('teacher.create', [
            'default_color' => Settings::get_value('default_calendar_color_coding'),
            'custom_fields' => CustomFields::where('data_model', 'Teachers')->get(), 
        ]);
    }

    public function store(TeacherRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->fullname,
                'username' => $request->username,
                'email' => $request->email,
                'zoom_email' => $request->zoom_email,
                'receive_emails' => 1,
                'password' => bcrypt($request->password),
                'lang' => User::getDefaultLanuage('Teacher')
            ]);

            $teacher = Teachers::create([
                'name' => $request->fullname,
                'furigana' => $request->furigana,
                'nickname' => $request->nickname,
                'username' => $request->username,
                'birthday' => $request->birthday,
                'birthplace' => $request->birthplace,
                'profile' => $request->profile,
                'status'=> 0,
                'user_id' => $user->id,
                'color_coding' => $request->color_coding
            ]);

            $custom_fields = CustomFields::where('data_model', 'Teachers')->get();
            if ($custom_fields) {
                foreach ($custom_fields as $custom_field) {
                    if ( !empty($request->{'custom_'.$custom_field->field_name}) ) {
                        CustomFieldValue::create([
                            'custom_field_id' => $custom_field->id,
                            'model_id' => $teacher->id,
                            'field_value' => $request->{'custom_'.$custom_field->field_name}
                        ]);
                    }
                }
            }

            $user->assignRole('Teacher');
            NotificationHelper::sendNewUserNotification(User::find($user->id), $request->password);

            return redirect('/teacher')->with('success', __('messages.teacher-has-been-added'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $teacher = Teachers::find($id);
        return view('teacher.details', array(
            'teacher' => $teacher,
            'custom_fields' => CustomFields::where('data_model', 'Teachers')->get(), 
        ));
    }

    public function edit($id)
    {
        $teacher = Teachers::find($id);
        return view('teacher.edit', array(
            'teacher' => $teacher,
            'custom_fields' => CustomFields::where('data_model', 'Teachers')->get(), 
            'default_color' => Settings::get_value('default_calendar_color_coding')
        ));
    }

    public function update(TeacherRequest $request, $id)
    {
        try {
            $teacher = Teachers::find($id);
            $user = $teacher->user;

            $user->update([
                'email' => $request->email,
                'zoom_email' => $request->zoom_email,
                'name' => $request->fullname,
                'username' => $request->username
            ]);

            $teacher->update([
                'name' => $request->fullname,
                'furigana' => $request->furigana,
                'nickname' => $request->nickname,
                'username' => $request->username,
                'birthday' => $request->birthday,
                'birthplace' => $request->birthplace,
                'profile' => $request->profile,
                'color_coding' => $request->color_coding
            ]);

            $custom_fields = CustomFields::where('data_model', 'Teachers')->get();
            if ($custom_fields) {
                foreach ($custom_fields as $custom_field) {
                    $field = CustomFieldValue::where('model_id', $teacher->id)->where('custom_field_id', $custom_field->id)->first();
                    if ($field) {
                        if ( !empty($request->{'custom_'.$custom_field->field_name}) ) {
                            $field->field_value = $request->{'custom_'.$custom_field->field_name};
                            $field->save();
                        } else {
                            $field->delete();
                        }
                    } else {
                        if ( !empty($request->{'custom_'.$custom_field->field_name}) ) {
                            CustomFieldValue::create([
                                'custom_field_id' => $custom_field->id,
                                'model_id' => $teacher->id,
                                'field_value' => $request->{'custom_'.$custom_field->field_name}
                            ]);
                        }
                    }
                }
            }
            return redirect('/teacher/'.$id.'/edit')->with('success', __('messages.teacher-has-been-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $teacher = Teachers::find($id);
        
        $res = $teacher->canBeDeleted();
        if($res['can_be_deleted'] != 1)
        {
            return redirect()->back()->with('error', $res['reason']);
        }

        $teacher->user()->delete();
        $teacher->delete();

        return redirect('/teacher')->with('success',__('messages.teacher-has-been-deleted-successfully'));
    }

    public function archiveTeacher(Request $request)
    {      
        $take_over_teacher_id = $request->take_over_teacher_id;
        $take_over_date = $request->take_over_date;

        $teacherTobeArchived = Teachers::findOrFail($request->teacher_id);   
        if($teacherTobeArchived->status == 1)
        {
            return redirect()->back()->with('error', __('messages.teacher-is-already-archived'));    
        }

        // Change All Future class of teacher by take_over_teacher from given date.
        $schedules = Schedules::where('teacher_id', $teacherTobeArchived->id)->get();
        foreach($schedules as $schedule)
        {
            if($schedule->date)// One Off Class
            {
                if($schedule->date >= $take_over_date) // In Future - Simply Update schedule
                {
                    $schedule->teacher_id = $take_over_teacher_id;
                    $schedule->save();
                }
            }
            else if($schedule->start_date && $schedule->end_date) // Recurring Class
            {
                if($schedule->end_date >= $take_over_date) // Ending In Future
                {
                    if($schedule->start_date >= $take_over_date) // Starting In Future - Simply Update schedule
                    {
                        $schedule->teacher_id = $take_over_teacher_id;
                        $schedule->save();
                    }
                    else // Split & Update schedule
                    {
                        $split_from_day = Carbon::createFromFormat('Y-m-d',$take_over_date, CommonHelper::getSchoolTimezone())->startOfDay();
                        $previous_instance = (clone $split_from_day)->modify('previous '.$schedule->day_of_week);

                        // Create new schedule by copying existing schedule.
                        $new_start_date = $split_from_day->format('Y-m-d');
                        $new_end_date = $previous_instance->format('Y-m-d');

                        $new_schedule = $schedule->replicate();
                        $new_schedule->start_date = $new_start_date; // End date is copied from original schedule.
                        $new_schedule->teacher_id = $take_over_teacher_id;
                        $new_schedule->save();

                        $new_schedule = Schedules::find($new_schedule->id); // Need to refetch new schedule from db other wise laravel calls relationships on old schedule object. (used in below code)

                        if(@$schedule->course_schedule->course_id)
                        {
                            $courseSchedule = new CourseSchedules();
                            $courseSchedule->schedule_id = $new_schedule->id;
                            $courseSchedule->course_id = @$schedule->course_schedule->course_id;
                            $courseSchedule->save();
                        }
                        
                        // Shorten the end date of exsting schedule.
                        $schedule->end_date = $new_end_date;
                        $schedule->save();

                        ClassesOffDays::where('schedule_id', $schedule->id)
                                    ->where('date','>', $new_end_date)
                                    ->update([
                                        'schedule_id' => $new_schedule->id
                                    ]);

                        Yoyaku::where('schedule_id', $schedule->id)
                                    ->where('date','>', $new_end_date)
                                    ->update([
                                        'schedule_id' => $new_schedule->id
                                    ]);

                        Attendances::where('schedule_id', $schedule->id)
                                    ->where('date','>', $new_end_date)
                                    ->update([
                                        'schedule_id' => $new_schedule->id,
                                    ]);
                            
                    }
                }
            }
        }

        $teacherTobeArchived->archive();
        return redirect()->back()->with('success', __('messages.teacher-archived-successfully'));
    }
}
