<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\Pivot;
class group_user extends Pivot
{
    protected $with='roles';
    protected $guard_name = 'api';
    use HasRoles;
    
}
