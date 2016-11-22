<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StuForeignResearch extends Model
{
    //
    protected $table = 'stu_foreign_research';
    
    protected $fillable = [
        'college', 'dept', 'name', 'stuLevel', 'nation',
        'startDate', 'endDate', 'comments'
    ];


    public $timestamps = false;
}
