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
                    $rol = isset($entradas[$i]["title"][0]) ? $entradas[$i]["title"][0] : "0;0";
                    $user_cn = isset($entradas[$i]["cn"][0]) ? $entradas[$i]["cn"][0] : "-";
                    $nomb_usu = isset($entradas[$i]["displayname"][0]) ? $entradas[$i]["displayname"][0] : "-";
                }           
                $array_dep = explode(";", $departamento);
                $array_rol = explode(";", $rol);
                //print_r($array_rol);
                $indice = array_search(1,$array_dep);
                //var_export($indice);
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
                        //$menu = DB::table('tblmenu_men')->select('menu_rut','menu_desc')->where([['menu_sist',$array_dep[$indice]],['menu_rol',$array_rol[$indice]],['menu_est',1],['menu_niv',1]])->get();
                        //dd($menu);
                        session(['id_usuario'=>$user_cn]);
                        session(['nomb_usuario'=>$nomb_usu]);
                        //session(['usutec'=>$array_rol[$indice]]);
                        session(['nombre_usuario'=>$user_cn]);
                        session(['rol'=>$array_rol[$indice]]);
                        session(['id_pvt'=>$validar[0]->log_idpvt]);
                        session(['desc_pvt'=>$punto_venta->pvt_desc]);
                        session(['menu_sist'=>$array_dep[$indice]]);
                        session(['menu_rol'=>$array_rol[$indice]]);
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
