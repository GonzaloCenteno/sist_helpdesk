<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class Factura_Controller extends BaseSoapController
{
    private $service;
    public function index()
    {
        try 
        {
            $tblusuarios_usu = DB::table('tblusuarios_usu')->where([['ldap_id',session('id_usuario')],['sist_id',1]])->first();
            if ($tblusuarios_usu) 
            {
                return $this->traer_datos($tblusuarios_usu->sist_id,$tblusuarios_usu->rol_id);
            }
            else
            {
                return view('errors/vw_sin_permiso',compact('tblmenu_men'));
            }
        }
        catch(\Exception $e) 
        {
            return $e->getMessage();
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
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('nombre_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $seriexml = $xml->createElement('SER', strtoupper($request['serie']));
        $seriexml =$root->appendChild($seriexml);
        
        $numeroxml = $xml->createElement('NUM', strtoupper($request['numero']));
        $numeroxml =$root->appendChild($numeroxml);
        
        $montoxml = $xml->createElement('MON', $request['monto']);
        $montoxml =$root->appendChild($montoxml);
        
        $fechaxml = $xml->createElement('FEC', date("d/m/Y", strtotime($request['fecha'])));
        $fechaxml =$root->appendChild($fechaxml);
        
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
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('nombre_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $idfacturaxml = $xml->createElement('ID', $id_factura);
        $idfacturaxml =$root->appendChild($idfacturaxml);
        
        $seriexml = $xml->createElement('SER', strtoupper($request['serie']));
        $seriexml =$root->appendChild($seriexml);
        
        $numeroxml = $xml->createElement('NUM', strtoupper($request['numero']));
        $numeroxml =$root->appendChild($numeroxml);
        
        $montoxml = $xml->createElement('MON', $request['monto']);
        $montoxml =$root->appendChild($montoxml);
        
        $fechaxml = $xml->createElement('FEC', date("d/m/Y", strtotime($request['fecha'])));
        $fechaxml =$root->appendChild($fechaxml);
        
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
        
    }
    
    public function traer_datos($sist_id,$rol_id)
    {
        $tblmenu_men = DB::table('tblmenu_men')->where([['menu_sist',$sist_id],['menu_rol',$rol_id],['menu_est',1],['menu_niv',1]])->orderBy('menu_id','asc')->get();
        $tblmenu_men2 = DB::table('tblmenu_men')->where([['menu_sist',$sist_id],['menu_rol',$rol_id],['menu_est',1],['menu_niv',2]])->orderBy('menu_id','asc')->get();
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
        
        //dd($datos['PROVEEDOR']);

        if($datos['CODERR']=='00000')
        {
            $proveedor = $datos['PROVEEDOR'];
            $num = $datos['NUMTIC'];
            //dd($proveedor->IDPRO);
            return view('inventario/vw_factura',compact('tblmenu_men','tblmenu_men2','proveedor','num'));
        }
        
        echo "HUBO UN ERROR TRAENDO LOS DATOS DEL PROVEEDOR";
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
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['FACTURA']->IDFAC),
                trim($datos['FACTURA']->SERIE),
                trim($datos['FACTURA']->NUM),
                trim($datos['FACTURA']->MONTO),
                trim($datos['FACTURA']->FECFAC),
                trim($datos['FACTURA']->IDPRO),
                trim($datos['FACTURA']->DESPRO),   
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
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDFAC),
                    trim($Datos->SERIE),
                    trim($Datos->NUM),
                    trim($Datos->MONTO),
                    trim($Datos->FECFAC),
                    trim($Datos->IDPRO),
                    trim($Datos->DESPRO),
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
        
            self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuariox = $xml->createElement('USU',session('nombre_usuario'));
            $usuariox =$root->appendChild($usuariox);  
            
            $rolxml = $xml->createElement('NIVEL',session('rol'));
            $rolxml =$root->appendChild($rolxml);  
            
            $serienumxml = $xml->createElement('FACT', strtoupper($request['serie_num']));
            $serienumxml =$root->appendChild($serienumxml);
            
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
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['FACTURA']->IDFAC),
                trim($datos['FACTURA']->SERIE),
                trim($datos['FACTURA']->NUM),
                trim($datos['FACTURA']->MONTO),
                trim($datos['FACTURA']->FECFAC),
                trim($datos['FACTURA']->IDPRO),
                trim($datos['FACTURA']->DESPRO),   
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
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDFAC),
                    trim($Datos->SERIE),
                    trim($Datos->NUM),
                    trim($Datos->MONTO),
                    trim($Datos->FECFAC),
                    trim($Datos->IDPRO),
                    trim($Datos->DESPRO),
                );  
            }
            return response()->json($Lista);
        }
    }

}
