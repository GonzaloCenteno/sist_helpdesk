jQuery(document).ready(function($){
    
    CKEDITOR.replace('mdl_nueva_descripcion');
    CKEDITOR.replace('mdl_nueva_respuesta');
    
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
        rowNum: 20, sortname: 'cabt_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_ticket_creados" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE TICKETS CREADOS -', align: "center",
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
        rowList: [20, 30, 40, 50, 100000000],
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
    
    jQuery("#tabla_tickets").jqGrid('setGridParam', {
        url: 'ticketbuscar/0?grid=buscar_tickets&titulo='+$('#titulo').val()+'&fecha_desde='+$('#fecha_desde').val()+'&fecha_hasta='+$('#fecha_hasta').val()
    }).trigger('reloadGrid');

})

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
    datos = "";
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
                datos = '<div class="form-group col-md-12"><h4>PRIORIDAD ADMIN SELECCIONADA</h4></div>'+
                                '<div class="form-group col-md-12">'+
                                    '<h5>'+data.prioridad+'</h5>'+
                                '</div>';
                $("#caja_prioridad_tb").html(datos);
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