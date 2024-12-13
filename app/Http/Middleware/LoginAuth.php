<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Lang;

class LoginAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $obj = new Controller();
        $obj->call_mode; // call mode for admin ..
        if(in_array($obj->call_mode,['admin', 'web'])){
            if(isset($request['draw'])){
                $request['page'] = ($request['start'] / 10 ) + 1;
            }

            if (Cookie::get('remember_me')) {
                $rememberEmail = Cookie::get('remember_me');

                \Log::debug('Login via remember_me');

                $user = User::where('remember_token',$rememberEmail)->first();

                $request['user_id'] = $user->id;
                $request['company_id'] = $user->company_id;
                $request['call_mode'] = $obj->call_mode;

                return $next($request);
            } else if ($request->session()->exists('user')) {
                $user_session = $request->session()->get('user');
                \Log::debug('Login via session');
                //print_r($user_session);exit;
                $request['user_id'] = $user_session->id;
                $request['company_id'] = $user_session->company_id;
                $request['call_mode'] = $obj->call_mode;

                return $next($request);
            }
            return redirect('/admin/login');
        }

        if(!($result = User::auth($request->header('user-token')))){
            $code = 400;
            $response = [
                'code' => $code,
                'message' => Lang::get('passwords.user_token'),
                'data' => [],
            ];
            \Log::debug('Middleware LoginAuth Failed');
            return response()->json($response, $code);

        }else{
            // \Log::debug('Middleware LoginAuth Success');
        }

        $request['user'] = $result;
        $request['user_id'] = $result->id;
        $request['company_id'] = $result->company_id;
        $request['company_group_id'] = $result->company_group_id;
        $request['call_mode'] = 'api';
        return $next($request);
    }
}
