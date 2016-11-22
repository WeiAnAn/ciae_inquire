<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StuFromPartnerSchool extends Model
{
    //
    protected $table = 'stu_from_partner_school';
    
    protected $fillable = [
        'college', 'dept', 'name', 'stuLevel', 'nation',
        'startDate', 'endDate', 'comments'
    ];


    public $timestamps = false;
}
