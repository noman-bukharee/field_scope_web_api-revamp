<?php
use App\Models\User;
    
    $session = \Session::all();
    $user = $session['user'];
    $userGroupId = $user->user_group_id;
    if ($userGroupId == 1) {
        $roleName = 'admin';
    } 
    elseif($userGroupId == 2){

        //Get Agent role Title
        $userInsector = User::leftJoin('company_group AS cg', 'cg.id', '=', 'user.company_group_id')
            ->where('user.id', session('user')->id)
            ->where('cg.id', $user->company_group_id)
            ->first();
        if($userInsector->role_id == 2){
            $roleName = 'manager';
        }
        else{
            $roleName = 'standard';
        }
    }
   
?>
