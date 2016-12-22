<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForeignStu extends Model
{
    //
    protected $table ='foreign_stu';
    protected $fillable=['college','dept','chtName',
    					'engName','stuID','stuLevel',
    					'nation','engNation','startDate',
    					'endDate','comments','status'];
    public $timestamps=false;

}
