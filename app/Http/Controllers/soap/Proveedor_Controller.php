<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tbl_proveedor;
use Validator;

class Proveedor_Controller extends BaseSoapController
{
    private $service;
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_proveedores'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('inventario/vw_proveedores',compact('menu','permiso')); 
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
            if ($request['grid'] == 'proveedores') 
            {
                return $this->crear_tabla_proveedores($request);
            }
            if ($request['grid'] == 'buscar_proveedores') 
            {
                return $this->crear_tabla_buscar_proveedores($request);
            }
            if ($request['validar'] == 'validar_proveedores') 
            {
                return $this->validar_buscar_proveedores($request);
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

        $rsocialxml = $xml->createElement('RAZ', strtoupper($request['razon_social']));
        $rsocialxml =$root->appendChild($rsocialxml);
        
        $rucxml = $xml->createElement('RUC', $request['ruc']);
        $rucxml =$root->appendChild($rucxml);
        
        $telefonoxml = $xml->createElement('TEL', $request['telefono']);
        $telefonoxml =$root->appendChild($telefonoxml);
        
        $contactoxml = $xml->createElement('CON', strtoupper($request['contacto']));
        $contactoxml =$root->appendChild($contactoxml);
        
        $direccionxml = $xml->createElement('DIR', strtoupper($request['direccion']));
        $direccionxml =$root->appendChild($direccionxml);
        
        $servicioxml = $xml->createElement('SER', strtoupper($request['servicio']));
        $servicioxml =$root->appendChild($servicioxml);
        
        $correoxml = $xml->createElement('COR', strtoupper($request['correo']));
        $correoxml =$root->appendChild($correoxml);

        $xml->formatOutput = true;

        $codigo = '018';
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

    public function edit($id_proveedor,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->editar_datos_proveedores($id_proveedor, $request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->cambiar_estado_proveedores($id_proveedor, $request);
        } 
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        
    }
    
    public function crear_tabla_proveedores(Request $request)
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

            $codigo = '029';
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
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_proveedores'],['btn_view',1]])->get();
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
            $Lista->rows[0]['id'] = (integer)$datos['PROVEEDOR']->IDPRO;
            if ($permiso[0]->btn_del == 1) 
            {
                if ($datos['PROVEEDOR']->IDEST == 5) {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_proveedor('.trim((integer)$datos['PROVEEDOR']->IDPRO).',6)"><i class="fa fa-check"></i> '.$datos['PROVEEDOR']->ESTPRO.'</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_proveedor('.trim((integer)$datos['PROVEEDOR']->IDPRO).',5)"><i class="fa fa-times"></i> '.$datos['PROVEEDOR']->ESTPRO.'</button>'; 
                }
            }
            else
            {
                if ($datos['PROVEEDOR']->IDEST == 5) {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> '.$datos['PROVEEDOR']->ESTPRO.'</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> '.$datos['PROVEEDOR']->ESTPRO.'</button>'; 
                }
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['PROVEEDOR']->IDPRO),
                trim($datos['PROVEEDOR']->RAZSOC),
                trim($datos['PROVEEDOR']->RUCPRO),
                trim($datos['PROVEEDOR']->TELPRO),
                trim($datos['PROVEEDOR']->CONPRO),
                trim($datos['PROVEEDOR']->DIRPRO),
                trim($datos['PROVEEDOR']->SERVPRO),
                trim($datos['PROVEEDOR']->CORRPRO),
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
           foreach ($datos['PROVEEDOR'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDPRO;
                if ($permiso[0]->btn_del == 1) 
                {
                    if ($Datos->IDEST == 5) {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_proveedor('.trim((integer)$Datos->IDPRO).',6)"><i class="fa fa-check"></i> '.$Datos->ESTPRO.'</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_proveedor('.trim((integer)$Datos->IDPRO).',5)"><i class="fa fa-times"></i> '.$Datos->ESTPRO.'</button>'; 
                    }
                }
                else
                {
                    if ($Datos->IDEST == 5) {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> '.$Datos->ESTPRO.'</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> '.$Datos->ESTPRO.'</button>'; 
                    }
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDPRO),
                    trim($Datos->RAZSOC),
                    trim($Datos->RUCPRO),
                    trim($Datos->TELPRO),
                    trim($Datos->CONPRO),
                    trim($Datos->DIRPRO),
                    trim($Datos->SERVPRO),
                    trim($Datos->CORRPRO),
                    $nuevo,
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function editar_datos_proveedores($id_proveedor, Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('id_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $idproveedorxml = $xml->createElement('IDP', $id_proveedor);
        $idproveedorxml =$root->appendChild($idproveedorxml);
        
        $razonsocialxml = $xml->createElement('RAZ', strtoupper($request['razon_social']));
        $razonsocialxml =$root->appendChild($razonsocialxml);
        
        $rucxml = $xml->createElement('RUC', $request['ruc']);
        $rucxml =$root->appendChild($rucxml);
        
        $telefonoxml = $xml->createElement('TEL', $request['telefono']);
        $telefonoxml =$root->appendChild($telefonoxml);
        
        $contactoxml = $xml->createElement('CON', strtoupper($request['contacto']));
        $contactoxml =$root->appendChild($contactoxml);
        
        $direccionxml = $xml->createElement('DIR', strtoupper($request['direccion']));
        $direccionxml =$root->appendChild($direccionxml);
        
        $servicioxml = $xml->createElement('SER', strtoupper($request['servicio']));
        $servicioxml =$root->appendChild($servicioxml);
        
        $correoxml = $xml->createElement('COR', strtoupper($request['correo']));
        $correoxml =$root->appendChild($correoxml);

        $xml->formatOutput = true;

        $codigo = '023';
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
    
    public function cambiar_estado_proveedores($id_proveedor, Request $request)
    {
        $Tbl_proveedor = new Tbl_proveedor;
        $val=  $Tbl_proveedor::where("pro_id","=",$id_proveedor)->first();
        if($val)
        {
            $val->pro_est = $request['estado'];
            $val->save();
        }
        return $id_proveedor;
    }
    
    public function validar_buscar_proveedores(Request $request)
    {
        $validar = DB::table('cromohelp.tbl_proveedor')->where('pro_raz','like', '%'.strtoupper($request['razon_social']).'%')->get();
        if ($validar->count() > 0) 
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    public function crear_tabla_buscar_proveedores(Request $request)
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
            
            $razonsocialxml = $xml->createElement('RAZ', strtoupper($request['razon_social']));
            $razonsocialxml =$root->appendChild($razonsocialxml); 
            
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

            $codigo = '030';
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
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_proveedores'],['btn_view',1]])->get();
            
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
            $Lista->rows[0]['id'] = (integer)$datos['PROVEEDOR']->IDPRO;
            if ($permiso[0]->btn_del == 1) 
            {
                if ($datos['PROVEEDOR']->IDEST == 5) {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_proveedor('.trim((integer)$datos['PROVEEDOR']->IDPRO).',6)"><i class="fa fa-check"></i> '.$datos['PROVEEDOR']->ESTPRO.'</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_proveedor('.trim((integer)$datos['PROVEEDOR']->IDPRO).',5)"><i class="fa fa-times"></i> '.$datos['PROVEEDOR']->ESTPRO.'</button>'; 
                }
            }
            else
            {
                if ($datos['PROVEEDOR']->IDEST == 5) {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> '.$datos['PROVEEDOR']->ESTPRO.'</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> '.$datos['PROVEEDOR']->ESTPRO.'</button>'; 
                }
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['PROVEEDOR']->IDPRO),
                trim($datos['PROVEEDOR']->RAZSOC),
                trim($datos['PROVEEDOR']->RUCPRO),
                trim($datos['PROVEEDOR']->TELPRO),
                trim($datos['PROVEEDOR']->CONPRO),
                trim($datos['PROVEEDOR']->DIRPRO),
                trim($datos['PROVEEDOR']->SERVPRO),
                trim($datos['PROVEEDOR']->CORRPRO),
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
           foreach ($datos['PROVEEDOR'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDPRO;
                if ($permiso[0]->btn_del == 1) 
                {
                    if ($Datos->IDEST == 5) {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_proveedor('.trim((integer)$Datos->IDPRO).',6)"><i class="fa fa-check"></i> '.$Datos->ESTPRO.'</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_proveedor('.trim((integer)$Datos->IDPRO).',5)"><i class="fa fa-times"></i> '.$Datos->ESTPRO.'</button>'; 
                    }
                }
                else
                {
                    if ($Datos->IDEST == 5) {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> '.$Datos->ESTPRO.'</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> '.$Datos->ESTPRO.'</button>'; 
                    }
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDPRO),
                    trim($Datos->RAZSOC),
                    trim($Datos->RUCPRO),
                    trim($Datos->TELPRO),
                    trim($Datos->CONPRO),
                    trim($Datos->DIRPRO),
                    trim($Datos->SERVPRO),
                    trim($Datos->CORRPRO),
                    $nuevo,
                );  
            }
            return response()->json($Lista);
        }
    }

}
