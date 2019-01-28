<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tbl_marcas;
use Validator;

class Marca_Controller extends BaseSoapController
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
                return view('inventario/vw_marcas',compact('tblmenu_men','tblmenu_men2','tblmenu_men3'));
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
            if ($request['grid'] == 'marcas') 
            {
                return $this->crear_tabla_marcas($request);
            }
            if ($request['grid'] == 'buscar_marcas') 
            {
                return $this->crear_tabla_buscar_marcas($request);
            }
            if ($request['validar'] == 'validar_marcas') 
            {
                return $this->validar_buscar_marcas($request);
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

        $desxml = $xml->createElement('DES', strtoupper($request['descripcion']));
        $desxml =$root->appendChild($desxml);

        $xml->formatOutput = true;

        $codigo = '017';
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

    public function edit($id_marca,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->modificar_datos_marcas($id_marca, $request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->cambiar_estado_marcas($id_marca, $request);
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        
    }
    
    public function crear_tabla_marcas(Request $request)
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

            $codigo = '027';
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
            $Lista->rows[0]['id'] = (integer)$datos['MARCA']->IDMAR;
            if ($datos['MARCA']->IDEST == 5) {
                $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_marca('.trim((integer)$datos['MARCA']->IDMAR).',6)"><i class="fa fa-check"></i> '.$datos['MARCA']->ESTMAR.'</button>';
            }else{
                $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_marca('.trim((integer)$datos['MARCA']->IDMAR).',5)"><i class="fa fa-times"></i> '.$datos['MARCA']->ESTMAR.'</button>'; 
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['MARCA']->IDMAR),
                trim($datos['MARCA']->DESMAR),
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
           foreach ($datos['MARCA'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDMAR;
                if ($Datos->IDEST == 5) {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_marca('.trim((integer)$Datos->IDMAR).',6)"><i class="fa fa-check"></i> '.$Datos->ESTMAR.'</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_marca('.trim((integer)$Datos->IDMAR).',5)"><i class="fa fa-times"></i> '.$Datos->ESTMAR.'</button>'; 
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDMAR),
                    trim($Datos->DESMAR),
                    $nuevo,
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function modificar_datos_marcas($id_marca, Request $request)
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('nombre_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $idmarcaxml = $xml->createElement('IDM', $id_marca);
        $idmarcaxml =$root->appendChild($idmarcaxml);
        
        $descripcionxml = $xml->createElement('DES', strtoupper($request['descripcion']));
        $descripcionxml =$root->appendChild($descripcionxml);

        $xml->formatOutput = true;

        $codigo = '022';
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
    
    public function cambiar_estado_marcas($id_marca, Request $request)
    {
        $Tbl_marcas = new Tbl_marcas;
        $val=  $Tbl_marcas::where("mar_id","=",$id_marca)->first();
        if($val)
        {
            $val->mar_est = $request['estado'];
            $val->save();
        }
        return $id_marca;
    }
    
    public function validar_buscar_marcas(Request $request)
    {
        $validar = DB::table('cromohelp.tbl_marcas')->where('mar_desc','like', '%'.strtoupper($request['descripcion']).'%')->get();
        if ($validar->count() > 0) 
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    public function crear_tabla_buscar_marcas(Request $request)
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
            
            $descripcionxml = $xml->createElement('DES', strtoupper($request['descripcion']));
            $descripcionxml =$root->appendChild($descripcionxml); 
            
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

            $codigo = '028';
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
            $Lista->rows[0]['id'] = (integer)$datos['MARCA']->IDMAR;
            if ($datos['MARCA']->IDEST == 5) {
                $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_marca('.trim((integer)$datos['MARCA']->IDMAR).',6)"><i class="fa fa-check"></i> '.$datos['MARCA']->ESTMAR.'</button>';
            }else{
                $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_marca('.trim((integer)$datos['MARCA']->IDMAR).',5)"><i class="fa fa-times"></i> '.$datos['MARCA']->ESTMAR.'</button>'; 
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['MARCA']->IDMAR),
                trim($datos['MARCA']->DESMAR),
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
           foreach ($datos['MARCA'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDMAR;
                if ($Datos->IDEST == 5) {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_marca('.trim((integer)$Datos->IDMAR).',6)"><i class="fa fa-check"></i> '.$Datos->ESTMAR.'</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_marca('.trim((integer)$Datos->IDMAR).',5)"><i class="fa fa-times"></i> '.$Datos->ESTMAR.'</button>'; 
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDMAR),
                    trim($Datos->DESMAR),
                    $nuevo,
                );  
            }
            return response()->json($Lista);
        }
    }

}
