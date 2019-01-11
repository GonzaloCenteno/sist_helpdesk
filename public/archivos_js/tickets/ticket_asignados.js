jQuery(document).ready(function($){
    
    CKEDITOR.replace('mdl_nueva_descripcion');
    
    jQuery("#tabla_tickets_asignados").jqGrid({
        url: 'ticketasignados/0?grid=tickets_asignados',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        //cmTemplate: { sortable: false },
        colNames: ['ID', 'TITULO', 'TIPO', 'AREA', 'PRIORIDAD', 'ESTADO', 'FECHA', 'VER TICKET'],
        rowNum: 10, sortname: 'cabt_id', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE TICKETS ASIGNADOS', align: "center",
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
        pager: '#paginador_tabla_tickets_asignados',
        rowList: [10, 20, 30, 40, 50],
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tabla_tickets_asignados").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
});

jQuery(document).on("click", "#btn_buscar_ticket_asignados", function(){
    if ($('#txt_fecha_desde').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA DESDE ES OBLIGATORIO...', '#txt_fecha_desde');
        return false;
    }
    if ($('#txt_fecha_hasta').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA HASTA ES OBLIGATORIO...', '#txt_fecha_hasta');
        return false;
    }
    
    $.ajax({
        url: 'ticketasignados/0?validar=validar_tickets',
        type: 'GET',
        data:
        {
            titulo:$('#txt_titulo').val(),
            fecha_desde:$('#txt_fecha_desde').val(),
            fecha_hasta:$('#txt_fecha_hasta').val()
        },
        beforeSend:function()
        {            
            MensajeEspera('BUSCANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data == 0) 
            {
                mostraralertasconfoco('NO SE ENCONTRARON REGISTROS');
                jQuery("#tabla_tickets_asignados").jqGrid('setGridParam', {
                    url: 'ticketasignados/0?grid=tickets_asignados'
                }).trigger('reloadGrid');
            }
            else
            {
                jQuery("#tabla_tickets_asignados").jqGrid('setGridParam', {
                    url: 'ticketasignados/0?grid=buscar_tickets&titulo='+$('#txt_titulo').val()+'&fecha_desde='+$('#txt_fecha_desde').val()+'&fecha_hasta='+$('#txt_fecha_hasta').val()
                }).trigger('reloadGrid');
                swal.close();
            }
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
        
})

function ver_ticket_asignados(id_ticket)
{
    CKEDITOR.instances['mdl_nueva_descripcion'].setData('INGRESAR UNA DESCRIPCION');
    
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
                            html = html+'<div class="form-group col-md-12"><label for="titulo" class="fw-500">PERTENECE A:</label><label class="bdc-grey-200"><b> '+data.datos[i].USUNOM+' </b></label></div>\n\
                                    <div class="form-group col-md-12"><label for="titulo" class="fw-500">DESCRIPCION:</label><label class="form-control bdc-grey-200">'+data.datos[i].TEXT+'</label></div>\n\
                                    <div class="form-group col-md-6"><label for="titulo" class="fw-500">FECHA CREACION:</label><label class="bdc-grey-200">'+data.datos[i].FECCRE+'</label></div>';
                        }
                    }
                }
                $('.modal-body').scrollTop(0);
                $("#detalle").html(html);
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

jQuery(document).on("click", "#btn_responder_ticket_asignados" ,function(){
    
    var respuesta = CKEDITOR.instances['mdl_nueva_descripcion'].getData();

    if (respuesta == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#mdl_nueva_descripcion');
        return false;
    }
    
    id_ticket = $('#tabla_tickets_asignados').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        url: 'ticketasignados/'+id_ticket+'/edit',
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
                $('#btn_cerrar_modal').click();
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

jQuery(document).on("click", "#btn_cerrar_ticket_asignados", function(){
    
    id_ticket = $('#tabla_tickets_asignados').jqGrid ('getGridParam', 'selrow');
    
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
                url: 'ticketasignados/'+id_ticket+'/edit',
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
                        $('#btn_cerrar_modal').click();
                        MensajeConfirmacion('EL TICKET FUE CERRADO');
                        jQuery("#tabla_tickets_asignados").jqGrid('setGridParam', {
                            url: 'ticketasignados/0?grid=tickets_asignados'
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

jQuery(document).on("click", "#btn_rechazar_ticket", function(){
    
    id_ticket = $('#tabla_tickets_asignados').jqGrid ('getGridParam', 'selrow');
    
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
                $('#btn_cerrar_modal').click();
                jQuery("#tabla_tickets_asignados").jqGrid('setGridParam', {
                    url: 'ticketasignados/0?grid=tickets_asignados'
                }).trigger('reloadGrid');
            }
            else
            {
                MensajeAdvertencia('NO SE PUDO ENVIAR LA RESPUESTA');
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
