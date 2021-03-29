<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $table = 'login_history';

    public static function create($user_id, $ip, $browser)
    {
        $loginHistory = new LoginHistory();
        $loginHistory->IP = $ip;
        $loginHistory->user_id = $user_id;
        $loginHistory->browser = $browser;
        $loginHistory->logged_in = date('Y-m-d H:i:s');
        $loginHistory->created_at = date('Y-m-d H:i:s');
        $loginHistory->save();
    }

    public static function logout($user_id)
    {
        $loginHistory = LoginHistory::where('user_id', $user_id)->whereNull('logged_out')->latest()->first();
        if ($loginHistory) {
            $loginHistory->logged_out = date('Y-m-d H:i:s');
            $loginHistory->updated_at = date('Y-m-d H:i:s');
            $loginHistory->save();
        }
    }

}
