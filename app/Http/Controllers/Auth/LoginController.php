<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $this->validate(request(),[
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);
        
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
                    $departamento = isset($entradas[$i]["department"][0]) ? $entradas[$i]["department"][0] : "0;0";
                    $user_cn = isset($entradas[$i]["cn"][0]) ? $entradas[$i]["cn"][0] : "-";
                    $nomb_usu = isset($entradas[$i]["displayname"][0]) ? $entradas[$i]["displayname"][0] : "-";
                }           
                $array_dep = explode(";", $departamento);
                $sist_id = DB::table('permisos.tblsistemas_sis')->where('sist_desc', 'like', '%CromoHelp%')->get();
                $indice = array_search($sist_id[0]->sist_id,$array_dep);
                if($indice === false)
                {
                    session()->flash('msg', 'NO TIENE ACCESO PARA ESTE SISTEMA');
                    return redirect()->back();
                } 
                else
                {
                    $validar = DB::table('cromohelp.tbl_login')->where('log_idusu',$user_cn)->get();
                    if ($validar->count() > 0) 
                    {
                        $punto_venta = DB::table('cromohelp.tbl_pvt')->where('pvt_id',$validar[0]->log_idpvt)->first();
                        session(['id_usuario'=>$user_cn]);
                        session(['nomb_usuario'=>$nomb_usu]);
                        //session(['nombre_usuario'=>$user_cn]);
                        session(['id_pvt'=>$validar[0]->log_idpvt]);
                        session(['desc_pvt'=>$punto_venta->pvt_desc]);
                        session(['sist_id'=>$sist_id[0]->sist_id]);
                        return redirect('dashboard');
                    }
                    else
                    {
                        session(['id'=>$user_cn]);
                        return redirect('registro');
                    }
                }
            }
            else
            {
                session()->flash('msg', 'EL USUARIO NO TIENE PERMISOS');
                return redirect()->back();
            }
        }
        else
        {
            return back()
                ->withErrors(['password' => trans('auth.failed')])
                ->withInput(request(['usuario'])); 
        }  
    }
    
    public function logout()
    {
        \Session::flush();
        return redirect('/');
    }
}