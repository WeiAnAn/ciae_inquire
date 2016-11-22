<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerSchool extends Model
{
    //
    protected $table ='partner_school';
    protected $fillable=['college','dept','nation',
    					'chtName','enName','startDate',
    					'endDate','comments'];
    public $timestamps=false;
}
