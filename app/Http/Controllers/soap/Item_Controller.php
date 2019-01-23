<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tbl_item;
use Validator;

class Item_Controller extends BaseSoapController
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
                
                $proveedores =& $this->traer_proveedores();
                $marcas =& $this->traer_marcas();
                $facturas =& $this->traer_facturas();
                //dd($marcas);
                if($proveedores['CODERR']=='00000' && $marcas['CODERR']=='00000' && $facturas['CODERR']=='00000')
                {
                    $proveedor = $proveedores['PROVEEDOR'];
                    $marca = $marcas['MARCA'];
                    $factura = $facturas['FACTURA'];
                    $num_pro = $proveedores['NUMTIC'];
                    $num_mar = $marcas['NUMTIC'];
                    $num_fac = $facturas['NUMTIC'];
                    return view('inventario/vw_item',compact('tblmenu_men','tblmenu_men2','proveedor','marca','factura','num_pro','num_mar','num_fac'));
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
            if ($request['grid'] == 'items') 
            {
                return $this->crear_tabla_items($request);
            }
            if ($request['grid'] == 'buscar_items') 
            {
                return $this->crear_tabla_buscar_items($request);
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
        
        $descripcionxml = $xml->createElement('DES', strtoupper($request['descripcion']));
        $descripcionxml =$root->appendChild($descripcionxml);
        
        $seriexml = $xml->createElement('SER', strtoupper($request['serie']));
        $seriexml =$root->appendChild($seriexml);
        
        $cantidadxml = $xml->createElement('CAN', $request['cantidad']);
        $cantidadxml =$root->appendChild($cantidadxml);
        
        $precioxml = $xml->createElement('PRE', $request['precio']);
        $precioxml =$root->appendChild($precioxml);
        
        $marcaxml = $xml->createElement('IDM', $request['id_marca']);
        $marcaxml =$root->appendChild($marcaxml);
        
        $proovedorxml = $xml->createElement('IDP', $request['id_proveedor']);
        $proovedorxml =$root->appendChild($proovedorxml);
        
        $facturaxml = $xml->createElement('FAC', $request['id_factura']);
        $facturaxml =$root->appendChild($facturaxml);
        
        $fechaxml = $xml->createElement('FEC', date("d/m/Y", strtotime($request['fecha'])));
        $fechaxml =$root->appendChild($fechaxml);

        $xml->formatOutput = true;

        $codigo = '020';
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

    public function edit($id_item,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->editar_datos_item($id_item, $request);
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
    
    public function &traer_proveedores()
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USU',session('nombre_usuario')); 
        $usuariox =$root->appendChild($usuariox);
        
        $xml->formatOutput = true;

        $codigo='038';
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
    
    public function &traer_marcas()
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USU',session('nombre_usuario')); 
        $usuariox =$root->appendChild($usuariox);
        
        $xml->formatOutput = true;

        $codigo='037';
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
    
    public function &traer_facturas()
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USU',session('nombre_usuario')); 
        $usuariox =$root->appendChild($usuariox);
        
        $xml->formatOutput = true;

        $codigo='039';
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
    
    public function crear_tabla_items(Request $request)
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

            $codigo = '033';
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
            $Lista->rows[0]['id'] = (integer)$datos['ITEM']->IDITEM;
            if ($datos['ITEM']->ESTITE == 'Activo') {
                $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_item('.trim((integer)$datos['ITEM']->IDITEM).',6)"><i class="fa fa-check"></i> Activo</button>';
            }else{
                $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_item('.trim((integer)$datos['ITEM']->IDITEM).',5)"><i class="fa fa-times"></i> Desactivo</button>'; 
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['ITEM']->IDITEM),
                trim($datos['ITEM']->DESITE),
                trim($datos['ITEM']->SERITE),
                trim($datos['ITEM']->CATITE),
                trim($datos['ITEM']->IDMARC),
                trim($datos['ITEM']->MARITE),
                trim($datos['ITEM']->IDPROV),
                trim($datos['ITEM']->PROITE),   
                trim($datos['ITEM']->IDFACT),   
                trim($datos['ITEM']->FACITE),   
                trim($datos['ITEM']->PREITE),   
                trim($datos['ITEM']->FECITE),   
                $nuevo,
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
           foreach ($datos['ITEM'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDITEM;
                if ($Datos->ESTITE == 'Activo') {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_item('.trim((integer)$Datos->IDITEM).',6)"><i class="fa fa-check"></i> Activo</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_item('.trim((integer)$Datos->IDITEM).',5)"><i class="fa fa-times"></i> Desactivo</button>'; 
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDITEM),
                    trim($Datos->DESITE),
                    trim($Datos->SERITE),
                    trim($Datos->CATITE),
                    trim($Datos->IDMARC),
                    trim($Datos->MARITE),
                    trim($Datos->IDPROV),
                    trim($Datos->PROITE),
                    trim($Datos->IDFACT),
                    trim($Datos->FACITE),
                    trim($Datos->PREITE),
                    trim($Datos->FECITE),
                    $nuevo,
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function crear_tabla_buscar_items(Request $request)
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
            
            $seriexml = $xml->createElement('FAC', strtoupper($request['serie']));
            $seriexml =$root->appendChild($seriexml);
            
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

            $codigo = '034';
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
            $Lista->rows[0]['id'] = (integer)$datos['ITEM']->IDITEM;
            if ($datos['ITEM']->ESTITE == 'Activo') {
                $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_item('.trim((integer)$datos['ITEM']->IDITEM).',6)"><i class="fa fa-check"></i> Activo</button>';
            }else{
                $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_item('.trim((integer)$datos['ITEM']->IDITEM).',5)"><i class="fa fa-times"></i> Desactivo</button>'; 
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['ITEM']->IDITEM),
                trim($datos['ITEM']->DESITE),
                trim($datos['ITEM']->SERITE),
                trim($datos['ITEM']->CATITE),
                trim($datos['ITEM']->IDMARC),
                trim($datos['ITEM']->MARITE),
                trim($datos['ITEM']->IDPROV),
                trim($datos['ITEM']->PROITE),   
                trim($datos['ITEM']->IDFACT),   
                trim($datos['ITEM']->FACITE),   
                trim($datos['ITEM']->PREITE),   
                trim($datos['ITEM']->FECITE),   
                $nuevo
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
           foreach ($datos['ITEM'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDITEM;
                if ($Datos->ESTITE == 'Activo') {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_item('.trim((integer)$Datos->IDITEM).',6)"><i class="fa fa-check"></i> Activo</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_item('.trim((integer)$Datos->IDITEM).',5)"><i class="fa fa-times"></i> Desactivo</button>'; 
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDITEM),
                    trim($Datos->DESITE),
                    trim($Datos->SERITE),
                    trim($Datos->CATITE),
                    trim($Datos->IDMARC),
                    trim($Datos->MARITE),
                    trim($Datos->IDPROV),
                    trim($Datos->PROITE),
                    trim($Datos->IDFACT),
                    trim($Datos->FACITE),
                    trim($Datos->PREITE),
                    trim($Datos->FECITE),
                    $nuevo,
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function editar_datos_item($id_item, Request $request)
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('nombre_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $iditemxml = $xml->createElement('ID', $id_item);
        $iditemxml =$root->appendChild($iditemxml);
        
        $descripcionxml = $xml->createElement('DES', strtoupper($request['descripcion']));
        $descripcionxml =$root->appendChild($descripcionxml);
        
        $seriexml = $xml->createElement('SER', strtoupper($request['serie']));
        $seriexml =$root->appendChild($seriexml);
        
        $cantidadxml = $xml->createElement('CAN', $request['cantidad']);
        $cantidadxml =$root->appendChild($cantidadxml);
        
        $precioxml = $xml->createElement('PRE', $request['precio']);
        $precioxml =$root->appendChild($precioxml);
        
        $proveedorxml = $xml->createElement('IDP', $request['id_proveedor']);
        $proveedorxml =$root->appendChild($proveedorxml);
        
        $marcaxml = $xml->createElement('IDM', $request['id_marca']);
        $marcaxml =$root->appendChild($marcaxml);
        
        $facturaxml = $xml->createElement('FAC', $request['id_factura']);
        $facturaxml =$root->appendChild($facturaxml);
        
        $fechaxml = $xml->createElement('FEC', date("d/m/Y", strtotime($request['fecha'])));
        $fechaxml =$root->appendChild($fechaxml);

        $xml->formatOutput = true;

        $codigo = '025';
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
