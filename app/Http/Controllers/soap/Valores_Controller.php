<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tbl_valores;

class Valores_Controller extends BaseSoapController
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
                return view('encuesta/vw_valores',compact('tblmenu_men','tblmenu_men2','tblmenu_men3'));
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
            if ($request['show'] == 'datos_valor') 
            {
                return $this->recuperar_datos_valor($id, $request);
            }
        }
        else
        {
            if ($request['grid'] == 'valores') 
            {
                return $this->crear_tabla_valores($request);
            }
        }
    }

    public function create(Request $request)
    {
        
    }

    public function edit($id_valor,Request $request)
    {
        $Tbl_valores = new Tbl_valores;
        $val=  $Tbl_valores::where("val_id","=",$id_valor)->first();
        if($val)
        {
            $val->val_est = $request['estado'];
            $val->save();
        }
        return $id_valor;
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->crear_registro_valor($request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->editar_registro_valor($request);
        }
    }
    
    public function crear_registro_valor(Request $request)
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('nombre_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  

        $desxml = $xml->createElement('DES', strtoupper($request['desc_valor']));
        $desxml =$root->appendChild($desxml);
        
        $file1 = $request->file('img1_valor');
        $file_1 = \File::get($file1);
        $img1xml = $xml->createElement('IMG1', base64_encode($file_1));
        $img1xml =$root->appendChild($img1xml);
        
        $file2 = $request->file('img2_valor');
        $file_2 = \File::get($file2);
        $img2xml = $xml->createElement('IMG2', base64_encode($file_2));
        $img2xml =$root->appendChild($img2xml);

        $xml->formatOutput = true;

        $codigo = '043';
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
    
    public function crear_tabla_valores(Request $request)
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

            $codigo = '045';
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
            $Lista->rows[0]['id'] = (integer)$datos['VALOR']->IDVAL;
            if ($datos['VALOR']->IDEST == 5) {
                $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_valor('.trim((integer)$datos['VALOR']->IDVAL).',6)"><i class="fa fa-check"></i> '.$datos['VALOR']->ESTVAL.'</button>';
            }else{
                $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_valor('.trim((integer)$datos['VALOR']->IDVAL).',5)"><i class="fa fa-times"></i> '.$datos['VALOR']->ESTVAL.'</button>'; 
            }
            $Lista->rows[0]['cell'] = array(
                trim((integer)$datos['VALOR']->IDVAL),
                trim($datos['VALOR']->DESVAL),
                trim($datos['VALOR']->IMG1VAL),
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
           foreach ($datos['VALOR'] as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = (integer)$Datos->IDVAL;
                if ($Datos->IDEST == 5) {
                    $nuevo = '<button class="btn btn-lg btn-success" type="button" onclick="cambiar_estado_valor('.trim((integer)$Datos->IDVAL).',6)"><i class="fa fa-check"></i> '.$Datos->ESTVAL.'</button>';
                }else{
                    $nuevo = '<button class="btn btn-lg btn-danger" type="button" onclick="cambiar_estado_valor('.trim((integer)$Datos->IDVAL).',5)"><i class="fa fa-times"></i> '.$Datos->ESTVAL.'</button>'; 
                }
                $Lista->rows[$Index]['cell'] = array(
                    trim((integer)$Datos->IDVAL),
                    trim($Datos->DESVAL),
                    trim($Datos->IMG1VAL),
                    $nuevo,
                );  
            }
            return response()->json($Lista);
        }
    }
    
    public function recuperar_datos_valor($id_valor, Request $request)
    {
        $datos = DB::table('cromohelp.tbl_valores')->select('val_desc','val_img','val_img2')->where('val_id',$id_valor)->get();
        return $datos;
    }
    
    public function editar_registro_valor(Request $request)
    {
        self::setWsdl('http://10.1.4.250:8080/WSCromoHelp/services/Cls_Listen?wsdl');
        $this->service = InstanceSoapClient::init();

        $xml = new \DomDocument('1.0', 'UTF-8'); 
        $root = $xml->createElement('CROMOHELP'); 
        $root = $xml->appendChild($root); 

        $usuarioxml = $xml->createElement('USU',session('nombre_usuario'));
        $usuarioxml =$root->appendChild($usuarioxml);  
        
        $idvalorxml = $xml->createElement('ID', $request['id_valor']);
        $idvalorxml =$root->appendChild($idvalorxml);

        $desxml = $xml->createElement('DES', strtoupper($request['desc_valor']));
        $desxml =$root->appendChild($desxml);
        
        $file1 = $request->file('img1_valor');
        $file_1 = \File::get($file1);
        $img1xml = $xml->createElement('IMG1', base64_encode($file_1));
        $img1xml =$root->appendChild($img1xml);
        
        $file2 = $request->file('img2_valor');
        $file_2 = \File::get($file2);
        $img2xml = $xml->createElement('IMG2', base64_encode($file_2));
        $img2xml =$root->appendChild($img2xml);

        $xml->formatOutput = true;

        $codigo = '044';
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

}
