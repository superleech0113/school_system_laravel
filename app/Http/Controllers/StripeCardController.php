<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;

class StripeCardController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $permissions['create'] = $user->can('card-create');
        $permissions['delete'] = $user->can('card-delete');
        $stripe_publishable_key = Settings::get_value('stripe_publishable_key');
        return view('card.index',compact('stripe_publishable_key', 'permissions'));
    }

    public function records()
    {
        $res = \Auth::user()->getStripeCards();
        if($res['status'] == 0) {
            abort(400, $res['message']);
        }

        $res1 = \Auth::user()->getStripeCustomer();
        if($res1['status'] == 0) {
            abort(400, $res['message']);
        }

        return [
            'cards' => $res['cards'],
            'default_source' => $res1['customer']['default_source']
        ];
    }

    public function addCard(Request $request)
    {
        $res = \Auth::user()->addStripeCard($request->token);
        if($res['status'] == 0) {
            abort(400, $res['message']);
        }
        return [
            'status' => 1,
            'message' => __('messages.card-added-successfully')
        ];
    }

    public function deleteCard($card_id)
    {
        $res = \Auth::user()->deleteStripeCard($card_id);
        if($res['status'] == 0) {
            abort(400, $res['message']);
        }
        return [
            'status' => 1,
            'message' => __('messages.card-deleted-successfully')
        ];
    }

    public function setAsDefault($card_id)
    {
        $res = \Auth::user()->setStripeDefaultCard($card_id);
        if($res['status'] == 0) {
            abort(400, $res['message']);
        }
        return [
            'status' => 1,
            'message' => __('messages.default-card-updated-successfully'),
        ];
    }
}
