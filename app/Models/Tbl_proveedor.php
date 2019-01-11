<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_proveedor extends Model
{
    public $timestamps = false;
    protected $table = 'cromohelp.tbl_proveedor';
    protected $primaryKey='pro_id';
}
