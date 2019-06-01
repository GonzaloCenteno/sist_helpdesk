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
            <h2 class="c-grey-900 text-center">TICKET ASIGNADOS</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="titulo" class="fw-500">BUSCAR POR TITULO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="txt_titulo" name="txt_titulo" placeholder="ESCRIBIR TITULO DEL TICKET" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="fw-500">FECHA DESDE:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_fecha_desde" placeholder="SELECCIONAR UNA FECHA" name="txt_fecha_desde" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
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
                                <input type="text" class="form-control start-date rounded" id="txt_fecha_hasta" placeholder="SELECCIONAR UNA FECHA" name="txt_fecha_hasta" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 29px;">
                        <button id="btn_buscar_ticket_asignados" class="btn btn-danger btn-block" type="button"><i class="fa fa-search"></i> BUSCAR</button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_tickets_asignados"></table>
                            <div id="paginador_tabla_tickets_asignados"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Ticket_Asignados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">INFORMACION TICKET</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
         
                <div class="form-row text-center" id="caja_prioridad">
                    <!-- DATOS DINAMICOS -->
                </div>
                <hr>
           
                <center><h3>DETALLE - RESPUESTAS</h3></center>
                <div class="form-row" id="detalle">
                    <!-- DATOS DINAMICOS -->
                </div>
                <div class="form-group">
                    <label for="editor" class="fw-500">REGISTRAR NUEVA RESPUESTA:</label>
                    <textarea  id="mdl_nueva_descripcion" name="mdl_nueva_descripcion">INGRESE UNA DESCRIPCION</textarea >
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal">CERRAR VENTANA</button>
                @if(session('sro_id') == 1 || session('sro_id') == 2)
                    <button type="button" class="btn btn-danger" id="btn_rechazar_ticket">RECHAZAR TICKET</button>
                @else
                @endif
                <button type="button" class="btn btn-primary" id="btn_responder_ticket_asignados">RESPONDER TICKET</button>
                <button type="button" class="btn btn-success" id="btn_cerrar_ticket_asignados">CERRAR TICKET</button>
            </div>
        </div>
    </div>
</div>


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/tickets/ticket_asignados.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
function ver_ticket_asignados(id_ticket)
{
    CKEDITOR.instances['mdl_nueva_descripcion'].setData('INGRESAR UNA DESCRIPCION');
    $('#prioridad_asignados').val('1');
    
    $.ajax({
        url: 'ticketasignados/'+id_ticket+'?show=traer_ticket',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION...');  
        },
        success: function(data) 
        {
            if (data.salida == '00000') 
            {
                html="";
                datos = "";
    //            console.log(data.datos);
                $('#mdl_titulo').text(data.asunto);
                $('#mdl_estado').text(data.estado);
                $('#mdl_tipo').text(data.tipo);
                $('#mdl_area').text(data.area);
                $('#mdl_prioridad').text(data.prioridad);
                $('#mdl_fecha_creacion_cab').text(data.fecha_creacion);
                $('#mdl_fecha_actualizacion').text(data.fecha_actualizada);
                
                if(variable == 1 || variable == 2)
                {
                    if(data.prio_admin == 0)
                    {
                        datos = '<div class="form-group col-md-12"><h4>CAMBIAR PRIORIDAD</h4></div>'+
                                '<div class="form-group col-md-9">'+
                                    '<select id="prioridad_asignados" name="prioridad_asignados" class="form-control rounded">'+     
                                    @foreach($prioridad as $pr)          
                                        '<option value="{{ $pr->prio_id }}">{{ $pr->prio_desc }}</option>'+
                                    @endforeach
                                    '</select>'+
                                '</div>'+
                                '<div class="form-group col-md-3">'+
                                    '<button type="button" class="btn btn-primary" id="btn_prioridad_asignados">MODIFICAR</button>'+
                                '</div>';
                    }
                    else
                    {
                        datos = '<div class="form-group col-md-12"><h4>PRIORIDAD ADMIN SELECCIONADA</h4></div>'+
                                '<div class="form-group col-md-12">'+
                                    '<h5>'+data.prioridad_admin+'</h5>'+
                                '</div>';
                    }
                }


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
                $("#caja_prioridad").html(datos);
                swal.close();
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
