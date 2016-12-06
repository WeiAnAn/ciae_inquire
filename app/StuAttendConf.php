<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StuAttendConf extends Model
{
    //
    protected $table ='stu_attend_conf';
    protected $fillable=['college','dept','name',
    					'stuLevel','nation','confName',
    					'startDate','endDate','comments'];
    public $timestamps=false;

}
