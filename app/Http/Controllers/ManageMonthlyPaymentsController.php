<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Helpers\CommonHelper;
use App\Helpers\PaymentHelper;
use App\MonthlyPayments;
use App\Plan;
use App\Settings;
use App\Students;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManageMonthlyPaymentsController extends Controller
{
    public function index($month_year = NULL)
    {
        if (!$month_year) {
            $month_year = Carbon::now(CommonHelper::getSchoolTimezone())->format('Y-m');
        }
        return view('accounting.manage_monthly_payments', compact('month_year'));
    }

    public function data($month_year)
    {
        $student_role_ids = explode(",", Settings::get_value('generate_payment_info_for_roles'));

        $students = Students::join('model_has_roles',function($join){
                        $join->on('model_has_roles.model_id','=','students.user_id')
                            ->where('model_has_roles.model_type','=','App\User');
                    })
                    ->where('use_stripe_subscription', 0)
                    ->whereIn('model_has_roles.role_id', $student_role_ids)
                    ->orderBy('firstname','ASC')
                    ->orderBy('lastname','ASC')
                    ->get();

        $final_students = [];
        foreach($students as $student){
            $temp = [
                'id' => $student->id,
                'fullname' => $student->fullname,
                'payment_settings' => $student->paymentSetting,
                'payment_breakdown_records' => $student->paymentBreakdownSettings()->with('plan')->get(),
                'use_stripe_subscription' => $student->use_stripe_subscription,
            ];
            $final_students[] = $temp;
        }

        $payments = MonthlyPayments::with('student')->OnlyMonthyPayments()->where('period', $month_year)->get();
        $generated_payments = [];
        foreach($payments as $payment)
        {
            $generated_payments[] = $payment->formatForManagePaymetsPage(\Auth::user());
        }

        $out['students'] = $final_students;
        $out['generated_payments'] = $generated_payments;
        $out['plans'] = Plan::get();
        $out['discounts'] = Discount::get();
        $out['payment_methods'] = explode(',', Settings::get_value('payment_methods'));
        return $out;
    }

    public function generatePaymentRecords(Request $request)
    {
        $month_year = $request->month_year;

        // Validate request
        $customer_ids = collect($request->payments)->pluck('customer_id')->all();
        $payments = MonthlyPayments::OnlyMonthyPayments()->where('period', $month_year)->whereIn('customer_id', $customer_ids)->get();

        if(count($payments) > 0)
        {        
            $student_names = [];
            foreach($payments as $payment) {
                $student_names[] = $payment->student->fullname;
            }

            $student_names = array_unique($student_names);

            return [
                'status' => 0,
                'message' => __('messages.payment-record-already-exists-for-following-student') . implode(", ", $student_names)
            ];
        }

        foreach($request->payments as $payment)
        {
            $student = Students::findOrFail($payment['customer_id']);
            if($payment['payment_method'] == 'stripe' && $student->use_stripe_subscription)
            {
                $error = __('messages.can-not-use-stripe-as-payment-method-when-stripe-subscription-is-used-to-collect-payments-for-student').' '. $student->fullname;
                abort(400, $error);
            }
        }

        foreach($request->payments as $payment)
        {
            $customer_id = $payment['customer_id'];            
            if($payment['rest_month'])
            {
                PaymentHelper::createRestMonthPaymentRecord($customer_id, $month_year);
            }
            else
            {
                $payment_method = $payment['payment_method'];
                $payment_breakdown_records = $payment['payment_breakdown_records'];
                $memo = $payment['memo'];
                $number_of_lessons = $payment['number_of_lessons'];
                $discount_id = $payment['discount_id'];
                PaymentHelper::createMonthlyPaymentRecord($customer_id, $month_year, $payment_method, $payment_breakdown_records, $memo, $number_of_lessons, $discount_id);
            }
        }

        return [
            'status' => 1,
            'message' => __('messages.payment-records-generated-successfully')
        ];
    }

    public function markAsPaidData()
    {
        $out['date'] = \Carbon\carbon::now(\App\Helpers\CommonHelper::getSchoolTimezone())->format('Y-m-d');
        $out['time'] = \Carbon\carbon::now(\App\Helpers\CommonHelper::getSchoolTimezone())->format('H:i');
        return $out;
    }
}
