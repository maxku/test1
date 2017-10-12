<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = ['id', 'description', 'value', 'value_usd', 'date'];
    protected $table = 'records';
    public $timestamps = false;
}
