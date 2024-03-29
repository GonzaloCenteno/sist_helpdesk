
function limpiar_datos_movimiento()
{
    var now = new Date();
    var today =  now.getDate() + '/' + (now.getMonth() + 1) + '/' + now.getFullYear();
    $('#mdl_mfecha').val(today);
}

jQuery(document).on("click", "#btn_nuevo_movimiento", function(){
    $('#titulo_movimiento').text('CREAR NUEVO MOVIMIENTO');
    $('#btn_guardar_movimiento').show();
    $('#btn_actualizar_movimiento').hide();
    $('#punto_venta_origen').empty();
    $('#mdl_mitem').val('0');
    $('#mdl_mitem').change();
    limpiar_datos_movimiento();
})

jQuery(document).on("click", "#btn_guardar_movimiento", function(){
    
    if ($('#mdl_mitem').val() == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UN ITEM DE LA LISTA...', '#mdl_mitem');
        return false;
    }
    
    if ($('#mdl_mfecha').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA ES OBLIGATORIO...', '#mdl_mfecha');
        return false;
    }
    
    $.ajax({
        url: 'movimientos/create',
        type: 'GET',
        data:
        {
            id_item: $("#mdl_mitem").val(),
            pvt_origen: $("#mdl_pvt_origen").val(),
            pvt_destino: $("#mdl_pvt_destino").val(),
            fecha: $('#mdl_mfecha').val()
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
                jQuery("#tabla_movimientos").jqGrid('setGridParam', {
                    url: 'movimientos/0?grid=movimientos'
                }).trigger('reloadGrid');
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL ENVIAR LA INFORMACION');
                console.log(data.mensaje);
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

jQuery(document).on("click", "#btn_modificar_movimiento", function(){
    
    id_movimiento = $('#tabla_movimientos').jqGrid ('getGridParam', 'selrow');
    html = '';
    if(id_movimiento){
        $('#btn_nuevo_movimiento').click();
        $('#titulo_movimiento').text('MODIFICAR MOVIMIENTO');
        $('#btn_guardar_movimiento').hide();
        $('#btn_actualizar_movimiento').show();
        
        $('#mdl_mitem').val($('#tabla_movimientos').jqGrid ('getCell', id_movimiento, 'id_item'));
        $('#mdl_mitem').change();
        
        html = '<input type="hidden" id="mdl_pvt_origen" value="'+$('#tabla_movimientos').jqGrid ('getCell', id_movimiento, 'id_pvt_ori')+'"><h4>PUNTO ORIGEN: '+$('#tabla_movimientos').jqGrid ('getCell', id_movimiento, 'pvt_ori')+'</h4>'
        
        $('#mdl_pvt_destino').val($('#tabla_movimientos').jqGrid ('getCell', id_movimiento, 'id_pvt_des'));
        $('#mdl_pvt_destino').change();
        
        $('#mdl_mfecha').val($('#tabla_movimientos').jqGrid ('getCell', id_movimiento, 'mov_fec'));

        $("#punto_venta_origen").html(html);        
    }else{
        mostraralertasconfoco("NO HAY NINGUN MOVIMIENTO SELECCIONADO","#tabla_movimientos");
    }
    
})

jQuery(document).on("click", "#btn_actualizar_movimiento", function(){
    
    id_movimiento = $('#tabla_movimientos').jqGrid ('getGridParam', 'selrow');
    
    if ($('#mdl_mfecha').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA ES OBLIGATORIO...', '#mdl_mfecha');
        return false;
    }
    
    $.ajax({
        url: 'movimientos/'+id_movimiento+'/edit',
        type: 'GET',
        data:
        {
            id_item: $("#mdl_mitem").val(),
            pvt_origen: $("#mdl_pvt_origen").val(),
            pvt_destino: $("#mdl_pvt_destino").val(),
            fecha: $('#mdl_mfecha').val(),
            tipo:1
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
                jQuery("#tabla_movimientos").jqGrid('setGridParam', {
                    url: 'movimientos/0?grid=movimientos'
                }).trigger('reloadGrid');
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL ENVIAR LA INFORMACION');
                console.log(data.mensaje);
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
    
})

jQuery(document).on("click", "#btn_buscar_movimiento", function(){   
    
    jQuery("#tabla_movimientos").jqGrid('setGridParam', {
        url: 'movimientos/0?grid=buscar_movimientos&descripcion='+$('#txt_descripcion_item').val()+'&fecha_desde='+$('#txt_fecha_desde').val()+'&fecha_hasta='+$('#txt_fecha_hasta').val()
    }).trigger('reloadGrid');
             
})

function cambiar_estado_item(id_item,estado)
{
    
    
    if (estado == 6) 
    {
        swal({
        title: '¿ESTA SEGURO DE QUERER DESCATIVAR ESTE ITEM?',
        text: "EL ITEM PASARA A ESTADO DESACTIVO...",
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
                 url: 'items/'+id_item+'/edit',
                 type: 'GET',
                 data:
                 {
                     estado:estado,
                     tipo:2
                 },
                 beforeSend:function()
                 {            
                     MensajeEspera('ENVIANDO INFORMACION');  
                 },
                 success: function(data) 
                 {
                     if (data > 0) 
                     {
                         MensajeConfirmacion("EL ITEM FUE DESACTIVADO");
                         jQuery("#tabla_items").jqGrid('setGridParam', {
                             url: 'items/0?grid=items'
                         }).trigger('reloadGrid');
                     }
                     else
                     {
                         MensajeAdvertencia('OCURRIO UN PROBLEMA AL ENVIAR LA INFORMACION');
                         console.log(data.mensaje);
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
    }
    else
    {
        mostraralertasconfoco("NO SE PUEDE REALIZAR ESTA ACCION","#tabla_items");
    }
}

jQuery(document).on("click", "#btn_act_table_movimiento", function(){
    jQuery("#tabla_movimientos").jqGrid('setGridParam', {
        url: 'movimientos/0?grid=movimientos'
    }).trigger('reloadGrid');
    
    $('#txt_descripcion_item').val('');
    $('#txt_fecha_desde').val('');
    $('#txt_fecha_hasta').val('');
});