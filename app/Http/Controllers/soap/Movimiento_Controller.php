<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class Movimiento_Controller extends BaseSoapController
{
    private $service;
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            if (session('rol') == 1 || session('rol') == 2) 
            {
                $tblmenu_men = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
                $tblmenu_men2 = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
                $tblmenu_men3 = DB::table('tblmenu_men')->where([['menu_sist',session('menu_sist')],['menu_rol',session('menu_rol')],['menu_est',1],['menu_niv',3]])->orderBy('menu_id','asc')->get();
                
                $items =& $this->traer_items();
                $punto_ventas =& $this->traer_puntos_venta();
                //dd($punto_venta);
                if($items['CODERR']=='00000' && $punto_ventas['CODERR']=='00000')
                {
                    $item = $items['ITEM'];
                    $punto_venta = $punto_ventas['PVT'];
                    $num_ite = $items['NUMTIC'];
                    $num_pvt = $punto_ventas['NUMTIC'];
                    return view('inventario/vw_movimiento',compact('tblmenu_men','tblmenu_men2','tblmenu_men3','item','punto_venta','num_ite','num_pvt'));
                }

                echo "HUBO UN ERROR TRAENDO LOS DATOS";
            }
            else
            {
                return view('errors/vw_sin_permiso',compact('tblmenu_men'));
            }     
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
            
        }
        else
        {
            if ($request['grid'] == 'movimientos') 
            {
                return $this->crear_tabla_movimientos($request);
            }
            if ($request['grid'] == 'buscar_movimientos') 
            {
                return $this->crear_tabla_buscar_movimientos($request);
            }
        }
    }

    public function create(Request $request)
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('nombre_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  
        
        $iditemxml = $xml->createElement('IDI', $request['id_item']);
        $iditemxml =$root->appendChild($iditemxml);
        
        $pvtorigenxml = $xml->createElement('PVTO', $request['pvt_origen']);
        $pvtorigenxml =$root->appendChild($pvtorigenxml);
        
        $pvtdestinoxml = $xml->createElement('PVTD', $request['pvt_destino']);
        $pvtdestinoxml =$root->appendChild($pvtdestinoxml);
        
        $fechaxml = $xml->createElement('FEC', date("d/m/Y", strtotime($request['fecha'])));
        $fechaxml =$root->appendChild($fechaxml);
        
        $idusuarioxml = $xml->createElement('IDU', session('id_usuario'));
        $idusuarioxml =$root->appendChild($idusuarioxml);

        $xml->formatOutput = true;

        $codigo = '021';
        $trama = $xml->saveXML();
//        dd($trama);

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

    public function edit($id_movimiento,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->editar_datos_movimientos($id_movimiento, $request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->cambiar_estado_items($id_item, $request);
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        
    }
    
    public function &traer_items()
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USU',session('nombre_usuario')); 
        $usuariox =$root->appendChild($usuariox);
        
        $xml->formatOutput = true;

        $codigo='041';
        $trama = $xml->saveXML();   
        //dd($trama);

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
        $datos = (array) @simplexml_load_string($xmlr2);
        
        return $datos;
    }
    
    public function &traer_puntos_venta()
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USU',session('nombre_usuario')); 
        $usuariox =$root->appendChild($usuariox);
        
        $xml->formatOutput = true;

        $codigo='040';
        $trama = $xml->saveXML();   
        //dd($trama);

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
        $datos = (array) @simplexml_load_string($xmlr2);
        
        return $datos;
    }
    
    public function crear_tabla_movimientos(Request $request)
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
        
            self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
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

            $codigo = '035';
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
            $Lista->rows[0]['id'] = (integer)$datos['MOVIMIENTOS']->IDITEM;
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['MOVIMIENTOS']->IDITEM),
                trim($datos['MOVIMIENTOS']->IDITE),
                trim($datos['MOVIMIENTOS']->DESITE),
                trim($datos['MOVIMIENTOS']->IDORI),
                trim($datos['MOVIMIENTOS']->DESORI),
                trim($datos['MOVIMIENTOS']->IDDES),
                trim($datos['MOVIMIENTOS']->DESMOV),
                trim($datos['MOVIMIENTOS']->USUMOV),
                trim($datos['MOVIMIENTOS']->USUNAM),   
                trim($datos['MOVIMIENTOS']->FECMOV),   
                trim($datos['MOVIMIENTOS']->ESTMOV),      
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
           foreach ($datos['MOVIMIENTOS'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDITEM;
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDITEM),
                    trim($Datos->IDITE),
                    trim($Datos->DESITE),
                    trim($Datos->IDORI),
                    trim($Datos->DESORI),
                    trim($Datos->IDDES),
                    trim($Datos->DESMOV),
                    trim($Datos->USUMOV),
                    trim($Datos->USUNAM),
                    trim($Datos->FECMOV),
                    trim($Datos->ESTMOV),
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function editar_datos_movimientos($id_movimiento, Request $request)
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('nombre_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  
        
        $idmovimientoxml = $xml->createElement('IDM', $id_movimiento);
        $idmovimientoxml =$root->appendChild($idmovimientoxml);
        
        $iditemxml = $xml->createElement('IDI', $request['id_item']);
        $iditemxml =$root->appendChild($iditemxml);
        
        $pvtorigenxml = $xml->createElement('PVTO', $request['pvt_origen']);
        $pvtorigenxml =$root->appendChild($pvtorigenxml);
        
        $pvtdestinoxml = $xml->createElement('PVTD', $request['pvt_destino']);
        $pvtdestinoxml =$root->appendChild($pvtdestinoxml);
        
        $fechaxml = $xml->createElement('FEC', date("d/m/Y", strtotime($request['fecha'])));
        $fechaxml =$root->appendChild($fechaxml);
        
        $idusuarioxml = $xml->createElement('IDU', session('id_usuario'));
        $idusuarioxml =$root->appendChild($idusuarioxml);

        $xml->formatOutput = true;

        $codigo = '026';
        $trama = $xml->saveXML();
//        dd($trama);

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
    
    public function crear_tabla_buscar_movimientos(Request $request)
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
        
            self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuariox = $xml->createElement('USU',session('nombre_usuario'));
            $usuariox =$root->appendChild($usuariox);  
            
            $rolxml = $xml->createElement('NIVEL',session('rol'));
            $rolxml =$root->appendChild($rolxml);  
            
            $descripcionxml = $xml->createElement('DES', strtoupper($request['descripcion']));
            $descripcionxml =$root->appendChild($descripcionxml);
            
            $fecinixml = $xml->createElement('FECINI',date("d/m/Y", strtotime($request['fecha_desde'])));
            $fecinixml =$root->appendChild($fecinixml);  
            
            $fecfinxml = $xml->createElement('FECFIN',date("d/m/Y", strtotime($request['fecha_hasta'])).' 23:59:00');
            $fecfinxml =$root->appendChild($fecfinxml);

            $orderby1 = $xml->createElement('ORDERBY1',$sidx); 
            $orderby1 =$root->appendChild($orderby1);  

            $orderby2=$xml->createElement('ORDERBY2',$sord); 
            $orderby2 =$root->appendChild($orderby2); 

            $limitxml=$xml->createElement('LIMIT',$limit); 
            $limitxml =$root->appendChild($limitxml); 

            $offsetxml=$xml->createElement('OFFSET',$start); 
            $offsetxml =$root->appendChild($offsetxml); 

            $xml->formatOutput = true;

            $codigo = '036';
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
            $Lista->rows[0]['id'] = (integer)$datos['MOVIMIENTOS']->IDITEM;
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['MOVIMIENTOS']->IDITEM),
                trim($datos['MOVIMIENTOS']->IDITE),
                trim($datos['MOVIMIENTOS']->DESITE),
                trim($datos['MOVIMIENTOS']->IDORI),
                trim($datos['MOVIMIENTOS']->DESORI),
                trim($datos['MOVIMIENTOS']->IDDES),
                trim($datos['MOVIMIENTOS']->DESMOV),
                trim($datos['MOVIMIENTOS']->USUMOV),
                trim($datos['MOVIMIENTOS']->USUNAM),   
                trim($datos['MOVIMIENTOS']->FECMOV),   
                trim($datos['MOVIMIENTOS']->ESTMOV),      
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
           foreach ($datos['MOVIMIENTOS'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDITEM;
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDITEM),
                    trim($Datos->IDITE),
                    trim($Datos->DESITE),
                    trim($Datos->IDORI),
                    trim($Datos->DESORI),
                    trim($Datos->IDDES),
                    trim($Datos->DESMOV),
                    trim($Datos->USUMOV),
                    trim($Datos->USUNAM),
                    trim($Datos->FECMOV),
                    trim($Datos->ESTMOV),
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function cambiar_estado_items($id_item,Request $request)
    {
        $Tbl_item = new Tbl_item;
        $val=  $Tbl_item::where("item_id","=",$id_item)->first();
        if($val)
        {
            $val->item_est = $request['estado'];
            $val->save();
        }
        return $id_item;
    }

}
