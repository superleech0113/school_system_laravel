<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Settings;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $permissions['create'] = $user->can('plan-create');
        $permissions['edit'] = $user->can('plan-edit');
        $use_stripe_subscription = Settings::get_value('use_stripe_subscription');
        return view('plan.index', compact('permissions', 'use_stripe_subscription'));
    }

    public function records()
    {
        return Plan::get();
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
            'price_per_month' => 'required|numeric'
        ]);

        $plan = $request->id ? Plan::findOrFail($request->id) : new Plan();
        $plan->name = $request->name;
        $plan->is_active = $request->is_active;
        if (!$plan->id) {
            $plan->number_of_lessons = $request->number_of_lessons;
            $plan->price_per_month = $request->price_per_month;
        }
        $plan->syncWithStripe($request->send_to_stripe);
        $plan->save();

        $plan = $plan->fresh();
        $out['status'] = 1;
        $out['message'] = $request->id ? __('messages.plan-updated-successfully') : __('messages.plan-created-successfully');
        $out['plan'] = $plan;
        return $out;
    }
}
