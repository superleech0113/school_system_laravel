<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Settings;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $permissions['create'] = $user->can('discount-create');
        $permissions['edit'] = $user->can('discount-edit');
        $durationEnum = Discount::durationEnum();
        $use_stripe_subscription = Settings::get_value('use_stripe_subscription');
        return view('discount.index', compact('permissions', 'durationEnum', 'use_stripe_subscription'));
    }

    public function records()
    {
        return Discount::get();
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
            'amount' => 'required|numeric',
            'duration' => 'required',
            'is_active' => 'required',
        ]);

        $discount = $request->id ? Discount::findOrFail($request->id) : new Discount();
        $discount->name = $request->name;
        $discount->is_active = $request->is_active;
        if (!$discount->id){
            $discount->amount = $request->amount;
            $discount->duration = $request->duration;
        }
        $discount->syncWithStripe($request->send_to_stripe);
        $discount->save();

        $discount = $discount->fresh();
        $out['status'] = 1;
        $out['message'] = $request->id ? __('messages.discount-updated-successfully') : __('messages.discount-created-successfully');
        $out['discount'] = $discount;
        return $out;
    }
}
