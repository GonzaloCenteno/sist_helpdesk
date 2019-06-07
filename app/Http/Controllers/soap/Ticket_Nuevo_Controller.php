<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Pusher\Pusher;

class Ticket_Nuevo_Controller extends BaseSoapController
{
    private $service;
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_nuevo_ticket'],['btn_view',1]])->get();
                if ($permiso->count() == 0) 
                {
                    return view('errors/vw_sin_permiso',compact('menu'));
                }
            $datos =& $this->traer_datos();
            if($datos['CODERR']=='00000')
            {
                $numtip = $datos['NUMTIP'];
                $numare = $datos['NUMARE'];
                $numpri = $datos['NUMPRI'];
                return view('tickets/vw_ticket_nuevo',compact('menu','permiso','numtip','numare','numpri','datos'));
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }

    public function show($id, Request $request)
    {
        if ($id > 0) 
        {
            if ($request['datos'] == 'traer_subareas') 
            {
                return $this->traer_subareas($id, $request);
            }
        }
    }

    public function create(Request $request)
    {
    
    }

    public function edit($id,Request $request)
    {
              
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cbxtipo' => 'required|not_in:0',
            'cbxarea' => 'required|not_in:0',
            'cbxsubarea' => 'required|not_in:0',
            'cbxpri' => 'required|not_in:0',
            'txfecha' => 'required|string',
            'intitulo' => 'required|string',
            'descripcion' => 'required',
        ],[
            'cbxtipo.required' => 'EL CAMPO TIPO ES OBLIGATORIO',
            'cbxarea.required' => 'EL CAMPO AREA ES OBLIGATORIO',
            'cbxsubarea.required' => 'EL CAMPO SUB-AREA ES OBLIGATORIO',
            'cbxpri.required' => 'EL CAMPO PRIORIDAD ES OBLIGATORIO',
            'txfecha.required' => 'EL CAMPO FECHA ES OBLIGATORIO',
            'intitulo.required' => 'EL CAMPO TITULO ES OBLIGATORIO',
            'descripcion.required' => 'EL CAMPO DESCRIPCION ES OBLIGATORIO',
            'cbxtipo.not_in' => 'DEBES SELECCIONAR UN CAMPO VALIDO',
            'cbxarea.not_in' => 'DEBES SELECCIONAR UNA AREA VALIDA',
            'cbxsubarea.not_in' => 'DEBES SELECCIONAR UNA SUB-AREA VALIDA',
            'cbxpri.not_in' => 'DEBES SELECCIONAR UNA PRIORIDAD VALIDA',
        ]);
        
        if ($validator->passes()) 
        {
            self::setWsdl();
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuariox = $xml->createElement('USU',session('id_usuario'));
            $usuariox =$root->appendChild($usuariox);  

            $tix=$xml->createElement('TIP',$request['cbxtipo']); 
            $tix =$root->appendChild($tix); 

            $arx=$xml->createElement('ARE',$request['cbxarea']); 
            $arx =$root->appendChild($arx);
            
            $subarx=$xml->createElement('SUBARE',$request['cbxsubarea']); 
            $subarx =$root->appendChild($subarx);

            $prx=$xml->createElement('PRI',$request['cbxpri']); 
            $prx =$root->appendChild($prx);

            $fex=$xml->createElement('FEC',$request['txfecha']); 
            $fex =$root->appendChild($fex);

            $ttx=$xml->createElement('TIT', strtoupper($request['intitulo'])); 
            $ttx =$root->appendChild($ttx);
            
            $var = htmlspecialchars($request['descripcion']);
            $dex=$xml->createElement('DES',$var); 
            $dex =$root->appendChild($dex);

            if($request->hasFile('file'))
            {
                $nombre = $request->file->getClientOriginalName();
                $ruta = $request->file->storeAs('public/Archivos',date('Y-m-d'). '_' .$nombre);
                $acx=$xml->createElement('ARC',$ruta); 
                $acx =$root->appendChild($acx);
            } 
            else
            {
                $acx=$xml->createElement('ARC','-'); 
                $acx =$root->appendChild($acx);
            }
            
            $punto_ventax=$xml->createElement('PVT',session('id_pvt')); 
            $punto_ventax =$root->appendChild($punto_ventax);

            $xml->formatOutput = true;

            $codigo = '004';
            $trama = $xml->saveXML();

            //dd($trama);

            $parametros = [
                "cod" =>$codigo,
                "trama" => $trama
            ];

            $respuesta = $this->service->consulta($parametros);

            //dd($respuesta);

            $array2 = (array) $respuesta;
            foreach ($array2 as &$valor2) 
            {
                $xmlr2 = $valor2 ;
            }
            $final2=strlen($xmlr2)-2;
            $xmlr2=substr($xmlr2, 1, $final2);
            $xmlr2=$xmlr2;
            $datos = simplexml_load_string($xmlr2);
            //dd($datos);
            
            $respuesta_datos = $datos->TITU[0].", ".$datos->ESTA[0]." - ".$datos->FECHA[0];
            
            $options = array(
                'cluster' => 'us2', 
                'encrypted' => true
            );

            $pusher = new Pusher(
                'd8966da1d9f626630fe1',
                '82beae62b2d0106fd43d',
                '686357',
                $options
            );
            
            $this->llamar_notificacion($respuesta_datos);
            $pusher->trigger('notify_user', 'notify-event_user', $respuesta_datos);
            
            
            return response()->json([
                'respuesta' => $datos->CODERR[0],
                'texto' => $respuesta_datos,
                'mensaje' => $datos->MSGERR[0],
            ]);
        }
        else
        {
            return response()->json([
            'msg' => 'validator',
            'respuesta'=>$validator->errors()->all()
            ]);
        }
    }
    
    public function llamar_notificacion($respuesta_datos)
    {
        $content      = array(
            "en" => 'NUEVO INCIDENTE REGISTRADO'
        );
        $headings     = array(
            "en" => 'MENSAJE CROMOAYUDA'
        );
        $hashes_array = array();
        array_push($hashes_array, array(
            "id" => "like-button",
            "text" => $respuesta_datos,
            "icon" => "http://i.imgur.com/N8SN8ZS.png",
            "url" => \URL::to('ticketasignar')
        ));
        $fields = array(
            'app_id' => "e15317a4-06ae-422c-919d-eade16bf4608",
            'included_segments' => array(
                'All'
            ),
            'contents' => $content,
            'headings' => $headings,
            'web_buttons' => $hashes_array,
            'url' => \URL::to('/'),
            'chrome_web_image' => url('img/bus-home.png')
        );

        $fields = json_encode($fields);
   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ZDhjOTRkNzMtMjQ5OS00MDViLTk2NTMtZDMwOTU4YzEwY2Qx'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
    }
    
    public function &traer_datos()
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USER',session('id_usuario')); 
        $usuariox =$root->appendChild($usuariox);  

        $ipx=$xml->createElement('NIVEL',session('sro_id')); 
        $ipx =$root->appendChild($ipx); 

        $xml->formatOutput = true;

        $c2='003';
        $t = $xml->saveXML();   

        $params = [
            "cod" =>$c2,
            "trama" => $t
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
        $datos = (array) @simplexml_load_string($xmlr2);
        
        return $datos;
    }
    
    public function traer_subareas($id_area, Request $request)
    {
        $datos = DB::table('cromohelp.tbl_subarea')->where('suba_area',$id_area)->orderBy('suba_desc','asc')->get();
        return $datos;
    }
    
}
