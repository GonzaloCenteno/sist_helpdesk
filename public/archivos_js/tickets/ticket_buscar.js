jQuery(document).ready(function($){
    
    CKEDITOR.replace('mdl_nueva_descripcion');
    
    jQuery("#tabla_tickets").jqGrid({
        url: 'ticketbuscar/0?grid=tickets',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        //cmTemplate: { sortable: false },
        pgbuttons: false,
        pgtext: null, 
        colNames: ['ID', 'TITULO', 'TIPO', 'AREA', 'PRIORIDAD', 'ESTADO', 'FECHA', 'VER TICKET'],
        rowNum: 10, sortname: 'cabt_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_ticket_creados" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE TICKETS CREADOS -', align: "center",
        colModel: [
            {name: 'cabt_id', index: 'cabt_id', align: 'left',width: 20, hidden: true},
            {name: 'cabt_asunto', index: 'cabt_asunto', align: 'left', width: 40},
            {name: 'tip_desc', index: 'tip_desc', align: 'left', width: 20},
            {name: 'are_desc', index: 'are_desc', align: 'left', width: 25},
            {name: 'prio_desc', index: 'prio_desc', align: 'left', width: 20},
            {name: 'desc_est', index: 'desc_est', align: 'left', width: 20},
            {name: 'cabt_feccre', index: 'cabt_feccre', align: 'center', width: 25},
            {name: 'cabt_id', index: 'cabt_id', align: 'center', width: 25}
        ],
        pager: '#paginador_tabla_tickets',
        rowList: [10, 20, 30, 40, 50, 100000000],
        loadComplete: function() {
            $("option[value=100000000]").text('TODOS');
        },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tabla_tickets").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
});

jQuery(document).on("click", "#btn_buscar_datos", function(){
    if ($('#fecha_desde').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA DESDE ES OBLIGATORIO...', '#fecha_desde');
        return false;
    }
    if ($('#fecha_hasta').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA HASTA ES OBLIGATORIO...', '#fecha_hasta');
        return false;
    }
    
    jQuery("#tabla_tickets").jqGrid('setGridParam', {
        url: 'ticketbuscar/0?grid=buscar_tickets&titulo='+$('#titulo').val()+'&fecha_desde='+$('#fecha_desde').val()+'&fecha_hasta='+$('#fecha_hasta').val()
    }).trigger('reloadGrid');

})

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
                    if (data.datos.ARCH != '-') 
                    {
                        html = html+'<div class="form-group col-md-12"><label for="titulo" class="fw-500">PERTENECE A:</label><label class="bdc-grey-200"><b> '+data.datos.USUNOM+' </b></label></div>\n\
                                <div class="form-group col-md-12"><label for="titulo" class="fw-500">DESCRIPCION:</label><label class="form-control bdc-grey-200">'+data.datos.TEXT+'</label></div>\n\
                                <div class="form-group col-md-6"><label for="titulo" class="fw-500">FECHA CREACION:</label><label class="bdc-grey-200">'+data.datos.FECCRE+'</label></div>\n\
                                <div class="form-group col-md-6"><a class="btn btn-danger btn-sm btn-block" style="text-decoration: none;" href=descargar/'+data.datos.IDRESP+' ><span class="btn-label"><i class="fa fa-print"></i></span> DESCARGAR</a></div>';
                    }
                    else
                    {
                        html = html+'<div class="form-group col-md-12"><label for="titulo" class="fw-500">PERTENECE A:</label><label class="bdc-grey-200"><b> '+data.datos.USUNOM+' </b></label></div>\n\
                                <div class="form-group col-md-12"><label for="titulo" class="fw-500">DESCRIPCION:</label><label class="form-control bdc-grey-200">'+data.datos.TEXT+'</label></div>\n\
                                <div class="form-group col-md-6"><label for="titulo" class="fw-500">FECHA CREACION:</label><label class="bdc-grey-200">'+data.datos.FECCRE+'</label></div>';
                    }
                }
                else
                {
                    for(i=0;i<data.datos.length;i++)
                    {
                        if (data.datos[i].ARCH != '-') 
                        {
                            html = html+'<div class="form-group col-md-12"><label for="titulo" class="fw-500">PERTENECE A:</label><label class="bdc-grey-200"><b> '+data.datos[i].USUNOM+' </b></label></div>\n\
                                    <div class="form-group col-md-12"><label for="titulo" class="fw-500">DESCRIPCION:</label><label class="form-control bdc-grey-200">'+data.datos[i].TEXT+'</label></div>\n\
                                    <div class="form-group col-md-6"><label for="titulo" class="fw-500">FECHA CREACION:</label><label class="bdc-grey-200">'+data.datos[i].FECCRE+'</label></div>\n\
                                    <div class="form-group col-md-6"><a class="btn btn-danger btn-sm btn-block" style="text-decoration: none;" href=descargar/'+data.datos[i].IDRESP+' ><span class="btn-label"><i class="fa fa-print"></i></span> DESCARGAR</a></div>';
                        }
                        else
                        {
                            html = html+'<div class="form-group col-md-12"><label for="titulo" class="fw-500">PERTENECE A:</label><label class="bdc-grey-200"><b> '+data.datos[i].USUNOM+ '</b></label></div>\n\
                                    <div class="form-group col-md-12"><label for="titulo" class="fw-500">DESCRIPCION:</label><label class="form-control bdc-grey-200">'+data.datos[i].TEXT+'</label></div>\n\
                                    <div class="form-group col-md-6"><label for="titulo" class="fw-500">FECHA CREACION:</label><label class="bdc-grey-200">'+data.datos[i].FECCRE+'</label></div>';
                        }
                    }
                }
                $('.modal-body').scrollTop(0);
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

jQuery(document).on("click", "#btn_responder_ticket" ,function(){
    
    var respuesta = CKEDITOR.instances['mdl_nueva_descripcion'].getData();

    if (respuesta == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#mdl_nueva_descripcion');
        return false;
    }
    
    id_ticket = $('#tabla_tickets').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        url: 'ticketbuscar/'+id_ticket+'/edit',
        type: 'GET',
        data:
        {
            respuesta:respuesta,
            tipo:1
        },
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data == '00000') 
            {
                MensajeConfirmacion('EL RESPUESTA FUE ENVIADA CON EXITO');
                $('#btn_cerrar_sesion').click();
                CKEDITOR.instances['mdl_nueva_descripcion'].setData('INGRESAR UNA DESCRIPCION');
            }
            else if (data == '90006') 
            {
                MensajeAdvertencia('PRIMERO DEBES ASIGNAR EL TICKET A UNA PERSONA');
                CKEDITOR.instances['mdl_nueva_descripcion'].setData('INGRESAR UNA DESCRIPCION');
            }
            else
            {
                MensajeAdvertencia('NO SE PUDO ENVIAR LA RESPUESTA');
                console.log(data);
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
})

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
                    if (data == '00000') 
                    {
                        $('#btn_cerrar_sesion').click();
                        MensajeConfirmacion('EL TICKET FUE CERRADO');
                        jQuery("#tabla_tickets").jqGrid('setGridParam', {
                            url: 'ticketbuscar/0?grid=tickets'
                        }).trigger('reloadGrid');
                        CKEDITOR.instances['mdl_nueva_descripcion'].setData('INGRESAR UNA DESCRIPCION');
                    }
                    else if (data == '90006') 
                    {
                        MensajeAdvertencia('PRIMERO DEBES ASIGNAR EL TICKET A UNA PERSONA');
                        CKEDITOR.instances['mdl_nueva_descripcion'].setData('INGRESAR UNA DESCRIPCION');
                    }
                    else
                    {
                        MensajeAdvertencia('NO SE PUDO ENVIAR LA RESPUESTA');
                        console.log(data);
                    }
                },
                error: function(data) {
                    MensajeAdvertencia("hubo un error, Comunicar al Administrador");
                    console.log('error');
                    console.log(data);
                }
            });
        }, function(dismiss) {
            console.log('OPERACION CANCELADA');
        });
})

//RECHAZAR Y CAMBIAR PRIORIDAD DE TICKETS ASIGNADOS

jQuery(document).on("click", "#btn_rechazar_tickets", function(){
    
    id_ticket = $('#tabla_tickets').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        url: 'ticketasignados/'+id_ticket+'/edit',
        type: 'GET',
        data:
        {
            tipo:3
        },
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data.respuesta == '00000') 
            {
                MensajeConfirmacion(data.mensaje);
                $('#btn_cerrar_sesion').click();
                jQuery("#tabla_tickets").jqGrid('setGridParam', {
                    url: 'ticketbuscar/0?grid=tickets'
                }).trigger('reloadGrid');
            }
            else
            {
                MensajeAdvertencia('NO SE PUDO OBTENER RESPUESTA');
                console.log(data);
            }
//            console.log(data);
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
})

jQuery(document).on("click", "#btn_prioridad_nuevo", function(){
    id_ticket = $('#tabla_tickets').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        url: 'ticketasignados/'+id_ticket+'/edit',
        type: 'GET',
        data:
        {
            prioridad : $('#prioridad_nuevo').val(),
            tipo:4
        },
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data.respuesta == '00000') 
            {
                MensajeConfirmacion(data.mensaje);
            }
            else
            {
                MensajeAdvertencia('NO SE PUDO OBTENER RESPUESTA');
                console.log(data);
            }
//            console.log(data);
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
})

jQuery(document).on("click", "#btn_act_table_ticket_creados", function(){
    jQuery("#tabla_tickets").jqGrid('setGridParam', {
        url: 'ticketbuscar/0?grid=tickets'
    }).trigger('reloadGrid');
    
    $('#titulo').val('');
    $('#fecha_desde').val('');
    $('#fecha_hasta').val('');
})