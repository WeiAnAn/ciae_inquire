<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerSchool extends Model
{
    //
    protected $table ='partner_school';
    protected $fillable=['college','dept','nation',
    					'chtName','engName','startDate',
    					'endDate','comments'];
    public $timestamps=false;
}
