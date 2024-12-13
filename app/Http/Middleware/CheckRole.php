<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Retrieve the user
        $user = User::where('id', session('user')->id)->first();
        // $userInspectors = User::inspectorDatatable($params);
        
        
        // Get the user's user group ID
        $userGroupId = $user->user_group_id;
        // Check if the user is an admin
        if ($userGroupId == 1) {
            $roleName = 'admin';
        } 
        // Check if the user is an agent
        elseif ($userGroupId == 2) {
            $userInsector = User::leftJoin('company_group AS cg', 'cg.id', '=', 'user.company_group_id')
            ->where('user.id', session('user')->id)
            ->where('cg.id', $user->company_group_id)
            ->first();
            
            $userType = $userInsector->title;

            \Log::info('userInsector' . $userType);
            // \Log::info('userInsector' . $userInsector);
            // $userType = $user->user_type;
            
            // Check if the user is a manager
            if ($userType == 'manager' || $userType == 'Manager') {
                $roleName = 'manager';
            } else {
                $roleName = 'standard';
            }
        } else {
            $code = 403;
            $response = [
                'code' => $code,
                'message' => __("You do not have permission to access this"),
                'data' => [],
            ];
            return response()->json($response, $code);
        }
        // Get the role name of the user
        // $userRole = $user->role()->first();
        // $roleName = strtolower($userRole->name);
      
        // Check if the user's role is in the provided roles array
        if (!in_array($roleName, $roles)) {
            $code = 403;
            $response = [
                'code' => $code,
                'message' => __("You do not have permission to access this"),
                'data' => $request->route()->getName(),
            ];
            return redirect()->route('admin.forbidden')->with('response', $response);
            // return response()->json($response, $code);
        }

        return $next($request);
        
    }
}
