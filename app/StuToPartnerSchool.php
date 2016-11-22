<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StuToPartnerSchool extends Model
{
    //
    protected $table = 'stu_to_partner_school';
    
    protected $fillable = [
        'college', 'dept', 'name', 'stuLevel', 'nation',
        'startDate', 'endDate', 'comments'
    ];


    public $timestamps = false;
}
