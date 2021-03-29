<?php

namespace App\Http\Controllers;

use App\ClassUsage;
use App\Discount;
use App\Helpers\ActivityEnum;
use App\Helpers\ActivityLogHelper;
use App\Helpers\AutomatedTagsHelper;
use App\Helpers\CommonHelper;
use App\Helpers\PaymentHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendStripeInvoiceForPayment;
use DB;
use App\Payments;
use App\MonthlyPayments;
use App\Plan;
use App\Settings;
use App\Students;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index()
    {
        $yearMonth = Carbon::now(CommonHelper::getSchoolTimezone())->format('Y-m');
        $payments = DB::table('payments')->select('payments.date', 'payments.points', 'payments.price', 'students.lastname_kanji', 'students.firstname_kanji')->join('students','payments.customer_id','=','students.id')->where('date','LIKE','%'.$yearMonth.'%')->get();
        $attendances = DB::table('attendances')->select('attendances.date','attendances.cancel_policy_id','teachers.nickname','students.lastname_kanji','students.firstname_kanji','payment_plans.points','payment_plans.cost','payment_plans.cost_to_teacher')->join('teachers','attendances.teacher_id','=','teachers.id')->join('students','attendances.customer_id','=','students.id')->join('payment_plans','attendances.payment_plan_id','=','payment_plans.id')->where('date','LIKE','%'.$yearMonth.'%')->orderBy('teachers.id')->get();
        foreach ($attendances as $attendance) {
            if($attendance->cancel_policy_id != NULL) {
                $policy = DB::table('cancellation_policies')->where('id','=',$attendance->cancel_policy_id)->get()->first();
                $attendance->points = $policy->points;
                $attendance->cost_to_teacher = $policy->salary;
            }
        }
        return view('payment.monthly', array('payments' => $payments, 'attendances' => $attendances));
    }

    public function create($id)
    {
        $now = Carbon::now(CommonHelper::getSchoolTimezone());
        $date = $now->format('Y-m-d');
        $expiration_date = (clone $now)->addDays(Settings::get_value('number_of_days_use_payment_points'))->format('Y-m-d');
        return view('payment.create', array('customer_id' => $id, 'expiration_date' => $expiration_date, 'date' => $date));
    }

    public function store(Request $request, $id)
    {
        // date_default_timezone_set("Asia/Tokyo");
        $request->validate([
            'price'=>'required|integer',
            'points'=> 'required',
            'date'=>'required'
        ]);

        if(empty($request->get('expiration_date')))
        {
            $expiration_date = Carbon::createFromFormat('Y-m-d',$request->get('date'))
                            ->addDays(Settings::get_value('number_of_days_use_payment_points'))
                            ->format('Y-m-d');
        } else {
            $expiration_date = $request->get('expiration_date');
        }

        $payment = new Payments([
        	'customer_id' => $id,
            'price' => $request->get('price'),
            'points'=> $request->get('points'),
            'date'=> $request->get('date'),
            'expiration_date' => $expiration_date,
        ]);

        $payment->save();
        return redirect('/student/'.$id.'?nav=payment');
    }

    public function destroy($id, $user_id)
    {
        $payment = Payments::find($id);
        $payment->delete();

        return redirect('/student/'.$user_id.'?nav=payment');
    }

    public function store_monthly_payments(Request $request, $id)
    {
        $student = Students::findOrFail($id);
        if($request->payment_method == 'stripe' && $student->use_stripe_subscription)
        {
            abort(400, __('messages.can-not-use-stripe-as-payment-method-when-stripe-subscription-is-used-to-collect-payments'));
        }

        if($request->payment_type == 'oneoff')
        {
            $customer_id = $id;
            $payment_category = $request->payment_category;
            $price = $request->price;
            $memo = $request->memo;
            $payment_method = $request->payment_method;
            $payment = PaymentHelper::createOneoffPaymentRecord($customer_id, $payment_category, $price, $memo, $payment_method);

            $nav = 'otherpayments';
        }
        else
        {
            if($request->rest_month == 1)
            {
                $customer_id = $id;
                $period = $request->period;
                $payment = PaymentHelper::createRestMonthPaymentRecord($customer_id, $period);
            }
            else
            {
                $customer_id = $id;
                $month_year = $request->period;
                $payment_method = $request->payment_method;
                $payment_breakdown_records = $request->payment_breakdown_records;
                $memo = $request->memo;
                $number_of_lessons = $request->number_of_lessons;
                $discount_id = $request->discount_id;
                $payment = PaymentHelper::createMonthlyPaymentRecord($customer_id, $month_year, $payment_method, $payment_breakdown_records, $memo, $number_of_lessons, $discount_id);
            }

            $nav = 'monthlypayment';
        }

        return [
            'message' => __('messages.payment-added-successfully'),
            'redirect_url' => route('student.show', ['student' => $id,'nav' => $nav])
        ];
    }

    public function update_payment(Request $request, $id)
    {
        $payment = MonthlyPayments::findOrFail($id);
        $oldPayment = clone $payment;

        if(!$payment->canBeEdited())
        {
            $error = __('messages.payment-record-is-not-in-editable-state');
            if($request->expectsJson())
            {
                abort(400, $error);
            }
            else
            {
                return redirect()->back()->with('error', $error);
            }
        }

        if($request->payment_method == 'stripe' && $payment->student->use_stripe_subscription)
        {
            $error = __('messages.can-not-use-stripe-as-payment-method-when-stripe-subscription-is-used-to-collect-payments');
            if($request->expectsJson())
            {
                abort(400, $error);
            }
            else
            {
                return redirect()->back()->with('error', $error);
            }
        }

        if($payment->rest_month)
        {
            $payment->period = $request->period;
            $nav = 'monthlypayment';
            $payment->save();
        }
        elseif($payment->isOneOffPayment())
        {
            $payment->payment_category = $request->payment_category;
            $payment->price = $request->price;
            $payment->memo = $request->memo;
            $payment->payment_method = $request->payment_method;
            $nav = 'otherpayments';
            $payment->save();
        }
        else
        {
            $month_year = $request->period;
            $memo = $request->memo;
            $number_of_lessons = $request->number_of_lessons;

            if ($payment->subscription_id)
            {
                $payment->period = $month_year;
                $payment->memo = $memo;
                $payment->number_of_lessons = $number_of_lessons;
                $payment->save();
            }
            else
            {
                $payment_id = $payment->id;
                $payment_method = $request->payment_method;
                $payment_breakdown_records = $request->payment_breakdown_records;
                $discount_id = $request->discount_id;
                $payment = PaymentHelper::updateMonthlyPaymentRecord($payment_id, $month_year, $payment_method, $payment_breakdown_records, $memo, $number_of_lessons, $discount_id);   
            }
            $nav = 'monthlypayment';
        }

        ActivityLogHelper::create(
            ActivityEnum::PAYMENT_UPDATED,
            CommonHelper::getMainLoggedInUserId(),
            ActivityLogHelper::getPaymentCUDParams($payment)
        );

        ClassUsage::paymentUpdated($payment, $oldPayment);

        $message = __('messages.payment-updated-successfully');
        if($request->expectsJson())
        {
            $out['message'] = $message;
            $out['payment'] = $payment->formatForManagePaymetsPage(\Auth::user());
            return $out;
        }
        else
        {
            return redirect(route('student.show', $payment->student->id).'?nav='.$nav)->with('success', $message);
        }  
    }

    public function destroy_monthly_payments($id, Request $request)
    {
        $payment = MonthlyPayments::find($id);
        
        ActivityLogHelper::create(
            ActivityEnum::PAYMENT_DELETED,
            CommonHelper::getMainLoggedInUserId(),
            ActivityLogHelper::getPaymentCUDParams($payment)
        );
        $student = $payment->student;
        $payment->delete();

        $automatedTagsHelper = new AutomatedTagsHelper($student);
        $automatedTagsHelper->refreshOutsandingPaymentTag();

        ClassUsage::PaymentDeleted($payment);
        
        $message = __('messages.payment-deleted-successfully');

        if($request->wantsJson())
        {
            $out['message'] = $message;
            return $out;
        }
        else
        {
            return redirect()->back()->with('success', $message);
        }
    }

    public function markPaymentAsPaid(Request $request)
    {
        $payment = MonthlyPayments::findOrFail($request->id);
        if(!$payment->canBemarkedAsPaidManually())
        {
            abort(400, __('messages.payment-status-can-not-be-updated'));
        }

        $date_time = \Carbon\Carbon::createFromFormat('Y-m-d H:i',$request->date.' '.$request->time, CommonHelper::getSchoolTimezone())
                        ->setTimezone("UTC")->format('Y-m-d H:i:s');
        $payment->status = 'paid';
        $payment->payment_recieved_at = $date_time;
        $payment->save();

        ClassUsage::paymentPaid($payment);

        $automatedTagsHelper = new AutomatedTagsHelper($payment->student);
        $automatedTagsHelper->refreshOutsandingPaymentTag();

        $out['message'] = __('messages.payment-suceessfully-marked-as-paid');
        $out['payment'] = $payment->formatForManagePaymetsPage(\Auth::user());
        return $out;
    }
   
    public function sendStripeInvoice(Request $request)
    {
        $payment = MonthlyPayments::findOrFail($request->payment_id);
        if(!$payment->canStripeInvoiceBeSent())
        {
            $error = __('messages.stripe-invoice-can-not-be-sent-for-this-payment-record');
            if($request->expectsJson())
            {
                abort(500,$error);
            }
            else
            {
                return redirect()->back()->with('error', $error);
            }
        }

        $error = PaymentHelper::sendStripeInvoice($payment);
        if($error != "")
        {
            if($request->expectsJson())
            {
                abort(500,$error);
            }
            else
            {
                return redirect()->back()->with('error', $error);
            }
        }

        $message = __('messages.stipe-invoice-has-been-sent-successfully');
        if($request->expectsJson())
        {
            $out['message'] = $message;
            $out['payment'] = $payment->formatForManagePaymetsPage(\Auth::user());
        }
        else
        {
            return redirect()->back()->with('success', $message);
        }
        return $out;
    }

    public function sendMutlipleStripeInvoice(Request $request)
    {
        $request->payment_ids;
        $payments = MonthlyPayments::whereIn('id', $request->payment_ids)->get();
        foreach($payments as $payment)
        {
            if(!$payment->canStripeInvoiceBeSent())
            {
                abort(400, __('messages.request-to-send-stripe-invoice-can-not-be-accepted-for-one-or-more-selected-payment-records'));
            }
        }

        $final_payments = [];
        foreach($payments as $payment)
        {
            $payment->status = 'sending-invoice';
            $payment->save();
            SendStripeInvoiceForPayment::dispatch($payment->id)->onQueue('stripe');
            $final_payments[] = $payment->formatForManagePaymetsPage(\Auth::user());
        }

        $out['message'] = __('messages.stripe-invoice-will-be-sent-soon');
        $out['payments'] = $final_payments;
        return $out;
    }

    public function payments()
    {
        $default['page'] = 1;
        $now = \Carbon\Carbon::now()->setTimezone(CommonHelper::getSchoolTimezone());
        $default['from_date'] = (clone $now)->subDays(30)->format('Y-m-d');
        $default['to_date'] = (clone $now)->format('Y-m-d');

        $session = session('accounting_payments_filter');
        $filter['page'] = isset($session['page']) ? $session['page'] : $default['page'];
        $filter['from_date'] = isset($session['from_date']) ? $session['from_date'] : $default['from_date'];
        $filter['to_date'] = isset($session['to_date']) ? $session['to_date'] : $default['to_date']; 
        
        return view('accounting.payments', [
            'filter' => $filter,
            'plans' => Plan::get(),
            'discounts' => Discount::get(),
            'payment_methods' => explode(',', Settings::get_value('payment_methods')),
            'payment_categories' => explode(',', Settings::get_value('payment_categories')),
        ]);
    }

    public function paymentRecords(Request $request)
    {
        $session = [
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'page' => $request->page,
        ];
        $request->session()->put('accounting_payments_filter', $session);

        $monthlyPaymentsQuery = MonthlyPayments::with('student')->where('status', 'paid');
        if($request->from_date)
        {
            $from_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->from_date .' 00:00:00', CommonHelper::getSchoolTimezone())->setTimezone("UTC");
            $monthlyPaymentsQuery->where('payment_recieved_at','>=', $from_date->format('Y-m-d H:i:s'));
        }

        if($request->to_date)
        {
            $to_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->to_date .' 23:59:59', CommonHelper::getSchoolTimezone())->setTimezone("UTC");
            $monthlyPaymentsQuery->where('payment_recieved_at','<=', $to_date->format('Y-m-d H:i:s'));
        }

        $payments_sum = (clone $monthlyPaymentsQuery)->sum('price');
        $monthlyPayments = $monthlyPaymentsQuery->orderBy('payment_recieved_at','DESC')->paginate(100);

        $records = [];
        foreach($monthlyPayments as $payment)
        {
            $records[] = $payment->formatForManagePaymetsPage(\Auth::user());
        }
        
        $out['total_records'] = $monthlyPayments->total();
        $out['last_page'] = $monthlyPayments->lastPage();
        $out['per_page'] = $monthlyPayments->perPage();
        $out['current_page'] = $monthlyPayments->currentPage();
        $out['payments_sum'] = $payments_sum;
        $out['records'] = $records;
        return $out;
    }
}
