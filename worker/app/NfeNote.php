<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NfeNote extends Model
{
    protected $fillable =['id', 'value'];
    protected $table = 'nfe_notes';
    public $incrementing = false;
}
