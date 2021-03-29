<?php 

namespace App\Http\Middleware;

use App\Settings;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Students;
use Illuminate\Support\Facades\Session;

class SetLang {
	public function handle($request, Closure $next)
	{
		$tenant = tenant();
        if ($tenant)
        {
            if(Session::has('lang'))
			{
				$lang = Session::get('lang');
			}
			else if(Auth::check())
			{
				$lang = Auth::user()->get_lang();
			}
			else
			{
				$lang = Settings::get_value('default_lang');
			}

			app()->setLocale($lang);
        }
    	return $next($request);
  	}
}