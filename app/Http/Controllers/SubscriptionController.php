<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $permissions['create'] = false;
        $permissions['edit'] = false;
        return view('subscription.index', [
            'discounts' => Discount::get(),
            'plans' => Plan::get(),
            'subscriptions' => $user->stripeSubscriptions()->with('stripeSubscriptionPlanItems.plan','discount')->get(),
            'user_id' => $user->id,
            'permissions' => $permissions
        ]);
    }
}
