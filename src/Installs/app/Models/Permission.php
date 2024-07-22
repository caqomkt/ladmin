<?php


namespace App;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends SpatiePermission
{
    use SoftDeletes;
    
    protected $table = 'permissions';
    
    protected $hidden = [
        
    ];

    protected $guarded = [];

    protected $dates = ['deleted_at'];
}
