<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $guarded = [];

    public function permission_role(){
        return $this->hasOne(PermissionRole::class, 'permission_id');
    }
}
