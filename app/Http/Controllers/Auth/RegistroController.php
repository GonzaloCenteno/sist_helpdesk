<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tbl_login;

class RegistroController extends Controller
{
    public function index()
    {
        $pvt = DB::table('cromohelp.tbl_pvt')->where('pvt_est',1)->orderBy('pvt_desc','asc')->get();
        return view('registro/vw_registro',compact('pvt'));
    }

    public function show($id, Request $request)
    {
        
    }

    public function create(Request $request)
    {
        $datos = DB::table('tblldap_ldap')->where('nombre_usuario',$request['usuario'])->get();
        if ($datos->count() > 0) 
        {
            $admin = DB::table('tblusuarios_usu')->where('ldap_id',$datos[0]->id_usuario)->where('sist_id',1)->where('rol_id',1)->get();
            if ($admin->count() > 0) 
            {
                $ldap_con = ldap_connect("cromotex.com.pe",389) or die ("NO SE PUDO CONECTAR CON EL SERVIDOR");

                ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);

                if (@ldap_bind($ldap_con, $datos[0]->dn, $request['password'])) 
                {
                    $Tbl_login = new Tbl_login;
                    $Tbl_login->log_idusu   = session('id');
                    $Tbl_login->log_idpvt   = $request['punto_venta'];
                    $Tbl_login->lof_feccre  = date('Y-m-d');

                    $Tbl_login->save();
                    \Session::flush();
                    return $Tbl_login->id;
                }
                else
                {
                    return 'error';
                }
            }
            else
            {
                return 'incorrecto';
            }
        }
        else
        {
            return 0;
        }
    }

    public function edit($id_ticket,Request $request)
    {
        
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        
    }

}