jQuery(document).ready(function($){
    
    CKEDITOR.replace('mdl_nueva_descripcion');
    
    jQuery("#tabla_tickets_asignados").jqGrid({
        url: 'ticketasignados/0?grid=tickets_asignados',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        pgbuttons: false,
        pgtext: null, 
        //cmTemplate: { sortable: false },
        colNames: ['ID', 'TITULO', 'TIPO', 'AREA', 'PRIORIDAD', 'ESTADO', 'FECHA', 'VER TICKET'],
        rowNum: 20, sortname: 'cabt_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_tickets_asignados" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE TICKETS ASIGNADOS -', align: "center",
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
        rowList: [20, 30, 40, 50, 100000000],
        loadComplete: function() {
            $("option[value=100000000]").text('TODOS');
        },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tabla_tickets_asignados").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
});

jQuery(document).on("click", "#btn_buscar_ticket_asignados", function(){

    jQuery("#tabla_tickets_asignados").jqGrid('setGridParam', {
        url: 'ticketasignados/0?grid=buscar_tickets&titulo='+$('#txt_titulo').val()+'&fecha_desde='+$('#txt_fecha_desde').val()+'&fecha_hasta='+$('#txt_fecha_hasta').val()
    }).trigger('reloadGrid');
                
})

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

jQuery(document).on("click", "#btn_prioridad_asignados", function(){
    id_ticket = $('#tabla_tickets_asignados').jqGrid ('getGridParam', 'selrow');
    
    dato = "";
    $.ajax({
        url: 'ticketasignados/'+id_ticket+'/edit',
        type: 'GET',
        data:
        {
            prioridad : $('#prioridad_asignados').val(),
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
                datos = '<div class="form-group col-md-12"><h4>PRIORIDAD ADMIN SELECCIONADA</h4></div>'+
                                '<div class="form-group col-md-12">'+
                                    '<h5>'+data.prioridad+'</h5>'+
                                '</div>';
                $("#caja_prioridad").html(datos);
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

jQuery(document).on("click", "#btn_act_table_tickets_asignados", function(){
    jQuery("#tabla_tickets_asignados").jqGrid('setGridParam', {
        url: 'ticketasignados/0?grid=tickets_asignados'
    }).trigger('reloadGrid');
    
    $('#txt_titulo').val('');
    $('#txt_fecha_desde').val('');
    $('#txt_fecha_hasta').val('');
})