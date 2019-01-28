<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_valores extends Model
{
    public $timestamps = false;
    protected $table = 'cromohelp.tbl_valores';
    protected $primaryKey='val_id';
}
