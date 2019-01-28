<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_preguntas extends Model
{
    public $timestamps = false;
    protected $table = 'cromohelp.tbl_preguntas';
    protected $primaryKey='pre_id';
}
