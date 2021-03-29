<?php

namespace App\Helpers;

use App\ClassUsage;
use App\Discount;
use App\MonthlyPaymentBreakdown;
use App\MonthlyPayments;
use App\Plan;
use App\Settings;
use App\StripeSubscription;
use App\StripeSubscriptionPlanItem;
use App\User;
use Carbon\Carbon;

class PaymentHelper {

    public static function createRestMonthPaymentRecord($customer_id, $period)
    {
        $payment = new MonthlyPayments();
        $payment->customer_id = $customer_id;
        $payment->status = 'draft';

        $payment->period = $period;

        $payment->rest_month = 1;
        $payment->number_of_lessons = 0;
        $payment->price = 0;
        $payment->payment_method = NULL;
        
        $payment->save();
        
        self::paymentCreated($payment);
        
        return $payment;
    }

    public static function createMonthlyPaymentRecord($customer_id, $month_year, $payment_method, $payment_breakdown_records, $memo, $number_of_lessons, $discount_id)
    {
        $payment = new MonthlyPayments();
        $payment->customer_id = $customer_id;
        $payment->status = 'draft';
        
        $payment->period = $month_year;
        $payment->payment_method = $payment_method;
        $payment->memo = $memo;
        $payment->number_of_lessons = $number_of_lessons;

        $payment = self::savePaymentBreakdown($payment, $payment_breakdown_records, $discount_id);
        if ($payment) {
            self::paymentCreated($payment);
        }
        return $payment;
    }

    public static function updateMonthlyPaymentRecord($payment_id, $month_year, $payment_method, $payment_breakdown_records, $memo, $number_of_lessons, $discount_id)
    {
        $payment = MonthlyPayments::find($payment_id);
        $payment->period = $month_year;
        $payment->payment_method = $payment_method;
        $payment->memo = $memo;
        $payment->number_of_lessons = $number_of_lessons;

        $payment = self::savePaymentBreakdown($payment, $payment_breakdown_records, $discount_id);
        return $payment;
    }

    public static function savePaymentBreakdown($payment, $payment_breakdown_records, $discount_id)
    {
        $price = 0;
        foreach($payment_breakdown_records as $record)
        {
            if($record['plan_id'])
            {
                $plan = Plan::find($record['plan_id']);
                $unit_amount = $plan->price_per_month;
            }
            else
            {
                $unit_amount = $record['unit_amount'];
            }
            $price += ( $unit_amount * $record['quantity']);
        }

        $discount_amount = 0;
        if ($discount_id) {
            $discount = Discount::find($discount_id);
            $discount_amount = $discount->amount;
        }

        $price -= $discount_amount;

        try {
            \DB::beginTransaction();

            $payment->discount_id = $discount_id;
            $payment->discount_amount = $discount_amount;
            $payment->price = $price;
            $payment->save();
            

            $payment->monthlyPaymentBreakdowns()->delete();

            foreach($payment_breakdown_records as $record)
            {
                $breakdown = new MonthlyPaymentBreakdown();
                $breakdown->monthly_payment_id = $payment->id;
                $breakdown->quantity = $record['quantity'];
                if($record['plan_id'])
                {
                    $breakdown->plan_id = $record['plan_id'];
                }
                else
                {
                    $breakdown->description = $record['description'];
                    $breakdown->unit_amount = $record['unit_amount'];
                }

                $breakdown->save();
            }

            \DB::commit();
            
            return $payment;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    public static function createOneoffPaymentRecord($customer_id, $payment_category, $price, $memo, $payment_method)
    {
        $payment = new MonthlyPayments();
        $payment->customer_id = $customer_id;
        $payment->status = 'draft';

        $payment->payment_category = $payment_category;
        $payment->price = $price;
        $payment->memo = $memo;
        $payment->payment_method = $payment_method;

        $payment->save();

        self::paymentCreated($payment);

        return $payment;
    }

    private static function paymentCreated($payment)
    {
        ActivityLogHelper::create(
            ActivityEnum::PAYMENT_CREATED,
            CommonHelper::getMainLoggedInUserId(),
            ActivityLogHelper::getPaymentCUDParams($payment)
        );

        $automatedTagsHelper = new AutomatedTagsHelper($payment->student);
        $automatedTagsHelper->refreshOutsandingPaymentTag();
    }

    public static function sendStripeInvoice($payment)
    {
        $user = $payment->student->user;
        
        try {
            $stripe_customer_id = $user->getStripeCustomerId();

            // Delete pending invoice items from stripe
            $stripe = new \Stripe\StripeClient(Settings::get_value('stripe_secret_key'));
            $invoiceItems = $stripe->invoiceItems->all(['limit' => 100, 'customer' => $stripe_customer_id, 'pending' => 'true']);
            foreach($invoiceItems as $invoiceItem)
            {
                $stripe->invoiceItems->delete($invoiceItem->id, []);
            }

            \Stripe\Stripe::setApiKey(Settings::get_value('stripe_secret_key'));
            $currency = Settings::get_value('stripe_currency');

            if($payment->isOneOffPayment())
            {
                \Stripe\InvoiceItem::create([
                    'customer' => $stripe_customer_id,
                    'currency' => $currency,
                    'description' => $payment->payment_category,
                    'amount' => CommonHelper::getStripeAmount($currency, $payment->price)
                ]);
            }
            else
            {
                $payment_breakdown_records = $payment->monthlyPaymentBreakdowns()->with('plan')->get();
                foreach($payment_breakdown_records as $record)
                {
                    if($record->plan)
                    {
                        $description = $record->plan->name." ({$record->plan->number_of_lessons} Lessons)";
                        $unit_amount = $record->plan->price_per_month;
                    }
                    else
                    {
                        $description = $record->description;
                        $unit_amount = $record->unit_amount;
                    }
                    $quantity = $record->quantity;

                    \Stripe\InvoiceItem::create([
                        'customer' => $stripe_customer_id,
                        'currency' => $currency,
                        'description' => $description,
                        'quantity' => $quantity,
                        'unit_amount' => CommonHelper::getStripeAmount($currency, $unit_amount)
                    ]);
                }
            }

            $coupon = $payment->discount ? $payment->discount->stripe_coupon_id : NULL;

            $discounts = [];
            if ($coupon) {
                $discounts[] = [
                    'coupon' => $coupon
                ];
            }

            $invoice = \Stripe\Invoice::create([
                'customer' => $stripe_customer_id,
                'collection_method' => 'send_invoice',
                'description' => $payment->memo,
                'days_until_due' => 30,
                'discounts' => $discounts
            ]);

            $invoice->sendInvoice();
        
            $payment->stripe_invoice_id = $invoice->id;
            $payment->stripe_invoice_url = $invoice->hosted_invoice_url;
            $payment->status = 'invoice-sent';
            $payment->save();
            return "";
        }
        catch(\Exception $e){
            $payment->status = 'stripe-error';
            $payment->save();

            return "Stripe Error: ".$e->getMessage();
        }
    }

    public static function saveStripeSubscription($stipeSubscription, $user, $plan_items, $discount_id)
    {
        try {
            $coupon = NULL;
            if ($discount_id) {
                $discount = Discount::findOrFail($discount_id);
                $coupon = $discount->stripe_coupon_id;
            }
            $params = [
                'proration_behavior' => 'none',
                'coupon' => $coupon
            ];
            $stripe = new \Stripe\StripeClient(Settings::get_value('stripe_secret_key'));

            if (!$stipeSubscription) 
            {
                $stripeHelper = new StripeHelper();
                $subscription_starts_at = $stripeHelper->getFirstInvoiceTime();
                $params['customer'] = $user->getStripeCustomerId();
                $params['billing_cycle_anchor'] = $subscription_starts_at->setTimezone('UTC')->timestamp;

                $items = [];
                foreach($plan_items as $record) {
                    $items[] = [
                        'price' => Plan::findOrFail($record['plan_id'])->stripe_price_id,
                        'quantity' => $record['quantity']
                    ];
                }
                $params['items'] = $items;
                $stripeSubscriptionRes = $stripe->subscriptions->create($params);
            }
            else 
            {
                $plan_items_by_id = collect($plan_items)->mapWithKeys(function($item) {
                    return [$item['plan_id'] => $item['quantity']];
                });
                $dbPlanItemsById = $stipeSubscription->stripeSubscriptionPlanItems->mapWithKeys(function($item){
                    return [$item['plan_id'] => $item];
                });
                $items = [];
                // Sortout existing plan items
                foreach($dbPlanItemsById as $plan_id => $dbPlanItem) 
                {
                    if(isset($plan_items_by_id[$plan_id]))
                    {
                        $items[] = [
                            'id' => $dbPlanItem->stripe_item_id,
                            'quantity' => $plan_items_by_id[$plan_id]
                        ];
                    }
                    else
                    {
                        $items[] = [
                            'id' => $dbPlanItem->stripe_item_id,
                            'deleted' => true
                        ];
                    }
                }
                // check if any new existing plan items added
                foreach($plan_items_by_id as $plan_id => $quantity)
                {
                    if (!isset($dbPlanItemsById[$plan_id])) {
                        $items[] = [
                            'price' => Plan::findOrFail($plan_id)->stripe_price_id,
                            'quantity' => $quantity
                        ];
                    }
                }
                $params['items'] = $items;
                $stripeSubscriptionRes = $stripe->subscriptions->update($stipeSubscription->stripe_subscription_id, $params);
            }

            $stripeSubscription = self::updateLocalSubscriptionData($stripeSubscriptionRes->toArray());

            return [
                'status' => 1,
                'stripeSubscription' => $stripeSubscription
            ];

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return [
                'status' => 0,
                'message' => __('messages.stripe-error').": ".$e->getMessage()
            ];
        }
    }

    public static function cancelStripeSubscription($stripe_subscription_id)
    {
        try {
            $stripe = new \Stripe\StripeClient(Settings::get_value('stripe_secret_key'));
            $stripeSubscriptionRes = $stripe->subscriptions->cancel(
                $stripe_subscription_id,
                []
            );
            self::updateLocalSubscriptionData($stripeSubscriptionRes->toArray());

            return [
                'status' => 1,
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return [
                'status' => 0,
                'message' => __('messages.stripe-error').": ".$e->getMessage()
            ];
        }
    }

    public static function updateLocalSubscriptionData($stripeSubscriptionData)
    {
        $stripeSubscription = StripeSubscription::where('stripe_subscription_id', $stripeSubscriptionData['id'])->first();
        if (!$stripeSubscription) {
            $stripeSubscription = new StripeSubscription();
            $stripeSubscription->stripe_subscription_id = $stripeSubscriptionData['id'];
            $stripeSubscription->user_id = User::where('stripe_customer_id', $stripeSubscriptionData['customer'])->first()->id;
        }

        $discount_id = NULL;
        if ($stripeSubscriptionData['discount']) {
            $discount_id = Discount::where('stripe_coupon_id', $stripeSubscriptionData['discount']['coupon']['id'])->first()->id;
        }

        $stripeSubscription->status = $stripeSubscriptionData['status'];
        $stripeSubscription->discount_id = $discount_id;
        
        $requires_payment_method = 0; 
        if ($stripeSubscription->isDirty('status') && in_array($stripeSubscription->status, StripeSubscription::ERROR_STATUSES))
        {
            \Stripe\Stripe::setApiKey(Settings::get_value('stripe_secret_key'));
            $stripeSubscriptionRes = \Stripe\Subscription::retrieve([ 'id' => $stripeSubscription->stripe_subscription_id, 'expand' => ['latest_invoice.payment_intent']]);
            $payment_intent_status = $stripeSubscriptionRes->latest_invoice->payment_intent->status;
            $stripeSubscription->payment_intent_status = $payment_intent_status;

            if($payment_intent_status == 'requires_payment_method')
            {
                $requires_payment_method = 1;
            }
        }
        else if(!in_array($stripeSubscription->status, StripeSubscription::ERROR_STATUSES))
        {
            $stripeSubscription->payment_intent_status = NULL;
        }
        
        $stripeSubscription->save();
        
        $stripeSubscription->stripeSubscriptionPlanItems()->delete();
        
        foreach($stripeSubscriptionData['items']['data'] as $stripeItem) {
            $stripeSubscriptionPlanItem = new StripeSubscriptionPlanItem();
            $stripeSubscriptionPlanItem->stripe_subscription_id = $stripeSubscription->id;
            $stripeSubscriptionPlanItem->plan_id = Plan::where('stripe_price_id', $stripeItem['price']['id'])->first()->id;
            $stripeSubscriptionPlanItem->quantity = $stripeItem['quantity'];
            $stripeSubscriptionPlanItem->stripe_item_id = $stripeItem['id'];
            $stripeSubscriptionPlanItem->save();
        }

        $automatedTagsHelper = new AutomatedTagsHelper($stripeSubscription->user->student);
        $automatedTagsHelper->refreshStripeSubscriptionErrorTag();
        
        if($requires_payment_method == 1) 
        {
            NotificationHelper::sendStripeSubscriptionRequiresNewPaymentMethodNotification($stripeSubscription);
        }

        return $stripeSubscription;
    }

    public static function getUpcommingStripeInvoice($stripe_subscription_id)
    {
        \Stripe\Stripe::setApiKey(Settings::get_value('stripe_secret_key'));
        
        try {
            $upcommingInvoice = \Stripe\Invoice::upcoming(["subscription" => $stripe_subscription_id]);
            $lines = self::getAllLineItemsOfInvoice($upcommingInvoice);
            $upcommingInvoice = $upcommingInvoice->toArray();
            $upcommingInvoice['lines'] = $lines;

            // Convert amounts for displaying
            $currency = Settings::get_value('stripe_currency');
            $lines = [];
            foreach($upcommingInvoice['lines'] as $line) {
                $line['amount'] = CommonHelper::getStripeToLocalAmount($currency, $line['amount']);
                $line['price']['unit_amount'] = CommonHelper::getStripeToLocalAmount($currency, $line['price']['unit_amount']);
                $lines[] = $line;
            }
            $upcommingInvoice['lines'] = $lines;
            $upcommingInvoice['subtotal'] = CommonHelper::getStripeToLocalAmount($currency, $upcommingInvoice['subtotal']);
            if (isset($upcommingInvoice['discount']['coupon']['amount_off'])) {
                $upcommingInvoice['discount']['coupon']['amount_off'] = CommonHelper::getStripeToLocalAmount($currency, $upcommingInvoice['discount']['coupon']['amount_off']);
            }
            $upcommingInvoice['total'] = CommonHelper::getStripeToLocalAmount($currency, $upcommingInvoice['total']);
            $upcommingInvoice['starting_balance'] = CommonHelper::getStripeToLocalAmount($currency, $upcommingInvoice['starting_balance']);
            $upcommingInvoice['amount_due'] = CommonHelper::getStripeToLocalAmount($currency, $upcommingInvoice['amount_due']);

            return [
                'status' => 1,
                'upcommingInvoice' => $upcommingInvoice
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            abort(400,  __('messages.stripe-error').": ".$e->getMessage());
        }
    }

    public static function getAllLineItemsOfInvoice($stripeInvoice)
    {
        $oneOffInvoiceItems = [];
        $subscriptionInvoiceItems = [];
        foreach($stripeInvoice->lines->autoPagingIterator() as $item) {
            $line = $item->toArray();

            if ($line['type'] == 'subscription')
            {
                $line['description'] = Plan::where('stripe_price_id', $line['price']['id'])->first()->name;
                $subscriptionInvoiceItems[] = $line;
            }
            else
            {
                $oneOffInvoiceItems[] = $line;
            }
        }
        $oneOffInvoiceItems = array_reverse($oneOffInvoiceItems);
        $lines = array_merge($oneOffInvoiceItems, $subscriptionInvoiceItems);
        return $lines;
    }

    public static function createPaymentEntryFromInvoice($stripe_invoice_Id)
    {
        $exists = MonthlyPayments::where('stripe_invoice_id', $stripe_invoice_Id)->exists();
        if ($exists) {
            return;
        }

        \Stripe\Stripe::setApiKey(Settings::get_value('stripe_secret_key'));
        $invoice = \Stripe\Invoice::retrieve([ 'id' => $stripe_invoice_Id ]);
        $lines = self::getAllLineItemsOfInvoice($invoice);
        $currency = Settings::get_value('stripe_currency');
        
        $payment_breakdown_records = [];
        $number_of_lessons = 0;
        foreach($lines as $line)
        {
            $temp = [];
            if($line['type'] == 'subscription') 
            {
                $plan = Plan::where('stripe_price_id', $line['price']['id'])->first();
                $number_of_lessons += ( $plan->number_of_lessons * $line['quantity'] );
                $temp['plan_id'] = $plan->id;
                $temp['description'] = NULL;
                $temp['quantity'] = $line['quantity'];
                $temp['unit_amount'] = NULL;
            }
            else 
            {
                $temp['plan_id'] = NULL;
                $temp['description'] = $line['description'];
                $temp['quantity'] = $line['quantity'];
                $temp['unit_amount'] = CommonHelper::getStripeToLocalAmount($currency,$line['unit_amount']);
            }
            $payment_breakdown_records[] = $temp;
        }

        $payment = new MonthlyPayments();
        $payment->customer_id = User::where('stripe_customer_id', $invoice->customer)->first()->student->id;
        $payment->price = CommonHelper::getStripeToLocalAmount($currency,$invoice->amount_paid);
        $payment->period = Carbon::createFromTimestampUTC($invoice->period_end)->setTimezone(CommonHelper::getSchoolTimezone())->format('Y-m');
        $payment->number_of_lessons = $number_of_lessons;
        $payment->payment_method = 'stripe subscription';
        $payment->memo = $invoice->description;
        $payment->status = 'paid';
        $payment->stripe_invoice_id = $invoice->id;
        $payment->stripe_invoice_url = $invoice->hosted_invoice_url;
        $payment->payment_recieved_at = Carbon::createFromTimestampUTC($invoice->status_transitions->paid_at)->format('Y-m-d H:i:s');
        if($invoice->discount) {
            $payment->discount_id = Discount::where('stripe_coupon_id', $invoice->discount->coupon->id)->first()->id;
            $payment->discount_amount = CommonHelper::getStripeToLocalAmount($currency,$invoice->total_discount_amounts[0]['amount']);
        }
        $payment->subscription_id = StripeSubscription::where('stripe_subscription_id', $invoice->subscription)->first()->id;
        $payment->save();

        foreach($payment_breakdown_records as $record)
        {
            $paymentBreakdown = new MonthlyPaymentBreakdown();
            $paymentBreakdown->monthly_payment_id = $payment->id;
            $paymentBreakdown->plan_id = $record['plan_id'];
            $paymentBreakdown->description = $record['description'];
            $paymentBreakdown->quantity = $record['quantity'];
            $paymentBreakdown->unit_amount = $record['unit_amount'];
            $paymentBreakdown->save();
        }

        ClassUsage::paymentPaid($payment);
    }

    public static function markStripePaymentRecordAsPaid($stripeInvoice)
    {
        $payment = MonthlyPayments::where('stripe_invoice_id', $stripeInvoice->id)->first();
        if($payment)
        {
            $payment->status = 'paid';
            $payment->payment_recieved_at =  \Carbon\Carbon::createFromTimestampUTC($stripeInvoice->status_transitions->paid_at)->format('Y-m-d H:i:s');
            $payment->save();
            ClassUsage::paymentPaid($payment);

            $automatedTagsHelper = new AutomatedTagsHelper($payment->student);
            $automatedTagsHelper->refreshOutsandingPaymentTag();
        }
    }
}