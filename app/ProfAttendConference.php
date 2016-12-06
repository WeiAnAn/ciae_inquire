<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfAttendConference extends Model
{
    //
    protected $table ='prof_attend_conference';
    protected $fillable=['college','dept','name',
    					'profLevel','nation','confName',
    					'startDate','endDate','comments'];
    public $timestamps=false;
}
?>