<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfForeignResearch extends Model
{
    //
    protected $table ='prof_foreign_research';
    protected $fillable=['college','dept','name',
    					'profLevel','nation',
    					'startDate','endDate','comments'];
    public $timestamps=false;
}
