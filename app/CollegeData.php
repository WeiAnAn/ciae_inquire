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

	public static function toChtName($college, $dept){
        return CollegeData::where('college',$college)
                          ->where('dept',$dept)->first();
    }
    
    public $timestamps = false;
}
