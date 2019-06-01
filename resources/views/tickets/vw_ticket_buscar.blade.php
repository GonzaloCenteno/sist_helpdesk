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
            <h2 class="c-grey-900 text-center">TICKETS CREADOS</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="titulo" class="fw-500">BUSCAR POR TITULO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="titulo" name="titulo" placeholder="ESCRIBIR TITULO DEL TICKET" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="fw-500">FECHA DESDE:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="fecha_desde" placeholder="SELECCIONAR UNA FECHA" name="fecha_desde" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
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
                                <input type="text" class="form-control start-date rounded" id="fecha_hasta" placeholder="SELECCIONAR UNA FECHA" name="fecha_hasta" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 29px;">
                        <button id="btn_buscar_datos" class="btn btn-danger btn-block" type="button"><i class="fa fa-search"></i> BUSCAR</button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_tickets"></table>
                            <div id="paginador_tabla_tickets"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="form-row" style="display:none;">
    <div class="form-group col-md-6">
        <button class="btn" id="btn_abrir_modal" style="background-color:#D48411;color:white;" data-toggle="modal" data-target="#Modal_Ticket" data-backdrop="static" data-keyboard="false" type="button"></button>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <div class="form-row text-center" id="caja_prioridad_tb">
                    <!-- DATOS DINAMICOS -->
                    <hr>
                </div>
                
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_sesion">CERRAR VENTANA</button>
                @if(session('sro_id') == 1 || session('sro_id') == 2)
                    <button type="button" class="btn btn-danger" id="btn_rechazar_tickets">RECHAZAR TICKET</button>
                @else
                @endif
                <button type="button" class="btn btn-primary" id="btn_responder_ticket">RESPONDER TICKET</button>
                <button type="button" class="btn btn-success" id="btn_cerrar_ticket">CERRAR TICKET</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ENCUESTA -->

<div class="form-row" style="display:none;">
    <div class="form-group col-md-6">
        <button class="btn" id="btn_abrir_encuesta" style="background-color:#D48411;color:white;" data-toggle="modal" data-target="#Modal_Encuesta" data-backdrop="static" data-keyboard="false" type="button"></button>
    </div>
</div>

<div class="modal fade" id="Modal_Encuesta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">INFORMACION ENCUESTA</h5>
            </div>
            <div class="modal-body">
                <h5 class="fw-500"><b>LEA CUIDADOSAMENTE CADA PREGUNTA, YA QUE SOLO PODRA SELECCIONAR UNA RESPUESTA</b></h5>
                <div class="form-row" id="cuerpo_encuesta">
                    
                </div>
                <hr>
                <div class="form-group">
                    <label for="editor" class="fw-500"><b>¿PORQUE MOTIVOS RESPONDISTE DE ESTA MANERA?</b></label>
                    <textarea  id="mdl_nueva_respuesta" name="mdl_nueva_respuesta"></textarea >
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_enviar_respuesta">ENVIAR RESPUESTA</button>
                <button style="display:none;" type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal_encuesta">CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/tickets/ticket_buscar.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
    
jQuery(document).on("click", "#btn_cerrar_ticket", function(){
    
    id_ticket = $('#tabla_tickets').jqGrid ('getGridParam', 'selrow');
    
    swal({
       title: '¿ESTA SEGURO DE QUERER CERRAR ESTE TICKET?',
       text: "EL TICKET PASARA A ESTADO FINALIZADO...",
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       cancelButtonText: 'CANCELAR',
       confirmButtonText: 'ACEPTAR',
       confirmButtonClass: 'btn btn-success',
       cancelButtonClass: 'btn btn-danger',
       buttonsStyling: false,
       reverseButtons: true,
       allowOutsideClick: false,
        allowEscapeKey:false,
        allowEnterKey:false
     }).then(function(result) {
            if (variable == 3) 
            {
                crear_encuesta();           
            }
            else
            {
                cerrar_ticket(id_ticket);
            }
        }, function(dismiss) {
            console.log('OPERACION CANCELADA');
        });
})

function crear_encuesta()
{
    $.ajax({
        url: 'ticketbuscar/0?show=traer_encuesta',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('CARGANDO INFORMACION');  
        },
        success: function(data) 
        {
            $('#btn_cerrar_sesion').click();
            CKEDITOR.instances['mdl_nueva_descripcion'].setData('INGRESAR UNA DESCRIPCION');
            $("#btn_abrir_encuesta").click();
            
            html="";
            for(i=0;i<data.preguntas.length;i++)
            {
                html = html+'<div class="form-group col-md-12"><label for="preguntas" class="fw-500"><input type="hidden" id="pregunta_'+i+'" value="'+data.preguntas[i].pre_id+'"><b> '+data.preguntas[i].pre_desc+' </b></label>\n\
                             <input type="hidden" class="datos_pregunta_valor" id="valor_pregunta_'+i+'" value="0"></div>';
                for(j=0;j<data.valores.length;j++)
                {
                    html = html+'<div style="padding-left: 45px;" class="form-group col-md-2 text-center"><input type="hidden" id="valor_'+j+'" value="'+data.valores[j].val_id+'"><input type="hidden" id="valimagen_'+i+'_'+j+'" class="img_preg_'+i+'" value="0">\n\
                                 <center><a href="#" onClick="activar_valor('+i+','+j+',valor_'+j+',valimagen_'+i+'_'+j+');"><img class="form-control text-center" id="imagen_'+i+'_'+j+'" src="data:image/png;base64,'+data.valores[j].val_img2+'" border="0" style="width: 80px;height: 60px;"/></a>\n\
                                 <label for="valores" class="fw-500 text-center"><b> '+data.valores[j].val_desc+' </b></label></center></div>';    
                }
            }
            $('.modal-body').scrollTop(0);
            $("#cuerpo_encuesta").html(html);
            swal.close();
            //console.log(data);
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

function activar_valor(valor_i,valor_j,id_valor,val_imagen)
{
    valor = id_valor[0].value;
    valor_imagen = val_imagen.value;
    $.ajax({
        url: 'ticketbuscar/0?show=traer_imagen_valor',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('CARGANDO INFORMACION');  
        },
        data:
        {
            id_valor:valor,
            valor_imagen:valor_imagen
        },
        success: function(data) 
        {
            if (data.valor == 1) 
            {
                $("#imagen_"+valor_i+"_"+valor_j).attr("src","data:image/png;base64,"+data.respuesta.val_img);
                $(".img_preg_"+valor_i).val(data.valor);
                //$("#imagen_"+valor_i+"_"+valor_j).attr("imgvalor",data.respuesta.val_id);
                $("#valor_pregunta_"+valor_i).val(data.respuesta.val_id);
                swal.close();
            }
            else
            {
                MensajeAdvertencia('SOLO SE PUEDE SELECCIONAR UNA RESPUESTA');
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

jQuery(document).on("click","#btn_enviar_respuesta", function(){
    var arreglo = new Array();
    $("input[type=hidden][class=datos_pregunta_valor]:hidden").each(function(){
        arreglo.push($(this).attr('value'));
    });
    
    var indice = jQuery.inArray('0', arreglo);
    if (indice != -1) 
    {
        MensajeAdvertencia("DEBE RESPONDER LA PREGUNTA: " + parseInt(indice+1));
    }
    else
    {
        id_ticket = $('#tabla_tickets').jqGrid ('getGridParam', 'selrow');
        $.ajax({
            url: 'ticketbuscar/0?show=traer_preguntas_valor',
            type: 'GET',
            success: function(data) 
            {
                cerrar_ticket(id_ticket);
                //console.log(data);
                for(i=0;i<data;i++)
                {
                    insertar_datos_encuesta(id_ticket,$("#pregunta_"+i).val(),$("#valor_pregunta_"+i).val());
                }
                insertar_observacion_encuesta(id_ticket);
            },
            error: function(data) {
                MensajeAdvertencia("hubo un error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
            }
        });
    }
})

function insertar_datos_encuesta(id_ticket,pregunta,valor)
{
    //console.log("ticket: "+ id_ticket + " Pregunta: " + pregunta + " valor seleccionado:" + valor);
    $.ajax({
        url: 'ticketbuscar/create',
        type: 'GET',
        data:
        {
            id_ticket:id_ticket,
            pregunta:pregunta,
            valor:valor,
            tipo:1
        },
        success: function(data) 
        {
            if (data.respuesta == '00000') 
            {
                console.log(data.mensaje);
            }
            else
            {
                console.log("ERROR AL ENVIAR DATOS" + data.mensaje);
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

function insertar_observacion_encuesta(id_ticket)
{
    var rpta_encuesta = CKEDITOR.instances['mdl_nueva_respuesta'].getData();
    
    $.ajax({
        url: 'ticketbuscar/create',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('CARGANDO INFORMACION');  
        },
        data:
        {
            id_ticket:id_ticket,
            respuesta:rpta_encuesta || '-',
            tipo:2
        },
        success: function(data) 
        {
            if (data.respuesta == '00000') 
            {
                MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
                jQuery("#tabla_tickets").jqGrid('setGridParam', {
                    url: 'ticketbuscar/0?grid=tickets'
                }).trigger('reloadGrid');
                $("#btn_cerrar_modal_encuesta").click();
                CKEDITOR.instances['mdl_nueva_respuesta'].setData('');
            }
            else
            {
                console.log("ERROR AL ENVIAR DATOS" + data.mensaje);
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}   

function cerrar_ticket(id_ticket)
{
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'ticketbuscar/'+id_ticket+'/edit',
        type: 'GET',
        data:
        {
            respuesta:'EL USUARIO CERRO ESTE TICKET',
            tipo:2
        },
        success: function(data) 
        {
            CKEDITOR.instances['mdl_nueva_descripcion'].setData('INGRESAR UNA DESCRIPCION');
            if (data.respuesta == '00000') 
            {
                console.log(data.mensaje);
            }
            else
            {
                console.log("ERROR AL ENVIAR DATOS" + data.mensaje);
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

function ver_ticket(id_ticket)
{
    CKEDITOR.instances['mdl_nueva_descripcion'].setData('INGRESAR UNA DESCRIPCION');
    
    $.ajax({
        url: 'ticketbuscar/'+id_ticket+'?show=traer_ticket',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('RECUPERANDO INFORMACION...');  
        },
        success: function(data) 
        {
            if (data.salida == '00000') 
            {
                $('#btn_abrir_modal').click();
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
                $("#caja_prioridad_tb").html(datos);
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
