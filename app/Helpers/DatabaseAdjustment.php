<?php

namespace App\Helpers;

use App\MonthlyPaymentBreakdown;
use App\MonthlyPayments;
use App\PaymentBreakdownSetting;
use App\PaymentSetting;
use App\ClassesOffDays;
use App\Schedules;
use App\SchoolOffDays;
use App\Yoyaku;
use Carbon\Carbon;

class DatabaseAdjustment {

    public static function removeRedundantYoakusForSchoolOffDays()
    {
        $count_1 = Yoyaku::count();

        $schoolOffDays = SchoolOffDays::all();
        foreach($schoolOffDays as $schoolOffDay)
        {
            $date = $schoolOffDay->date;
            $from_date = $date;
            $to_date = $date;

            $carbonFrom = Carbon::createFromFormat('Y-m-d H:i:s', $from_date .' 00:00:00');
            $carbonTo = Carbon::createFromFormat('Y-m-d H:i:s', $to_date. '23:59:59');
    
            $schedules  = Schedules::where(function($query) use($from_date,$to_date){
                                $query->where('date',NULL);
                                $query->orWhereBetween('date',[$from_date,$to_date]);
                            })->get();
    
            $events = array();
            foreach($schedules as $key => $schedule) {
                if(empty($schedule->date) && !empty($schedule->day_of_week))
                {
                    if(!empty($schedule->start_date) && !empty($schedule->end_date)) 
                    {
                        $start_date = Carbon::parse($schedule->start_date);
                        $end_date = Carbon::parse($schedule->end_date);
    
                        if($schedule->day_of_week == (clone $start_date)->format('l'))
                        {
                            $next_day_of_week = $start_date;
                        }
                        else
                        {
                            $next_day_of_week = $start_date->modify('next '.$schedule->day_of_week);
                        }
    
                        while($next_day_of_week->lessThanOrEqualTo($end_date)) {
                            if(
                                $next_day_of_week->greaterThanOrEqualTo($carbonFrom) &&
                                $next_day_of_week->lessThanOrEqualTo($carbonTo))
                            {
                                $event_date = $next_day_of_week->format('Y-m-d');
                                $events[] = [
                                    'schedule' => $schedule,
                                    'date' => $event_date
                                ];
                            }
                            $next_day_of_week = $next_day_of_week->modify('next '.$schedule->day_of_week);
                        }
                    }
                } else {
                    $events[] = [
                        'schedule' => $schedule,
                        'date' => $schedule->date
                    ];
                }
            }

            foreach($events as $event)
            {
                CommonHelper::cancelClass($event['schedule'], $event['date'], 0, 0);
            }
        }

        $count_2 = Yoyaku::count();
        $removed_yoyakus = $count_1 - $count_2;
        \Log::info("Executed removeRedundantYoakusForSchoolOffDays, Removed {$removed_yoyakus} Yoyakus");
    }

    public static function removeRedundantYoakusForClassOffDays()
    {
        // SQL to cross check
        // select count(distinct(yoyakus.id)) from yoyakus
        // LEFT JOIN classes_off_days ON classes_off_days.date = yoyakus.date and classes_off_days.schedule_id = yoyakus.schedule_id
        // LEFT JOIN school_off_days ON yoyakus.date = school_off_days.date
        // where classes_off_days.id IS NOT NULL

        $count_1 = Yoyaku::count();

        foreach(ClassesOffDays::get() as $classOffDay)
        {
            $exists = Yoyaku::where('date', $classOffDay->date)->where('schedule_id', $classOffDay->schedule_id)->exists();
            if ($exists)
            {
                CommonHelper::cancelClass($classOffDay->schedule, $classOffDay->date, 0, 0);
            }
        }

        $count_2 = Yoyaku::count();
        $removed_yoyakus = $count_1 - $count_2;
        \Log::info("Executed removeRedundantYoakusForClassOffDays, Removed {$removed_yoyakus} Yoyakus");
    }
    
    public static function migrateMonthlyPayments()
    {
        $MonthlyPayments = MonthlyPayments::
                            OnlyMonthyPayments()
                            ->where('rest_month', 0)
                            ->whereDoesntHave('monthlyPaymentBreakdowns')
                            ->get();
        foreach($MonthlyPayments as $MonthlyPayment) {
            $monthlyPaymentBreakdown = new MonthlyPaymentBreakdown();
            $monthlyPaymentBreakdown->monthly_payment_id = $MonthlyPayment->id;
            $monthlyPaymentBreakdown->description = 'Legacy payment';
            $monthlyPaymentBreakdown->unit_amount = $MonthlyPayment->price;
            $monthlyPaymentBreakdown->quantity = 1;
            $monthlyPaymentBreakdown->save();
        }

        MonthlyPaymentBreakdown::where('quantity', 0)->update(['quantity' => 1]);
    }

    public static function migratePaymentSettings()
    {
        $paymentSettings = PaymentSetting::all();
        foreach($paymentSettings as $paymentSetting) {
            if ($paymentSetting->price)
            {
                $paymentBreakdownSetting = new PaymentBreakdownSetting();
                $paymentBreakdownSetting->student_id = $paymentSetting->student_id;
                $paymentBreakdownSetting->description = "Legacy payment ("  . $paymentSetting->no_of_lessons . " Lessons)";
                $paymentBreakdownSetting->quantity = 1;
                $paymentBreakdownSetting->unit_amount = $paymentSetting->price;
                $paymentBreakdownSetting->save();
            }
        }
    }

    public static function deleteMissingRelationshipRecords()
    {
        // Cleanup for student related tables
        $sql = "DELETE t1 FROM attendances t1
                LEFT JOIN students t2 on t1.customer_id = t2.id
                WHERE t2.id IS NULL";
        \DB::statement($sql);

        $sql = "DELETE t1 FROM checkins t1
                LEFT JOIN students t2 on t1.student_id = t2.id
                WHERE t2.id IS NULL";
        \DB::statement($sql);

        $sql = "DELETE t1 FROM class_usages t1
                LEFT JOIN students t2 on t1.customer_id = t2.id
                WHERE t2.id IS NULL";
        \DB::statement($sql);

        $sql = "DELETE t1 FROM class_usage_summaries t1
                LEFT JOIN students t2 on t1.customer_id = t2.id
                WHERE t2.id IS NULL";
        \DB::statement($sql);

        $sql = "DELETE t1 FROM contacts t1
                LEFT JOIN students t2 on t1.customer_id = t2.id
                WHERE t2.id IS NULL";
        \DB::statement($sql);

        $sql = "DELETE t1 FROM monthly_payments t1
                LEFT JOIN students t2 on t1.customer_id = t2.id
                WHERE t2.id IS NULL";
        \DB::statement($sql);

        $sql = "DELETE t1 FROM payments t1
                LEFT JOIN students t2 on t1.customer_id = t2.id
                WHERE t2.id IS NULL";
        \DB::statement($sql);

        // Cleanup for user related tables
        $sql = "DELETE t1 FROM activity_logs t1
                LEFT JOIN users t2 on t1.user_id = t2.id
                WHERE t2.id IS NULL";
        \DB::statement($sql);
    }
}
?>
