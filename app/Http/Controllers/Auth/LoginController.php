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
        
        $datos = DB::table('tblldap_ldap')->where('nombre_usuario',$request['usuario'])->get();
        if ($datos->count() > 0) 
        {
            $ldap_con = ldap_connect("cromotex.com.pe",389) or die ("NO SE PUDO CONECTAR CON EL SERVIDOR");

            ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
            
            if (@ldap_bind($ldap_con, $datos[0]->dn, $request['password'])) 
            {
                $validar = DB::table('cromohelp.tbl_login')->where('log_idusu',$datos[0]->id_usuario)->get();
                if ($validar->count() > 0) 
                {
                    $rol = DB::table('tblusuarios_usu')->where('ldap_id',$datos[0]->id_usuario)->where('sist_id',1)->first();
                    $punto_venta = DB::table('cromohelp.tbl_pvt')->where('pvt_id',$validar[0]->log_idpvt)->first();
                    session(['id_usuario'=>$datos[0]->id_usuario]);
                    session(['usutec'=>$rol->usu_id]);
                    session(['nombre_usuario'=>$datos[0]->nombre_usuario]);
                    session(['rol'=>$rol->rol_id]);
                    session(['id_pvt'=>$validar[0]->log_idpvt]);
                    session(['desc_pvt'=>$punto_venta->pvt_desc]);
                    return redirect('dashboard');
                }
                else
                {
                    session(['id'=>$datos[0]->id_usuario]);
                    return redirect('registro');
                }   
            }
            else
            {
                return back()
                ->withErrors(['password' => trans('auth.failed')])
                ->withInput(request(['usuario'])); 
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
