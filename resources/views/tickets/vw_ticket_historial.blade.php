@extends('principal.p_inicio')

@section('content')
<style>
.modal-body {
    max-height: calc(100vh - 210px);
    overflow-y: auto;
}  
hr {
    border: 1px solid #7A7878;
}
</style>
<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>
    <div class="masonry-item col-md-12">
        <div class="bgc-white p-20 bd">
            <h2 class="c-grey-900 text-center">HISTORIAL DE TICKETS</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="titulo" class="fw-500">BUSCAR POR TITULO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="titulo_ht" name="titulo" placeholder="ESCRIBIR TITULO DEL TICKET" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="fw-500">FECHA DESDE:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="fecha_desde_ht" placeholder="SELECCIONAR UNA FECHA" name="fecha_desde" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="fw-500">FECHA HASTA:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="fecha_hasta_ht" placeholder="SELECCIONAR UNA FECHA" name="fecha_hasta" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 29px;">
                        <button id="btn_buscar_historial" class="btn btn-danger btn-block" type="button"><i class="fa fa-search"></i> BUSCAR</button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_historial_tickets"></table>
                            <div id="paginador_tabla_historial_tickets"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="form-row" style="display:none;">
    <div class="form-group col-md-6">
        <button class="btn" id="btn_abrir_modal_historial" style="background-color:#D48411;color:white;" data-toggle="modal" data-target="#Modal_Ticket_Historial" data-backdrop="static" data-keyboard="false" type="button"></button>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Ticket_Historial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">INFORMACION TICKET</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cuerpo">
                <table class="table table-condensed table-striped table-bordered text-center">
                    <tr>
                        <th style="width: 40%;"><label for="titulo" class="fw-500">TITULO:</label></th>
                        <td><label class="bdc-grey-200" id="mdl_titulo"></label></td>
                    </tr>
                    <tr>
                        <th><label for="titulo" class="fw-500">ESTADO:</label></th>
                        <td><label class="bdc-grey-200" id="mdl_estado"></label></td>
                    </tr>
                    <tr>
                        <th><label for="titulo" class="fw-500">TIPO:</label></th>
                        <td><label class="bdc-grey-200" id="mdl_tipo"></label></td>
                    </tr>
                    <tr>
                        <th><label for="titulo" class="fw-500">AREA:</label></th>
                        <td><label class="bdc-grey-200" id="mdl_area"></label></td>
                    </tr>
                    <tr>
                        <th><label for="titulo" class="fw-500">PRIORIDAD:</label></th>
                        <td><label class="bdc-grey-200" id="mdl_prioridad"></label></td>
                    </tr>
                    <tr>
                        <th><label for="titulo" class="fw-500">FECHA CREACION:</label></th>
                        <td><label class="bdc-grey-200" id="mdl_fecha_creacion_cab"></label></td>
                    </tr>
                    <tr>
                        <th><label for="titulo" class="fw-500">FECHA ACTUALIZACION:</label></th>
                        <td><label class="bdc-grey-200" id="mdl_fecha_actualizacion"></label></td>
                    </tr>
                </table>
                <hr>
                <center><h3>RESPUESTAS</h3></center>
                <div class="form-row" id="detalle">
                    <!-- DATOS DINAMICOS -->
                </div>
            </div>
            <div class="modal-footer">
                @if(session('rol') == 1 || session('rol') == 2)
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal_Info_Encuesta" data-backdrop="static" data-keyboard="false" id="btn_ver_info_encuesta">VER RESULTADO ENCUESTA</button>
                @else
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_sesion">CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL HISTORIAL ENCUESTA -->

<div class="modal fade" id="Modal_Info_Encuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">INFORMACION ENCUESTA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row" id="info_encuesta">
                    
                </div>
                <hr>
                <div class="form-group">
                    <label for="editor" class="fw-500"><b>¿PORQUE MOTIVOS RESPONDISTE DE ESTA MANERA?</b></label><br>
                    <label class="bdc-grey-200" id="mdl_obsr_encuesta"></label>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrar_ventana_encuesta">CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/tickets/ticket_historial.js') }}"></script>
<script>
function ver_ticket_historial(id_ticket)
{
    $.ajax({
        url: 'tickethistorial/'+id_ticket+'?show=traer_ticket',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION...');  
        },
        success: function(data) 
        {
            if (data.salida == '00000') 
            {
                $('#btn_abrir_modal_historial').click();
                html="";
    //            console.log(data.datos);
                $('#mdl_titulo').text(data.asunto);
                $('#mdl_estado').text(data.estado);
                $('#mdl_tipo').text(data.tipo);
                $('#mdl_area').text(data.area);
                $('#mdl_prioridad').text(data.prioridad);
                $('#mdl_fecha_creacion_cab').text(data.fecha_creacion);
                $('#mdl_fecha_actualizacion').text(data.fecha_actualizada);

                if (data.respuesta == 1) 
                {
                    var linea_tiempo1 = (tecnico.indexOf(data.datos.USUID) == 0) ? "timeline1" : "timeline2";
                    if (data.datos.ARCH != '-') 
                    {
                        html = html +   '<ul class="'+linea_tiempo1+'">\n\
                                            <li>\n\
                                                <div class="row">\n\
                                                    <h5 class="col-md-6"><b> '+data.datos.USUNOM+' </b></h5>\n\
                                                    <h5 class="col-md-6 text-right">'+data.datos.FECCRE+'</h5>\n\
                                                </div>\n\
                                                <label class="texto-completo">'+data.datos.TEXT+'</label>\n\
                                            </li>\n\
                                            <div class="form-group col-md-6"><a class="btn btn-danger btn-sm btn-block" style="text-decoration: none;" href=descargar/'+data.datos.IDRESP+' ><span class="btn-label"><i class="fa fa-print"></i></span> DESCARGAR</a></div>\n\
                                            <hr>\n\
                                        </ul>';
                    }
                    else
                    {
                        html = html +   '<ul class="'+linea_tiempo1+'">\n\
                                            <li>\n\
                                                <div class="row">\n\
                                                    <h5 class="col-md-6"><b> '+data.datos.USUNOM+' </b></h5>\n\
                                                    <h5 class="col-md-6 text-right">'+data.datos.FECCRE+'</h5>\n\
                                                </div>\n\
                                                <label class="texto-completo">'+data.datos.TEXT+'</label>\n\
                                            </li>\n\
                                            <hr>\n\
                                        </ul>';
                    }
                }
                else
                {
                    for(i=0;i<data.datos.length;i++)
                    {
                        var linea_tiempo2 = (tecnico.indexOf(data.datos[i].USUID) == 0) ? "timeline1" : "timeline2";
                        if (data.datos[i].ARCH != '-') 
                        {
                            html = html +   '<ul class="'+linea_tiempo2+'">\n\
                                                <li>\n\
                                                    <div class="row">\n\
                                                        <h5 class="col-md-6"><b> '+data.datos[i].USUNOM+' </b></h5>\n\
                                                        <h5 class="col-md-6 text-right">'+data.datos[i].FECCRE+'</h5>\n\
                                                    </div>\n\
                                                    <label class="texto-completo">'+data.datos[i].TEXT+'</label>\n\
                                                </li>\n\
                                                <div class="form-group col-md-6"><a class="btn btn-danger btn-sm btn-block" style="text-decoration: none;" href=descargar/'+data.datos[i].IDRESP+' ><span class="btn-label"><i class="fa fa-print"></i></span> DESCARGAR</a></div>\n\
                                                <hr>\n\
                                            </ul>';
                        }
                        else
                        {
                            html = html +   '<ul class="'+linea_tiempo2+'">\n\
                                                <li>\n\
                                                    <div class="row">\n\
                                                        <h5 class="col-md-6"><b> '+data.datos[i].USUNOM+' </b></h5>\n\
                                                        <h5 class="col-md-6 text-right">'+data.datos[i].FECCRE+'</h5>\n\
                                                    </div>\n\
                                                    <label class="texto-completo">'+data.datos[i].TEXT+'</label>\n\
                                                </li>\n\
                                                <hr>\n\
                                            </ul>';
                        }
                    }
                }
                setTimeout(function (){
                    $('.modal-body').scrollTop(0);
                }, 200);
                $("#detalle").html(html);
                swal.close();
            }
            else if(data == '90006')
            {
                MensajeAdvertencia('SE DEBE ASIGNAR PRIMERO EL TICKET');
            }
            else
            {
                MensajeAdvertencia('NO SE PUDO OBTENER RESPUESTA');
                console.log(data);
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}
</script>
@stop
@endsection
