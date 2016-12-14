<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendInternationalOrganization extends Model
{
    protected $table ='attend_international_organization';
    protected $fillable=['college','dept','name',
    					'organization','startDate',
    					'endDate','comments'];
    public $timestamps=false;
}
