<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'tblldap_ldap';
    
    protected $fillable = [
        'nombre_usuario', 'dn',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'nombre_usuario', 'dn',
    ];
    
    public function getAuthPassword()
    {
        return $this->nombre_usuario;
    }
}
