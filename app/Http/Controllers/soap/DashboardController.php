<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseSoapController
{
    private $service;
    public function index()
    {
        $tblusuarios_usu = DB::table('tblusuarios_usu')->where('ldap_id',session('id_usuario'))->where('sist_id',1)->first();
        if ($tblusuarios_usu) 
        {
            return $this->recuperar_datos($tblusuarios_usu->sist_id,$tblusuarios_usu->rol_id);
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
    
    }

    public function edit($id_usuario,Request $request)
    {
              
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {

    }
    
    public function recuperar_datos($sist_id,$rol_id)
    {
        $tblmenu_men = DB::table('tblmenu_men')->where([['menu_sist',$sist_id],['menu_rol',$rol_id],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
        $tblmenu_men2 = DB::table('tblmenu_men')->where([['menu_sist',$sist_id],['menu_rol',$rol_id],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USER',session('nombre_usuario')); 
        $usuariox =$root->appendChild($usuariox);  

        $ipx=$xml->createElement('NIVEL',$rol_id); 
        $ipx =$root->appendChild($ipx); 

        $xml->formatOutput = true;

        $codigo = '002';
        $trama = $xml->saveXML();   

        $params = [
            "cod" =>$codigo,
            "trama" => $trama
        ];
        $response = $this->service->consulta($params);

        $array2 = (array) $response;
        foreach ($array2 as &$valor2) 
        {
            $xmlr2 = $valor2 ;
        }
        $final2=strlen($xmlr2)-2;
        $xmlr2=substr($xmlr2, 1, $final2);
        $xmlr2=$xmlr2;
        $datos = simplexml_load_string($xmlr2);
        
        //dd($datos);

        if($datos->CODERR=='00000')
        {
            $total = $datos->TOTICK;
            $aperturados = $datos->TOTRES;
            $proceso = $datos->TOTPRO;
            $finalizado = $datos->TOTNOR;
        }
        return view('dashboard/vw_dashboard',compact('tblmenu_men','tblmenu_men2','total','aperturados','proceso','finalizado'));
    }
}
