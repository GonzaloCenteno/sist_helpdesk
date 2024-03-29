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
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_items'],['btn_view',1]])->get();
                if ($permiso->count() == 0) 
                {
                    return view('errors/vw_sin_permiso',compact('menu'));
                }
            $datos =& $this->traer_datos_item();
            if($datos['CODERR']=='00000')
            {
                $nummar = $datos['NUMAR'];
                $numpro = $datos['NUMPRO'];
                $numfac = $datos['NUMFAC'];
                return view('inventario/vw_item',compact('menu','permiso','nummar','numpro','numfac','datos'));
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
        if ($request['tipo'] == 1) 
        {
            return $this->crear_items($request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->crear_evaluacion($request);
        }
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
        
            self::setWsdl();
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuariox = $xml->createElement('USU',session('id_usuario'));
            $usuariox =$root->appendChild($usuariox);  
            
            $rolxml = $xml->createElement('NIVEL',session('sro_id'));
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
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_items'],['btn_view',1]])->get();
            
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
            if ($permiso[0]->btn_del == 1) 
            {
                if ($datos['ITEM']->ESTITE == 'Activo') {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_item('.trim((integer)$datos['ITEM']->IDITEM).',6)"><i class="fa fa-check"></i> Activo</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_item('.trim((integer)$datos['ITEM']->IDITEM).',5)"><i class="fa fa-times"></i> Desactivo</button>'; 
                }
            }
            else
            {
                if ($datos['ITEM']->ESTITE == 'Activo') {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> Activo</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> Desactivo</button>'; 
                }
            }
            
            if ($permiso[0]->btn_new == 1) 
            {
                if ($datos['ITEM']->CALIF == 0) {
                    $calificacion = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" id="btn_evaluar_item" data-toggle="modal" data-target="#Modal_Evaluacion" data-backdrop="static" data-keyboard="false"><i class="fa fa-th-list"></i> Evaluar</button>';
                }else{
                    $calificacion = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Evaluado</button>'; 
                }
            }
            else
            {
                if ($datos['ITEM']->CALIF == 0) {
                    $calificacion = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="sin_permiso();"><i class="fa fa-th-list"></i> Evaluar</button>';
                }else{
                    $calificacion = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Evaluado</button>'; 
                }
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
                $calificacion
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
                if ($permiso[0]->btn_del == 1) 
                {
                    if ($Datos->ESTITE == 'Activo') {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_item('.trim((integer)$Datos->IDITEM).',6)"><i class="fa fa-check"></i> Activo</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_item('.trim((integer)$Datos->IDITEM).',5)"><i class="fa fa-times"></i> Desactivo</button>'; 
                    }
                }
                else
                {
                    if ($Datos->ESTITE == 'Activo') {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> Activo</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> Desactivo</button>'; 
                    }
                }
                
                if ($permiso[0]->btn_new == 1) 
                {
                    if ($Datos->CALIF == 0) {
                        $calificacion = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" id="btn_evaluar_item" data-toggle="modal" data-target="#Modal_Evaluacion" data-backdrop="static" data-keyboard="false"><i class="fa fa-th-list"></i> Evaluar</button>';
                    }else{
                        $calificacion = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Evaluado</button>'; 
                    }
                }
                else
                {
                    if ($Datos->CALIF == 0) {
                        $calificacion = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="sin_permiso();"><i class="fa fa-th-list"></i> Evaluar</button>';
                    }else{
                        $calificacion = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Evaluado</button>'; 
                    }
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
                    $calificacion
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
        
            if(isset($request["descripcion"]))
            {
                $descripcion = strtoupper(trim($request['descripcion']));
            }
            else
            {
                $descripcion = "";
            }
            if(isset($request["serie"]))
            {
                $serie = strtoupper(trim($request['serie']));
            }    
            else
            {
                $serie = "";
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
            
            $where="WHERE 1=1";
            if($descripcion!='')
            {
                $where.= " AND item_desc LIKE '%$descripcion%'";
            }
                    
            if($serie!='')
            {
                $where.= " AND coalesce(fac.fact_serie||'-'||fac.fact_num) LIKE '%$serie%'";
            }
            
            if($fdesde!='' && $fhasta!='')
            {
                $where.= " AND item_fec between '$fdesde' and '$fhasta'";
            }
        
            self::setWsdl();
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuariox = $xml->createElement('USU',session('id_usuario'));
            $usuariox =$root->appendChild($usuariox);  
            
            $rolxml = $xml->createElement('NIVEL',session('sro_id'));
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
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_items'],['btn_view',1]])->get();
            
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
            if ($permiso[0]->btn_del == 1) 
            {
                if ($datos['ITEM']->ESTITE == 'Activo') {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_item('.trim((integer)$datos['ITEM']->IDITEM).',6)"><i class="fa fa-check"></i> Activo</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_item('.trim((integer)$datos['ITEM']->IDITEM).',5)"><i class="fa fa-times"></i> Desactivo</button>'; 
                }
            }
            else
            {
                if ($datos['ITEM']->ESTITE == 'Activo') {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> Activo</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> Desactivo</button>'; 
                }
            }
            
            if ($permiso[0]->btn_new == 1) 
            {
                if ($datos['ITEM']->CALIF == 0) {
                    $calificacion = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" id="btn_evaluar_item" data-toggle="modal" data-target="#Modal_Evaluacion" data-backdrop="static" data-keyboard="false"><i class="fa fa-th-list"></i> Evaluar</button>';
                }else{
                    $calificacion = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Evaluado</button>'; 
                }
            }
            else
            {
                if ($datos['ITEM']->CALIF == 0) {
                    $calificacion = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="sin_permiso();"><i class="fa fa-th-list"></i> Evaluar</button>';
                }else{
                    $calificacion = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Evaluado</button>'; 
                }
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
                $calificacion
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
                if ($permiso[0]->btn_del == 1) 
                {
                    if ($Datos->ESTITE == 'Activo') {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_item('.trim((integer)$Datos->IDITEM).',6)"><i class="fa fa-check"></i> Activo</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_item('.trim((integer)$Datos->IDITEM).',5)"><i class="fa fa-times"></i> Desactivo</button>'; 
                    }
                }
                else
                {
                    if ($Datos->ESTITE == 'Activo') {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> Activo</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> Desactivo</button>'; 
                    }
                }
                
                if ($permiso[0]->btn_new == 1) 
                {
                    if ($Datos->CALIF == 0) {
                        $calificacion = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" id="btn_evaluar_item" data-toggle="modal" data-target="#Modal_Evaluacion" data-backdrop="static" data-keyboard="false"><i class="fa fa-th-list"></i> Evaluar</button>';
                    }else{
                        $calificacion = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Evaluado</button>'; 
                    }
                }
                else
                {
                    if ($Datos->CALIF == 0) {
                        $calificacion = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="sin_permiso();"><i class="fa fa-th-list"></i> Evaluar</button>';
                    }else{
                        $calificacion = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Evaluado</button>'; 
                    }
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
                    $calificacion
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function editar_datos_item($id_item, Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('id_usuario'));
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
        
        $precio1xml = $xml->createElement('PRE1', $request['old_precio']);
        $precio1xml =$root->appendChild($precio1xml);
        
        $proveedorxml = $xml->createElement('IDP', $request['id_proveedor']);
        $proveedorxml =$root->appendChild($proveedorxml);
        
        $marcaxml = $xml->createElement('IDM', $request['id_marca']);
        $marcaxml =$root->appendChild($marcaxml);
        
        $facturaxml = $xml->createElement('FAC', $request['id_factura']);
        $facturaxml =$root->appendChild($facturaxml);
        
        $fechaxml = $xml->createElement('FEC', $request['fecha']);
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
    
    public function crear_items(Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('id_usuario'));
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
        
        $fechaxml = $xml->createElement('FEC', $request['fecha']);
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
    
    public function crear_evaluacion(Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('id_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  
        
        $iditemxml = $xml->createElement('ID', $request['id_item']);
        $iditemxml =$root->appendChild($iditemxml);
        
        $fecsolxml = $xml->createElement('FECSOL', $request['fecsol_calif']);
        $fecsolxml =$root->appendChild($fecsolxml);
        
        $fecentxml = $xml->createElement('FECENT', $request['fecent_calif']);
        $fecentxml =$root->appendChild($fecentxml);
        
        $pprexml = $xml->createElement('PPRE', $request['ppre_calif']);
        $pprexml =$root->appendChild($pprexml);
        
        $pcalxml = $xml->createElement('PCAL', $request['pcal_calif']);
        $pcalxml =$root->appendChild($pcalxml);
        
        $pstokxml = $xml->createElement('PSTOK', $request['pstok_calif']);
        $pstokxml =$root->appendChild($pstokxml);
        
        $pcrexml = $xml->createElement('PCRE', $request['pcre_calif']);
        $pcrexml =$root->appendChild($pcrexml);
        
        $pdocxml = $xml->createElement('PDOC', $request['pdoc_calif']);
        $pdocxml =$root->appendChild($pdocxml);

        $xml->formatOutput = true;

        $codigo = '051';
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
    
    public function &traer_datos_item()
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

        $c2='055';
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
    
}
