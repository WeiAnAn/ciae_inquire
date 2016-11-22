<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GraduateThreshold extends Model
{
    //
    protected $table ='graduate_threshold';
    protected $fillable=['college','dept','testName',
    					'testGrade',
    					'comments'];
    public $timestamps=false;
}
