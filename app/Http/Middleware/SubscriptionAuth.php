<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\CompanySubscriptionRelation;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Lang;

class SubscriptionAuth
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


        if( (CompanySubscriptionRelation::hasValidSubscription($request['company_id']) AND !empty($request['company_id']))
        ) {
            /** Not expired */
//            \Log::debug("Valid Subscription",['user-token' => $request->header('user-token') ]);
        }else {
            /** expired */
            if(in_array($obj->call_mode,['admin', 'web'])){
                return redirect('/admin/re_subscription')->with('message','Your subscription has expired. Please Resubscribe.');
            }else{
                $code = 400;
                $response = [
                    'code' => $code,
                    'message' => __("app.subscription.expired"),
                    'data' => [],
                ];
                return response()->json($response, $code);
            }
        }
        return $next($request);
    }
}
