<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternationalizeActivity extends Model
{
    //
    protected $table ='internationalize_activity';
    protected $fillable=['college','dept','activityName',
    					'place','host','guest',
    					'startDate','endDate'];
    public $timestamps=false;
}
