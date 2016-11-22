<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollegeData extends Model
{
    //
    protected $table = 'college_data';

    protected $fillable = [
        'college', 'dept', 'chtName',
    ];

   	protected $primaryKey = 'college';

   	public static function toChtName($college, $dept){
   		return CollegeData::where('college',$college)
				          ->where('dept',$dept)->first();
   	}

    public $timestamps = false;
}
