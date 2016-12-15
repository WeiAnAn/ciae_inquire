<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternationalJournalEditor extends Model
{
    protected $table ='international_journal_editor';
    protected $fillable=['college','dept','name',
    					'journalName',
    					'startDate','endDate','comments'];
    public $timestamps=false;
}
