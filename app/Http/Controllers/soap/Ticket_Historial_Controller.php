<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use Session;
Use Redirect;

class Ticket_Historial_Controller extends BaseSoapController
{
    private $service;
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $tblmenu_men = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
            $tblmenu_men2 = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
            $tblmenu_men3 = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',3]])->orderBy('menu_id','asc')->get();
            return view('tickets/vw_ticket_historial',compact('tblmenu_men','tblmenu_men2','tblmenu_men3'));
        }
        else
        {
            return view('errors/vw_sin_acceso',compact('tblmenu_men'));
        }
    }

    public function show($id, Request $request)
    {
        if ($id > 0) 
        {
            if ($request['show'] == 'traer_ticket') 
            {
                return $this->recuperar_datos_ticket($id, $request);
            }
        }
        else
        {
            if ($request['grid'] == 'tickets') 
            {
                return $this->crear_tabla_tickets($request);
            }
            if ($request['grid'] == 'buscar_tickets') 
            {
                return $this->crear_tabla_buscar_tickets($request);
            }
            if ($request['show'] == 'traer_historial_encuesta') 
            {
                return $this->traer_datos_historial_encuesta($request);
            }
        }
    }

    public function create(Request $request)
    {
        
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
    
    public function crear_tabla_tickets(Request $request)
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

            $usuariox = $xml->createElement('USU',session('nombre_usuario'));
            $usuariox =$root->appendChild($usuariox);  
            
            $rolxml = $xml->createElement('NIVEL',session('rol'));
            $rolxml =$root->appendChild($rolxml);  

            $orderby1 = $xml->createElement('ORDERBY1',$sidx); 
            $orderby1 =$root->appendChild($orderby1);  

            $orderby2=$xml->createElement('ORDERBY2',$sord); 
            $orderby2 =$root->appendChild($orderby2); 

            $limitxml=$xml->createElement('LIMIT',$limit); 
            $limitxml =$root->appendChild($limitxml); 

            $offsetxml=$xml->createElement('OFFSET',$start); 
            $offsetxml =$root->appendChild($offsetxml); 

            $xml->formatOutput = true;

            $codigo = '015';
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
            //dd($array['NUMTIC']);
            //dd($array['TICKETS'][0]->IDTIC);
            //$totalg = $datos->NUMTIC[0];
            
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
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['TICKETS']->IDTIC),
                    trim($datos['TICKETS']->TICASU),
                    trim($datos['TICKETS']->TICTDE),
                    trim($datos['TICKETS']->TICADE),
                    trim($datos['TICKETS']->TICCPR),
                    trim($datos['TICKETS']->TICDES),
                    trim(date("d/m/Y", strtotime($datos['TICKETS']->TICFEC))),
                    '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="ver_ticket_historial('.trim((integer)$datos['TICKETS']->IDTIC).')"><i class="fa fa-search"></i> VER TICKET</button>'
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
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDTIC),
                    trim($Datos->TICASU),
                    trim($Datos->TICTDE),
                    trim($Datos->TICADE),
                    trim($Datos->TICCPR),
                    trim($Datos->TICDES),
                    trim(date("d/m/Y", strtotime($Datos->TICFEC))),
                    '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="ver_ticket_historial('.trim((integer)$Datos->IDTIC).')"><i class="fa fa-search"></i> VER TICKET</button>'
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function crear_tabla_buscar_tickets(Request $request)
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
            
            if(isset($request["titulo"]))
            {
                $titulo = strtoupper(trim($request['titulo']));
            }
            else
            {
                $titulo = "";
            }
            if(isset($request["fecha_desde"]) && isset($request["fecha_hasta"]))
            {
                $fdesde = $request['fecha_desde'];
                $fhasta = $request['fecha_hasta'].' 23:59:00';
            }    
            else
            {
                $fdesde = "";
                $fhasta = "";
            }
            
            $where="";
            if($titulo!='')
            {
                $where.= " AND cab.cabt_asunto LIKE '%$titulo%'";
            }
            if($fdesde!='' && $fhasta!='')
            {
                $where.= " AND cab.cabt_feccre between '$fdesde' and '$fhasta'";
            }
        
            self::setWsdl();
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuariox = $xml->createElement('USU',session('nombre_usuario'));
            $usuariox =$root->appendChild($usuariox);  
            
            $rolxml = $xml->createElement('NIVEL',session('rol'));
            $rolxml =$root->appendChild($rolxml);
            
            $wherexml = $xml->createElement('WHERE', $where);
            $wherexml =$root->appendChild($wherexml);

            $orderby1 = $xml->createElement('ORDERBY1',$sidx); 
            $orderby1 =$root->appendChild($orderby1);  

            $orderby2=$xml->createElement('ORDERBY2',$sord); 
            $orderby2 =$root->appendChild($orderby2); 

            $limitxml=$xml->createElement('LIMIT',$limit); 
            $limitxml =$root->appendChild($limitxml); 

            $offsetxml=$xml->createElement('OFFSET',$start); 
            $offsetxml =$root->appendChild($offsetxml); 

            $xml->formatOutput = true;

            $codigo = '016';
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
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['TICKETS']->IDTIC),
                    trim($datos['TICKETS']->TICASU),
                    trim($datos['TICKETS']->TICTDE),
                    trim($datos['TICKETS']->TICADE),
                    trim($datos['TICKETS']->TICCPR),
                    trim($datos['TICKETS']->TICDES),
                    trim(date("d/m/Y", strtotime($datos['TICKETS']->TICFEC))),
                    '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="ver_ticket_historial('.trim((integer)$datos['TICKETS']->IDTIC).')"><i class="fa fa-search"></i> VER TICKET</button>'
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
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDTIC),
                    trim($Datos->TICASU),
                    trim($Datos->TICTDE),
                    trim($Datos->TICADE),
                    trim($Datos->TICCPR),
                    trim($Datos->TICDES),
                    trim(date("d/m/Y", strtotime($Datos->TICFEC))),
                    '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="ver_ticket_historial('.trim((integer)$Datos->IDTIC).')"><i class="fa fa-search"></i> VER TICKET</button>'
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function recuperar_datos_ticket($id_ticket, Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('nombre_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $rolxml=$xml->createElement('NIVEL',session('rol')); 
        $rolxml =$root->appendChild($rolxml); 

        $idticketxml = $xml->createElement('ID',$id_ticket);
        $idticketxml =$root->appendChild($idticketxml);

        $xml->formatOutput = true;

        $codigo = '012';
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
        //dd($datos['FECCRE']);
        //dd($datos['RESPUESTA']);
        
        if ($datos['CODERR'] == '90006') 
        {
            return $datos['CODERR'];
        }
        else
        {
            return response()->json([
                'salida' => $datos['CODERR'],
                'respuesta' => $datos['NUMDET'],
                'id_ticket' => $datos['IDTICK'],
                'fecha_creacion' => date("d-m-Y", strtotime($datos['FECCRE'])),
                'tipo' => $datos['TIPO'],
                'area' => $datos['AREA'],
                'prioridad' => $datos['PRIO'],
                'fecha_actualizada' => date("d-m-Y", strtotime($datos['FECACT'])),
                'usuario_tec' => $datos['USUTEC'],
                'asunto' => $datos['ASUNTO'],
                'estado' => $datos['ESTAD'],
                'usuario_creador' => $datos['USUCRE'],
                'datos' => $datos['RESPUESTA'],
            ]);
        }
    }
    
    public function traer_datos_historial_encuesta(Request $request)
    {
        $encuesta = DB::table('cromohelp.tbl_encuesta')
                ->join('cromohelp.tbl_preguntas', 'tbl_encuesta.enc_idpreg', '=', 'tbl_preguntas.pre_id')
                ->join('cromohelp.tbl_valores', 'tbl_encuesta.enc_idvalor', '=', 'tbl_valores.val_id')
                ->select('tbl_preguntas.pre_desc', 'tbl_valores.val_img', 'tbl_valores.val_desc')
                ->where('tbl_encuesta.enc_idcab',$request['id_ticket'])
                ->get();
        
        $observacion = DB::table('cromohelp.tbl_observaciones')->where('obs_idcab',$request['id_ticket'])->get();
        
        if ($encuesta->count() > 0 && $observacion->count() > 0) 
        {
            return response()->json([
                'rpta' => 1,
                'encuesta' => $encuesta,
                'observacion' => $observacion,
            ]);
        }
        else
        {
            return response()->json([
                'rpta' => 0, 
            ]);
        }
    }
}