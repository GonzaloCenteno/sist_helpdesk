<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item extends Model
{
    public $timestamps = false;
    protected $table = 'cromohelp.tbl_item';
    protected $primaryKey='item_id';
}
