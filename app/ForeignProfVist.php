<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForeignProfVist extends Model
{
    //
    protected $table ='foreign_prof_vist';
    protected $fillable=['college','dept','name',
    					'profLevel','nation',
    					'startDate','endDate','comments'];
    public $timestamps=false;
}
