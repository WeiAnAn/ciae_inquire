<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransnationalDegree extends Model
{
    //
    protected $table ='transnational_degree';
    protected $fillable=['college','dept','nation',
    					'chtName','engName','bachelor',
    					'master','PHD','classMode',
    					'degreeMode','comments'];
    public $timestamps=false;
}
