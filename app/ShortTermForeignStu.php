<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortTermForeignStu extends Model
{
    //
    protected $table= 'short_term_foreign_stu';

    protected $fillable=['college','dept','name',
    					'stuLevel','nation','startDate',
    					'endDate','comments'];
	public $timestamps=false;  
}
