<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tbl_login;

class RegistroController extends Controller
{
    public function index(Request $request)
    {
        if ($request->session()->has('id')) 
        {
            $pvt = DB::table('cromohelp.tbl_pvt')->where('pvt_est',1)->orderBy('pvt_desc','asc')->get();
            return view('registro/vw_registro',compact('pvt'));
        }
        else
        {
            return view('errors/vw_sin_acceso',compact('tblmenu_men'));
        }
    }

    public function show($id, Request $request)
    {
        
    }

    public function create(Request $request)
    {
        $ldap_con = ldap_connect("cromotex.com.pe",389)or die ("NO SE PUDO CONECTAR CON EL SERVIDOR");
        $ldap_dn = "DC=cromotex,DC=com,DC=pe";
        $usuario = $request['usuario'];
        $password = $request['password'];
        ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0);
        
        if (@ldap_bind($ldap_con, $usuario."@cromotex.com.pe", $password)) 
        {
            $filtro = "(cn=$usuario)";
            $busqueda = @ldap_search($ldap_con,$ldap_dn, $filtro) or exit("NO SE PUDO CONECTAR");
            $entradas = @ldap_get_entries($ldap_con, $busqueda);
            if ($entradas["count"] > 0) 
            {
                for ($i=0; $i<$entradas["count"]; $i++) 
                {
                    $user_cn = isset($entradas[$i]["cn"][0]) ? $entradas[$i]["cn"][0] : "-";
                }
                if ($user_cn === 'gcenteno') 
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
                    return 'incorrecto';
                }
            }
            else
            {
                return 'error';
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