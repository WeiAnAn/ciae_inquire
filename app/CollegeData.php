<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollegeData extends Model
{
    //
    protected $table = 'college_data';

    protected $fillable = [
        'college', 'dept', 'chtName',
    ];


    public $timestamps = false;
}
