<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tbl_preguntas;
use Validator;

class Preguntas_Controller extends BaseSoapController
{
    private $service;
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')],['ume_estado',1]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_preguntas'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('encuesta/vw_preguntas',compact('menu','permiso'));
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
            if ($request['grid'] == 'preguntas') 
            {
                return $this->crear_tabla_preguntas($request);
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

        $desxml = $xml->createElement('DES', strtoupper($request['descripcion']));
        $desxml =$root->appendChild($desxml);

        $xml->formatOutput = true;

        $codigo = '046';
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

    public function edit($id_pregunta,Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->modificar_datos_pregunta($id_pregunta, $request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->editar_estado_pregunta($id_pregunta, $request);
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        
    }
    
    public function crear_tabla_preguntas(Request $request)
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

            $codigo = '048';
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
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_preguntas'],['btn_view',1]])->get();
            
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
            $Lista->rows[0]['id'] = (integer)$datos['PREGUNTA']->IDPRE;
            if ($permiso[0]->btn_del == 1) 
            {
                if ($datos['PREGUNTA']->IDEST == 5) {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_pregunta('.trim((integer)$datos['PREGUNTA']->IDPRE).',6)"><i class="fa fa-check"></i> '.$datos['PREGUNTA']->ESTPRE.'</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_pregunta('.trim((integer)$datos['PREGUNTA']->IDPRE).',5)"><i class="fa fa-times"></i> '.$datos['PREGUNTA']->ESTPRE.'</button>'; 
                }
            }
            else
            {
                if ($datos['PREGUNTA']->IDEST == 5) {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> '.$datos['PREGUNTA']->ESTPRE.'</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> '.$datos['PREGUNTA']->ESTPRE.'</button>'; 
                }
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['PREGUNTA']->IDPRE),
                trim($datos['PREGUNTA']->DESPRE),
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
           foreach ($datos['PREGUNTA'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDPRE;
                if ($permiso[0]->btn_del == 1) 
                {
                    if ($Datos->IDEST == 5) {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_pregunta('.trim((integer)$Datos->IDPRE).',6)"><i class="fa fa-check"></i> '.$Datos->ESTPRE.'</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_pregunta('.trim((integer)$Datos->IDPRE).',5)"><i class="fa fa-times"></i> '.$Datos->ESTPRE.'</button>'; 
                    }
                }
                else
                {
                    if ($Datos->IDEST == 5) {
                        $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="sin_permiso();"><i class="fa fa-check"></i> '.$Datos->ESTPRE.'</button>';
                    }else{
                        $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="sin_permiso();"><i class="fa fa-times"></i> '.$Datos->ESTPRE.'</button>'; 
                    }
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDPRE),
                    trim($Datos->DESPRE),
                    $nuevo,
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function modificar_datos_pregunta($id_pregunta, Request $request)
    {
        self::setWsdl();
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('id_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $idpreguntaxml = $xml->createElement('IDP', $id_pregunta);
        $idpreguntaxml =$root->appendChild($idpreguntaxml);
        
        $descripcionxml = $xml->createElement('DES', strtoupper($request['descripcion']));
        $descripcionxml =$root->appendChild($descripcionxml);

        $xml->formatOutput = true;

        $codigo = '047';
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
    
    public function editar_estado_pregunta($id_pregunta, Request $request)
    {
        $Tbl_preguntas = new Tbl_preguntas;
        $val=  $Tbl_preguntas::where("pre_id","=",$id_pregunta)->first();
        if($val)
        {
            $val->pre_est = $request['estado'];
            $val->save();
        }
        return $id_pregunta;
    }
    
}
