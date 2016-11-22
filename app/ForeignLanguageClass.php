<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForeignLanguageClass extends Model
{
    //
    protected $table = 'foreign_language_class';

    protected $fillable = ['college','dept','year','semester','chtName','engName',
    						'teacher','language','totalCount','nationalCount','comments'];

   	public $timestamps = false;

}
