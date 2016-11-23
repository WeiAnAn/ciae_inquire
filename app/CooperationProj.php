<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CooperationProj extends Model
{
    //
    protected $table ='cooperation_proj';
    protected $fillable=['college','dept','name',
    					'projName','projOrg','projContent',
    					'startDate','endDate','comments'
    					];
    public $timestamps=false;
}
