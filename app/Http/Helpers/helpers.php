<?php

use App\GroupPermission;

/***************** User Roles Permissions ****************************/
function checkRolePermission($module_page)
{
    $_SESSION["group_id"]= Auth::user()->group_id;

    $group_id = $_SESSION["group_id"];
    return  GroupPermission::where(['group_id' => $group_id])->where('module_page',$module_page)->first();
    

}
/***************** User Roles Permissions ****************************/