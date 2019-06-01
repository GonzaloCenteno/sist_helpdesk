<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class Factura_Controller extends BaseSoapController
{
    private $service;
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_facturas'],['btn_view',1]])->get();
                if ($permiso->count() == 0) 
                {
                    return view('errors/vw_sin_permiso',compact('menu'));
                }
            $datos =& $this->traer_datos();
            if($datos['CODERR']=='00000')
            {
                $proveedor = $datos['PROVEEDOR'];
                $num = $datos['NUMTIC'];
                return view('inventario/vw_factura',compact('menu','permiso','proveedor','num'));
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
            if ($request['grid'] == 'facturas') 
            {
                return $this->crear_tabla_facturas($request);
            }
            if ($request['grid'] == 'buscar_facturas') 
            {
                return $this->crear_tabla_buscar_facturas($request);
            }
        }
    }

    public function create(Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('id_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $seriexml = $xml->createElement('SER', strtoupper($request['serie']));
        $seriexml =$root->appendChild($seriexml);
        
        $numeroxml = $xml->createElement('NUM', strtoupper($request['numero']));
        $numeroxml =$root->appendChild($numeroxml);
        
        $montoxml = $xml->createElement('MON', $request['monto']);
        $montoxml =$root->appendChild($montoxml);
        
        $fechaxml = $xml->createElement('FEC', $request['fecha']);
        $fechaxml =$root->appendChild($fechaxml);
        
        $monedaxml = $xml->createElement('MONE', $request['moneda']);
        $monedaxml =$root->appendChild($monedaxml);
        
        $productoxml = $xml->createElement('PRO', $request['id_producto']);
        $productoxml =$root->appendChild($productoxml);

        $xml->formatOutput = true;

        $codigo = '019';
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

    public function edit($id_factura,Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('id_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $idfacturaxml = $xml->createElement('ID', $id_factura);
        $idfacturaxml =$root->appendChild($idfacturaxml);
        
        $seriexml = $xml->createElement('SER', strtoupper($request['serie']));
        $seriexml =$root->appendChild($seriexml);
        
        $numeroxml = $xml->createElement('NUM', strtoupper($request['numero']));
        $numeroxml =$root->appendChild($numeroxml);
        
        $montoxml = $xml->createElement('MON', $request['monto']);
        $montoxml =$root->appendChild($montoxml);
        
        $fechaxml = $xml->createElement('FEC', $request['fecha']);
        $fechaxml =$root->appendChild($fechaxml);
        
        $monedaxml = $xml->createElement('MONE', $request['moneda']);
        $monedaxml =$root->appendChild($monedaxml);
        
        $productoxml = $xml->createElement('PRO', $request['id_producto']);
        $productoxml =$root->appendChild($productoxml);

        $xml->formatOutput = true;

        $codigo = '024';
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
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USU',session('id_usuario'));
        $usuariox = $root->appendChild($usuariox);  

        $idfactxml = $xml->createElement('IDFACT',$request['id_factura']); 
        $idfactxml = $root->appendChild($idfactxml);

        if($request->hasFile('file'))
        {
            $nombre = $request->file->getClientOriginalName();
            $ruta = $request->file->storeAs('public/Facturas',date('Y-m-d'). '_' . uniqid(). '_' .$nombre);
            $facimgxml = $xml->createElement('FACIMG',$ruta); 
            $facimgxml = $root->appendChild($facimgxml);
        } 
        else
        {
            $facimgxml = $xml->createElement('FACIMG','-'); 
            $facimgxml =$root->appendChild($facimgxml);
        }

        $xml->formatOutput = true;

        $codigo = '052';
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
        $datos = simplexml_load_string($xmlr2);
        //dd($datos);

        return response()->json([
            'respuesta' => $datos->CODERR[0],
            'mensaje' => $datos->MSGERR[0],
        ]);
    }
    
    public function &traer_datos()
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuariox = $xml->createElement('USU',session('id_usuario')); 
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
    
    public function crear_tabla_facturas(Request $request)
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

            $codigo = '031';
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
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_facturas'],['btn_view',1]])->get();
            
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
            $Lista->rows[0]['id'] = (integer)$datos['FACTURA']->IDFAC;
            if ($permiso[0]->btn_edit == 1) 
            {
                if ($datos['FACTURA']->FACIMG == '-') {
                    $archivo = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" id="btn_subir_archivo" data-toggle="modal" data-target="#Modal_Archivo" data-backdrop="static" data-keyboard="false"><i class="fa fa-folder-open"></i> Subir</button>';
                }else{
                    $archivo = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Archivado</button>'; 
                }
            }
            else
            {
                if ($datos['FACTURA']->FACIMG == '-') {
                    $archivo = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="sin_permiso();"><i class="fa fa-folder-open"></i> Subir</button>';
                }else{
                    $archivo = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Archivado</button>'; 
                }
            }
            if ($datos['FACTURA']->FACMON == 0) {
                $moneda = '<label>S/</label>';
            }else{
                $moneda = '<label>$</label>';
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['FACTURA']->IDFAC),
                trim($datos['FACTURA']->SERIE),
                trim($datos['FACTURA']->NUM),
                trim($datos['FACTURA']->MONTO),
                trim($datos['FACTURA']->FECFAC),
                trim($datos['FACTURA']->IDPRO),
                trim($datos['FACTURA']->DESPRO),
                trim($datos['FACTURA']->FACMON),
                $moneda,
                $archivo
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
           foreach ($datos['FACTURA'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDFAC;
                if ($permiso[0]->btn_edit == 1) 
                {
                    if ($Datos->FACIMG == '-') {
                        $archivo = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" id="btn_subir_archivo" data-toggle="modal" data-target="#Modal_Archivo" data-backdrop="static" data-keyboard="false"><i class="fa fa-folder-open"></i> Subir</button>';
                    }else{
                        $archivo = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Archivado</button>'; 
                    }
                }
                else
                {
                    if ($Datos->FACIMG == '-') {
                        $archivo = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="sin_permiso();"><i class="fa fa-folder-open"></i> Subir</button>';
                    }else{
                        $archivo = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Archivado</button>'; 
                    }
                }
                if ($Datos->FACMON == 0) {
                    $moneda = '<label>S/</label>';
                }else{
                    $moneda = '<label>$</label>';
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDFAC),
                    trim($Datos->SERIE),
                    trim($Datos->NUM),
                    trim($Datos->MONTO),
                    trim($Datos->FECFAC),
                    trim($Datos->IDPRO),
                    trim($Datos->DESPRO),
                    trim($Datos->FACMON),
                    $moneda,
                    $archivo
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function crear_tabla_buscar_facturas(Request $request)
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
        
            if(isset($request["serie_num"]))
            {
                $serie_num = strtoupper(trim($request['serie_num']));
            }
            else
            {
                $serie_num = "";
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
            if($serie_num!='')
            {
                $where.= " AND coalesce(fac.fact_serie||'-'||fac.fact_num) LIKE '%$serie_num%'";
            }
                    
            
            if($fdesde!='' && $fhasta!='')
            {
                $where.= " AND fact_fec between '$fdesde' and '$fhasta'";
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

            $codigo = '032';
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
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_facturas'],['btn_view',1]])->get();
            
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
            $Lista->rows[0]['id'] = (integer)$datos['FACTURA']->IDFAC;
            if ($permiso[0]->btn_edit == 1) 
            {
                if ($datos['FACTURA']->FACIMG == '-') {
                    $archivo = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" id="btn_subir_archivo" data-toggle="modal" data-target="#Modal_Archivo" data-backdrop="static" data-keyboard="false"><i class="fa fa-folder-open"></i> Subir</button>';
                }else{
                    $archivo = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Archivado</button>'; 
                }
            }
            else
            {
                if ($datos['FACTURA']->FACIMG == '-') {
                    $archivo = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="sin_permiso();"><i class="fa fa-folder-open"></i> Subir</button>';
                }else{
                    $archivo = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Archivado</button>'; 
                }
            }
            if ($datos['FACTURA']->FACMON == 0) {
                $moneda = '<label>S/</label>';
            }else{
                $moneda = '<label>$</label>';
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['FACTURA']->IDFAC),
                trim($datos['FACTURA']->SERIE),
                trim($datos['FACTURA']->NUM),
                trim($datos['FACTURA']->MONTO),
                trim($datos['FACTURA']->FECFAC),
                trim($datos['FACTURA']->IDPRO),
                trim($datos['FACTURA']->DESPRO),
                trim($datos['FACTURA']->FACMON),
                $moneda,
                $archivo
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
           foreach ($datos['FACTURA'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDFAC;
                if ($permiso[0]->btn_edit == 1) 
                {
                    if ($Datos->FACIMG == '-') {
                        $archivo = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" id="btn_subir_archivo" data-toggle="modal" data-target="#Modal_Archivo" data-backdrop="static" data-keyboard="false"><i class="fa fa-folder-open"></i> Subir</button>';
                    }else{
                        $archivo = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Archivado</button>'; 
                    }
                }
                else
                {
                    if ($Datos->FACIMG == '-') {
                        $archivo = '<button class="btn btn-lg" style="background-color:#D48411;color:white;" type="button" onclick="sin_permiso();"><i class="fa fa-folder-open"></i> Subir</button>';
                    }else{
                        $archivo = '<button class="btn btn-lg btn-danger" type="button"><i class="fa fa-check"></i> Archivado</button>'; 
                    }
                }
                if ($Datos->FACMON == 0) {
                    $moneda = '<label>S/</label>';
                }else{
                    $moneda = '<label>$</label>';
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDFAC),
                    trim($Datos->SERIE),
                    trim($Datos->NUM),
                    trim($Datos->MONTO),
                    trim($Datos->FECFAC),
                    trim($Datos->IDPRO),
                    trim($Datos->DESPRO),
                    trim($Datos->FACMON),
                    $moneda,
                    $archivo
                );  
            }
            return response()->json($Lista);
        }
    }

}
