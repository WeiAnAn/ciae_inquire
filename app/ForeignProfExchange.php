<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForeignProfExchange extends Model
{
    protected $table ='foreign_prof_exchange';
    protected $fillable=['college','dept','name',
    					'profLevel','nation',
    					'startDate','endDate','comments'];
    public $timestamps=false;
}
