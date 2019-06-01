<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Ticket_Asignar_Controller extends BaseSoapController
{
    private $service;
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_asignar_ticket'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('tickets/vw_ticket_asignar',compact('menu','permiso'));
            
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
           
        }
        else
        {
            if ($request['grid'] == 'asignar_tickets') 
            {
                return $this->crear_tabla_asignar_tickets($request);
            }
            if ($request['datos'] == 'traer_personal')
            {
                return $this->traer_datos_personal($request);
            }
        }
    }

    public function create(Request $request)
    {
    
    }

    public function edit($id_ticket,Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USER',session('id_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $rolxml = $xml->createElement('NIVEL',session('sro_id'));
        $rolxml =$root->appendChild($rolxml);
        
        $idticketxml = $xml->createElement('IDTICKET',$id_ticket);
        $idticketxml =$root->appendChild($idticketxml);
        
        $idtecnicoxml = $xml->createElement('IDUSUARIO',$request['id_tecnico']);
        $idtecnicoxml =$root->appendChild($idtecnicoxml);

        $xml->formatOutput = true;

        $codigo = '014';
        $trama = $xml->saveXML();
        //dd($trama);

        $parametros = [
            "cod" =>$codigo,
            "trama" => $trama
        ];

        $respuesta = $this->service->consulta($parametros);

        $array2 = (array) $respuesta;
        foreach ($array2 as &$valor2) 
        {
            $xmlr2 = $valor2 ;
        }
        $final2=strlen($xmlr2)-2;
        $xmlr2=substr($xmlr2, 1, $final2);
        $xmlr2=$xmlr2;
        $datos = (array) @simplexml_load_string($xmlr2);
        //dd($datos);
        
        return response()->json([
            'respuesta' => $datos['CODERR'],
            'mensaje' => $datos['MSGERR'],
        ]);
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        
    }
    
    public function crear_tabla_asignar_tickets(Request $request)
    {
        header('Content-type: application/json');
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $start = ($limit * $page) - $limit;  
        if ($start < 0) {
            $start = 0;
        }
        
            self::setWsdl();
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuariox = $xml->createElement('USU',session('id_usuario'));
            $usuariox =$root->appendChild($usuariox);  
            
            $rolx = $xml->createElement('NIVEL',session('sro_id'));
            $rolx =$root->appendChild($rolx);

            $orderby1 = $xml->createElement('ORDERBY1',$sidx); 
            $orderby1 =$root->appendChild($orderby1);  

            $orderby2=$xml->createElement('ORDERBY2',$sord); 
            $orderby2 =$root->appendChild($orderby2); 

            $limitxml=$xml->createElement('LIMIT',$limit); 
            $limitxml =$root->appendChild($limitxml); 

            $offsetxml=$xml->createElement('OFFSET',$start); 
            $offsetxml =$root->appendChild($offsetxml); 

            $xml->formatOutput = true;

            $codigo = '008';
            $trama = $xml->saveXML();
            //dd($trama);

            $parametros = [
                "cod" =>$codigo,
                "trama" => $trama
            ];

            $respuesta = $this->service->consulta($parametros);

            $array2 = (array) $respuesta;
            foreach ($array2 as &$valor2) 
            {
                $xmlr2 = $valor2 ;
            }
            $final2=strlen($xmlr2)-2;
            $xmlr2=substr($xmlr2, 1, $final2);
            $xmlr2=$xmlr2;
            $datos = (array) @simplexml_load_string($xmlr2);
            //dd($datos);
            //dd($array['TICKETS'][0]->IDTIC);
            //$totalg = $datos->NUMTIC[0];
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_asignar_ticket'],['btn_view',1]])->get();
            
        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $datos['NUMTIC'];
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        
        if ($datos['NUMTIC'] == 1) 
        {
            $Lista->rows[0]['id'] = (integer)$datos['TICKETS']->IDTIC;
            if ($permiso[0]->btn_edit == 1) 
            {
                $asignar = '<button class="btn btn-success btn-lg" data-toggle="modal" data-target="#Modal_Asignar_Ticket" data-backdrop="static" data-keyboard="false" type="button" onclick="asignar_ticket('.trim((integer)$datos['TICKETS']->IDTIC).')"><i class="fa fa-check-square-o"></i> ASIGNAR TICKET</button>';
            }
            else
            {
                $asignar = '<button class="btn btn-success btn-lg" type="button" onclick="sin_permiso();"><i class="fa fa-check-square-o"></i> ASIGNAR TICKET</button>';
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['TICKETS']->IDTIC),
                    trim($datos['TICKETS']->TICASU),
                    trim($datos['TICKETS']->TICTDE),
                    trim($datos['TICKETS']->TICADE),
                    trim($datos['TICKETS']->TICCPR),
                    trim($datos['TICKETS']->TICDES),
                    trim(date("d/m/Y", strtotime($datos['TICKETS']->TICFEC))),
                    $asignar
            );  
            return response()->json($Lista);
        }
        else if ($datos['NUMTIC'] == 0) 
        {
            return response()->json([
                'page' => 0,
                'records' => 0,
                'total' => 0,
            ]);
        }
        else
        {
            foreach ($datos['TICKETS'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDTIC;
                if ($permiso[0]->btn_edit == 1) 
                {
                    $asignar = '<button class="btn btn-success btn-lg" data-toggle="modal" data-target="#Modal_Asignar_Ticket" data-backdrop="static" data-keyboard="false" type="button" onclick="asignar_ticket('.trim((integer)$Datos->IDTIC).')"><i class="fa fa-check-square-o"></i> ASIGNAR TICKET</button>';
                }
                else
                {
                    $asignar = '<button class="btn btn-success btn-lg" type="button" onclick="sin_permiso();"><i class="fa fa-check-square-o"></i> ASIGNAR TICKET</button>';
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDTIC),
                    trim($Datos->TICASU),
                    trim($Datos->TICTDE),
                    trim($Datos->TICADE),
                    trim($Datos->TICCPR),
                    trim($Datos->TICDES),
                    trim(date("d/m/Y", strtotime($Datos->TICFEC))),
                    $asignar
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function traer_datos_personal(Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USER',session('id_usuario'));
        $usuariox =$root->appendChild($usuariox);  

        $rolx = $xml->createElement('NIVEL',session('sro_id'));
        $rolx =$root->appendChild($rolx);

        $xml->formatOutput = true;

        $codigo = '009';
        $trama = $xml->saveXML();
        //dd($trama);

        $parametros = [
            "cod" =>$codigo,
            "trama" => $trama
        ];

        $respuesta = $this->service->consulta($parametros);

        $array2 = (array) $respuesta;
        foreach ($array2 as &$valor2) 
        {
            $xmlr2 = $valor2 ;
        }
        $final2=strlen($xmlr2)-2;
        $xmlr2=substr($xmlr2, 1, $final2);
        $xmlr2=$xmlr2;
        $datos = (array) @simplexml_load_string($xmlr2);
        //dd($datos['CODERR']);
        //dd($datos['TECNICO']);
        
        return response()->json([
            'respuesta' => $datos['CODERR'],
            'nro_tecnicos' => $datos['NUMTEC'],
            'datos' => $datos['TECNICO'],
        ]);
    }

}
