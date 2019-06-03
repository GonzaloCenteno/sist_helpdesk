<?php

namespace App\Http\Controllers\soap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProveedorExport;
use App\Exports\EvaluacionExport;
use App\Exports\IncidentesExport;
use App\Exports\CalificacionExport;
use App\Exports\InventarioExport;

class Reportes_Controller extends BaseSoapController
{
    private $service;
    public function index(Request $request)
    {
        if ($request->session()->has('id_usuario'))
        {
            $menu = DB::table('permisos.vw_rol_menu_usuario')->where([['ume_usuario',session('id_usuario')],['sist_id',session('sist_id')]])->orderBy('ume_orden','asc')->get();
            $permiso = DB::table('permisos.vw_rol_submenu_usuario')->where([['usm_usuario',session('id_usuario')],['sist_id',session('sist_id')],['sme_sistema','li_config_rep_gerenciales'],['btn_view',1]])->get();
            if ($permiso->count() == 0) 
            {
                return view('errors/vw_sin_permiso',compact('menu'));
            }
            return view('reportes/vw_reportes',compact('menu','permiso')); 
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
            if ($request['show'] == 'lista_proveedores') 
            {
                return $this->abrir_reporte_lista_proveedores($request);
            }
            if ($request['show'] == 'lista_proveedores_excel') 
            {
                return $this->abrir_reporte_lista_proveedores_excel($request);
            }
            if ($request['show'] == 'evaluaciones') 
            {
                return $this->abrir_reporte_evaluaciones($request);
            }
            if ($request['show'] == 'evaluaciones_excel') 
            {
                return $this->abrir_reporte_evaluaciones_excel($request);
            }
            if ($request['show'] == 'incidentes') 
            {
                return $this->abrir_reporte_gestion_incidentes($request);
            }
            if ($request['show'] == 'incidentes_excel') 
            {
                return $this->abrir_reporte_gestion_incidentes_excel($request);
            }
            if ($request['show'] == 'calificacion') 
            {
                return $this->abrir_reporte_registro_calificacion($request);
            }
            if ($request['show'] == 'calificacion_excel') 
            {
                return $this->abrir_reporte_registro_calificacion_excel($request);
            }
            if ($request['show'] == 'inventario') 
            {
                return $this->abrir_reporte_gestion_inventario($request);
            }
            if ($request['show'] == 'inventario_excel') 
            {
                return $this->abrir_reporte_gestion_inventario_excel($request);
            }
        }
    }
    
    public function abrir_reporte_lista_proveedores(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            self::setWsdl();
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuarioxml = $xml->createElement('USU',session('id_usuario'));
            $usuarioxml =$root->appendChild($usuarioxml);  

            $fecinixml = $xml->createElement('FECINI', $request['fecha_inicio']);
            $fecinixml =$root->appendChild($fecinixml);
            
            $fecfinxml = $xml->createElement('FECFIN', $request['fecha_fin']);
            $fecfinxml =$root->appendChild($fecfinxml);

            $xml->formatOutput = true;

            $codigo = '054';
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
            //dd($datos['PROVEEDOR']);
            if($datos['CODERR'] == "00000")
            {
                $proveedores = $datos['PROVEEDOR'];
                $view = \View::make('reportes.pdf.vw_listar_proveedores',compact('proveedores'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                return $pdf->stream("LISTA DE PROVEEDORES".".pdf");
            }
            else
            {
                return $datos['MSGERR'];
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }
    
    public function abrir_reporte_lista_proveedores_excel(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            self::setWsdl();
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuarioxml = $xml->createElement('USU',session('id_usuario'));
            $usuarioxml =$root->appendChild($usuarioxml);  

            $fecinixml = $xml->createElement('FECINI', $request['fecha_inicio']);
            $fecinixml =$root->appendChild($fecinixml);
            
            $fecfinxml = $xml->createElement('FECFIN', $request['fecha_fin']);
            $fecfinxml =$root->appendChild($fecfinxml);

            $xml->formatOutput = true;

            $codigo = '054';
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
            //dd($datos['PROVEEDOR']);
            if($datos['CODERR'] == "00000")
            {
                $proveedores = $datos['PROVEEDOR'];
                return Excel::download(new ProveedorExport($proveedores), 'LISTA DE PROVEEDORES.xlsx');
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doc->saveHtml(),true);
            }
            else
            {
                return $datos['MSGERR'];
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }
    
    public function abrir_reporte_evaluaciones(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            self::setWsdl();
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuarioxml = $xml->createElement('USU',session('id_usuario'));
            $usuarioxml =$root->appendChild($usuarioxml);  

            $fecinixml = $xml->createElement('FECINI', $request['fecha_inicio']);
            $fecinixml =$root->appendChild($fecinixml);
            
            $fecfinxml = $xml->createElement('FECFIN', $request['fecha_fin']);
            $fecfinxml =$root->appendChild($fecfinxml);

            $xml->formatOutput = true;

            $codigo = '053';
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
            //dd($datos['CALIFICACION']);
            if($datos['CODERR'] == "00000")
            {
                //dd($datos);
                if ($datos['NUMTIC'] != 0) 
                {
                    $count = $datos['NUMTIC'];
                    $calificacion = $datos['CALIFICACION'];
                    $view = \View::make('reportes.pdf.vw_evaluaciones',compact('calificacion','count'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view)->setPaper('a4','landscape');
                    return $pdf->stream("EVALUACION DE PROVEEDORES".".pdf");
                }
                else
                {
                    return "NO SE ENCONTRARON DATOS";
                }
            }
            else
            {
                return $datos['MSGERR'];
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }  
    }
    
    public function abrir_reporte_evaluaciones_excel(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            self::setWsdl();
            $this->service = InstanceSoapClient::init();

            $xml = new \DomDocument('1.0', 'UTF-8'); 
            $root = $xml->createElement('CROMOHELP'); 
            $root = $xml->appendChild($root); 

            $usuarioxml = $xml->createElement('USU',session('id_usuario'));
            $usuarioxml =$root->appendChild($usuarioxml);  

            $fecinixml = $xml->createElement('FECINI', $request['fecha_inicio']);
            $fecinixml =$root->appendChild($fecinixml);
            
            $fecfinxml = $xml->createElement('FECFIN', $request['fecha_fin']);
            $fecfinxml =$root->appendChild($fecfinxml);

            $xml->formatOutput = true;

            $codigo = '053';
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
            //dd($datos['CALIFICACION']);
            if($datos['CODERR'] == "00000")
            {
                if ($datos['NUMTIC'] != 0) 
                {
                    $count = $datos['NUMTIC'];
                    $calificacion = $datos['CALIFICACION'];
                    return Excel::download(new EvaluacionExport($calificacion,$count), 'EVALUACIÓN DE PROVEEDORES.xlsx');
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doc->saveHtml(),true);
                }
                else
                {
                    return "NO SE ENCONTRARON DATOS";
                }
            }
            else
            {
                return $datos['MSGERR'];
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }
    
    public function abrir_reporte_gestion_incidentes(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            $datos = DB::select("select suba_desc,cabt_feccre::date as fecha,cabt_feccre::date || ' ' ||TO_CHAR(cabt_feccre,'HH24:MI:SS') AS fec_completa ,are_desc,cabt_asunto,desc_est,cabt_usucre,pvt_desc,cabt_usutec ,prio_desc
                                    from cromohelp.tbl_cabticket
                                    inner join cromohelp.tbl_estado on id_est=cabt_est
                                    inner join cromohelp.tbl_area on are_id=cabt_carea
                                    inner join cromohelp.tbl_subarea on cabt_subarea = suba_id
                                    inner join cromohelp.tbl_pvt on pvt_id=cabt_pvt
                                    inner join cromohelp.tbl_prioridad on prio_id=cabt_pri where cabt_feccre::date between '".$request['fecha_inicio']."' and '".$request['fecha_fin']."' order by cabt_feccre,are_desc,suba_desc asc");
            if (count($datos) > 0) 
            {
                $view = \View::make('reportes.pdf.vw_gestion_incidentes',compact('datos'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                return $pdf->stream("REGISTRO DE GESTION DE INCIDENTES".".pdf");
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }
    
    public function abrir_reporte_gestion_incidentes_excel(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            $datos = DB::select("select suba_desc,cabt_feccre::date as fecha,cabt_feccre::date || ' ' ||TO_CHAR(cabt_feccre,'HH24:MI:SS') AS fec_completa ,are_desc,cabt_asunto,desc_est,cabt_usucre,pvt_desc,cabt_usutec ,prio_desc
                                    from cromohelp.tbl_cabticket
                                    inner join cromohelp.tbl_estado on id_est=cabt_est
                                    inner join cromohelp.tbl_area on are_id=cabt_carea
                                    inner join cromohelp.tbl_subarea on cabt_subarea = suba_id
                                    inner join cromohelp.tbl_pvt on pvt_id=cabt_pvt
                                    inner join cromohelp.tbl_prioridad on prio_id=cabt_pri where cabt_feccre::date between '".$request['fecha_inicio']."' and '".$request['fecha_fin']."' order by cabt_feccre,are_desc,suba_desc asc");
            if (count($datos) > 0) 
            {
                return Excel::download(new IncidentesExport($datos), 'INCIDENTES.xlsx');
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doc->saveHtml(),true);
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }
    
    public function abrir_reporte_registro_calificacion(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            $preguntas = DB::table('cromohelp.tbl_preguntas')->select('pre_id','pre_desc')->where('pre_est',5)->orderBy('pre_id','asc')->get();
            
            $enteros='';
            foreach ($preguntas as $pregun){
                $enteros .=  '"'.$pregun->pre_id.'" INT,';
            }
            $var = trim($enteros,',');
            
            $datos = DB::select("SELECT distinct cabt_id,cabt_asunto,cab.cabt_usutec,cab.cabt_usucre,encuesta.* FROM CROSSTAB
                                    (
                                    'SELECT a.cabt_usucre,c.pre_id,b.enc_idvalor FROM cromohelp.tbl_cabticket a
                                    inner join cromohelp.tbl_encuesta b on a.cabt_id = b.enc_idcab
                                    inner join cromohelp.tbl_preguntas c on b.enc_idpreg = c.pre_id order by c.pre_id asc'
                                    )AS encuesta (cabt_usucre text,$var)
                                    inner join cromohelp.tbl_cabticket cab on 1=1
                                    inner join cromohelp.tbl_encuesta enc on enc_idcab=cabt_id
                                    inner join cromohelp.tbl_preguntas pre on enc_idpreg=pre_id where enc.enc_fecha::date between '".$request['fecha_inicio']."' and '".$request['fecha_fin']."' ");
            if (count($datos) > 0) 
            {
                $view = \View::make('reportes.pdf.vw_registro_calificacion',compact('datos','preguntas'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                return $pdf->stream("REGISTRO DE CALIFICACIONES".".pdf");
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }
    
    public function abrir_reporte_registro_calificacion_excel(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            $preguntas = DB::table('cromohelp.tbl_preguntas')->select('pre_id','pre_desc')->where('pre_est',5)->orderBy('pre_id','asc')->get();
            
            $enteros='';
            foreach ($preguntas as $pregun){
                $enteros .=  '"'.$pregun->pre_id.'" INT,';
            }
            $var = trim($enteros,',');
            
            $datos = DB::select("SELECT distinct cabt_id,cabt_asunto,cab.cabt_usutec,cab.cabt_usucre,encuesta.* FROM CROSSTAB
                                    (
                                    'SELECT a.cabt_usucre,c.pre_id,b.enc_idvalor FROM cromohelp.tbl_cabticket a
                                    inner join cromohelp.tbl_encuesta b on a.cabt_id = b.enc_idcab
                                    inner join cromohelp.tbl_preguntas c on b.enc_idpreg = c.pre_id order by c.pre_id asc'
                                    )AS encuesta (cabt_usucre text,$var)
                                    inner join cromohelp.tbl_cabticket cab on 1=1
                                    inner join cromohelp.tbl_encuesta enc on enc_idcab=cabt_id
                                    inner join cromohelp.tbl_preguntas pre on enc_idpreg=pre_id where enc.enc_fecha::date between '".$request['fecha_inicio']."' and '".$request['fecha_fin']."' ");
            if (count($datos) > 0) 
            {
                return Excel::download(new CalificacionExport($datos,$preguntas), 'CALIFICACIONES.xlsx');
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doc->saveHtml(),true);
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }
    
    public function abrir_reporte_gestion_inventario(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            $datos = DB::select("select a.item_desc,a.item_ser,a.item_cant,a.item_fec,b.mar_desc,c.pro_raz,c.pro_ruc,c.pro_tel from cromohelp.tbl_item a
                                inner join cromohelp.tbl_marcas b on a.mar_id = b.mar_id and b.mar_est = 5
                                inner join cromohelp.tbl_proveedor c on a.pro_id = c.pro_id and c.pro_est = 5
                                where a.item_fec between '".$request['fecha_inicio']."' and '".$request['fecha_fin']."'
                                order by a.item_fec asc");
            
            if (count($datos) > 0) 
            {
                $view = \View::make('reportes.pdf.vw_gestion_inventario',compact('datos'))->render();
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadHTML($view)->setPaper('a4','landscape');
                return $pdf->stream("GESTION DE INVENTARIO".".pdf");
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }
    
    public function abrir_reporte_gestion_inventario_excel(Request $request)
    {
        if ($request->session()->has('id_usuario') && session('sro_id') == 1 || session('sro_id') == 2)
        {
            $datos = DB::select("select a.item_desc,a.item_ser,a.item_cant,a.item_fec,b.mar_desc,c.pro_raz,c.pro_ruc,c.pro_tel from cromohelp.tbl_item a
                                inner join cromohelp.tbl_marcas b on a.mar_id = b.mar_id and b.mar_est = 5
                                inner join cromohelp.tbl_proveedor c on a.pro_id = c.pro_id and c.pro_est = 5
                                where a.item_fec between '".$request['fecha_inicio']."' and '".$request['fecha_fin']."'
                                order by a.item_fec asc");
            
            if (count($datos) > 0) 
            {
                return Excel::download(new InventarioExport($datos), 'INVENTARIO.xlsx');
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $doc->saveHtml(),true);
            }
            else
            {
                return "NO SE ENCONTRARON DATOS";
            }
        }
        else
        {
            return view('errors/vw_sin_acceso');
        }
    }

}
