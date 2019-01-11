<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_marcas extends Model
{
    public $timestamps = false;
    protected $table = 'cromohelp.tbl_marcas';
    protected $primaryKey='mar_id';
}
