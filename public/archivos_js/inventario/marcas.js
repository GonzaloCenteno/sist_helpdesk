
function limpiar_datos_marcas()
{
    $('#descripcion').val('');
}

jQuery(document).on("click", "#btn_nueva_marca", function(){
    $('#titulo_marca').text('CREAR NUEVA MARCA');
    $('#btn_guardar_marca').show();
    $('#btn_actualizar_marca').hide();
    limpiar_datos_marcas();
})

jQuery(document).on("click", "#btn_guardar_marca", function(){
    
//    $('#descripcion').focus();
    if ($('#descripcion').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#descripcion');
        return false;
    }
    
    $.ajax({
        url: 'marcas/create',
        type: 'GET',
        data:
        {
            descripcion: $('#descripcion').val()
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
                jQuery("#tabla_marcas").jqGrid('setGridParam', {
                    url: 'marcas/0?grid=marcas'
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

jQuery(document).on("click", "#btn_modificar_marca", function(){
    
    id_marca = $('#tabla_marcas').jqGrid ('getGridParam', 'selrow');
    
    if(id_marca){
        $('#btn_nueva_marca').click();
        $('#titulo_marca').text('MODIFICAR MARCA');
        $('#btn_guardar_marca').hide();
        $('#btn_actualizar_marca').show();
        
        $('#descripcion').val($('#tabla_marcas').jqGrid ('getCell', id_marca, 'mar_desc'));
        
    }else{
        mostraralertasconfoco("NO HAY NINGUNA MARCA SELECCIONADA","#tabla_marcas");
    }
    
})

jQuery(document).on("click", "#btn_actualizar_marca", function(){
    
    id_marca = $('#tabla_marcas').jqGrid ('getGridParam', 'selrow');
    
    if ($('#descripcion').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#descripcion');
        return false;
    }
    
    $.ajax({
        url: 'marcas/'+id_marca+'/edit',
        type: 'GET',
        data:
        {
            descripcion: $('#descripcion').val(),
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
                jQuery("#tabla_marcas").jqGrid('setGridParam', {
                    url: 'marcas/0?grid=marcas'
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

function cambiar_estado_marca(id_marca,estado)
{
    $.ajax({
        url: 'marcas/'+id_marca+'/edit',
        type: 'GET',
        data:
        {
            estado: estado,
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
                MensajeConfirmacion("CAMBIO DE ESTADO EXITOSO");
                jQuery("#tabla_marcas").jqGrid('setGridParam', {
                    url: 'marcas/0?grid=marcas'
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
}

jQuery(document).on("click", "#btn_buscar_marca", function(){    
    $.ajax({
        url: 'marcas/0?validar=validar_marcas',
        type: 'GET',
        data:
        {
            descripcion:$('#descripcion_marca').val()
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
                $('#descripcion_marca').val('');
                $('#descripcion_marca').focus();
                jQuery("#tabla_marcas").jqGrid('setGridParam', {
                    url: 'marcas/0?grid=marcas'
                }).trigger('reloadGrid');
            }
            else
            {
                jQuery("#tabla_marcas").jqGrid('setGridParam', {
                    url: 'marcas/0?grid=buscar_marcas&descripcion='+$('#descripcion_marca').val()
                }).trigger('reloadGrid');
                swal.close();
            }
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
})

jQuery(document).on("click", "#btn_act_table_marca", function(){
    jQuery("#tabla_marcas").jqGrid('setGridParam', {
        url: 'marcas/0?grid=marcas'
    }).trigger('reloadGrid');
    
    $('#descripcion_marca').val('');
})