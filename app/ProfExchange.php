<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfExchange extends Model
{
    //
    protected $table ='prof_exchange';
    protected $fillable=['college','dept','name',
    					'proLevel','nation',
    					'startDate','endDate','comments'];
    public $timestamps=false;
}
