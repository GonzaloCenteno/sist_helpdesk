jQuery(document).ready(function($){
    
    jQuery("#tabla_proveedores").jqGrid({
        url: 'proveedor/0?grid=proveedores',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        //cmTemplate: { sortable: false },
        colNames: ['ID', 'RAZON SOCIAL', 'RUC', 'TELEFONO', 'CONTACTO', 'ESTADO'],
        rowNum: 10, sortname: 'pro_id', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE PROVEEDORES', align: "center",
        colModel: [
            {name: 'pro_id', index: 'pro_id', align: 'left',width: 10, hidden:true},
            {name: 'pro_raz', index: 'pro_raz', align: 'left', width: 30},
            {name: 'pro_ruc', index: 'pro_ruc', align: 'center', width: 20},
            {name: 'pro_tel', index: 'pro_tel', align: 'center', width: 12},
            {name: 'pro_con', index: 'pro_con', align: 'left', width: 30},
            {name: 'pro_est', index: 'pro_est', align: 'center', width: 12}
        ],
        pager: '#paginador_tabla_proveedores',
        rowList: [10, 20, 30, 40, 50, 100000000],
        loadComplete: function() {
            $("option[value=100000000]").text('TODOS');
        },
        gridComplete: function () {
                var idarray = jQuery('#tabla_proveedores').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tabla_proveedores').jqGrid('getDataIDs')[0];
                        $("#tabla_proveedores").setSelection(firstid);    
                    }
            },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){$('#btn_modificar_proveedor').click();}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tabla_proveedores").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
    $("#txt_razon_social").keypress(function (e) {
        if (e.which == 13) {
            $('#btn_buscar_proveedor').click();
        }
    });
    
});

function limpiar_datos_proveedor()
{
    $('#mdl_razon_social').val('');
    $('#mdl_ruc').val('');
    $('#mdl_telefono').val('');
    $('#mdl_contacto').val('');
}

jQuery(document).on("click", "#btn_nuevo_proveedor", function(){
    $('#titulo_proveedor').text('CREAR NUEVO PROVEEDOR');
    $('#btn_guardar_proveedor').show();
    $('#btn_actualizar_proveedor').hide();
    limpiar_datos_proveedor();
})

jQuery(document).on("click", "#btn_guardar_proveedor", function(){
    
//    $('#descripcion').focus();
    
    if ($('#mdl_razon_social').val() == '') {
        mostraralertasconfoco('* EL CAMPO RAZON SOCIAL ES OBLIGATORIO...', '#mdl_razon_social');
        return false;
    }
    
    if ($('#mdl_ruc').val() == '') {
        mostraralertasconfoco('* EL CAMPO RUC ES OBLIGATORIO...', '#mdl_ruc');
        return false;
    }
    
    $.ajax({
        url: 'proveedor/create',
        type: 'GET',
        data:
        {
            razon_social: $('#mdl_razon_social').val(),
            ruc: $('#mdl_ruc').val(),
            telefono: $('#mdl_telefono').val() || '-',
            contacto: $('#mdl_contacto').val() || '-'
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
                jQuery("#tabla_proveedores").jqGrid('setGridParam', {
                    url: 'proveedor/0?grid=proveedores'
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

jQuery(document).on("click", "#btn_modificar_proveedor", function(){
    
    id_proveedor = $('#tabla_proveedores').jqGrid ('getGridParam', 'selrow');
    
    if(id_proveedor){
        $('#btn_nuevo_proveedor').click();
        $('#titulo_proveedor').text('MODIFICAR PROVEEDOR');
        $('#btn_guardar_proveedor').hide();
        $('#btn_actualizar_proveedor').show();
        
        $('#mdl_razon_social').val($('#tabla_proveedores').jqGrid ('getCell', id_proveedor, 'pro_raz'));
        $('#mdl_ruc').val($('#tabla_proveedores').jqGrid ('getCell', id_proveedor, 'pro_ruc'));
        $('#mdl_telefono').val($('#tabla_proveedores').jqGrid ('getCell', id_proveedor, 'pro_tel'));
        $('#mdl_contacto').val($('#tabla_proveedores').jqGrid ('getCell', id_proveedor, 'pro_con'));
        
    }else{
        mostraralertasconfoco("NO HAY NINGUN PROVEEDOR SELECCIONADO","#tabla_proveedores");
    }
    
})

jQuery(document).on("click", "#btn_actualizar_proveedor", function(){
    
    id_proveedor = $('#tabla_proveedores').jqGrid ('getGridParam', 'selrow');
    
    if ($('#mdl_razon_social').val() == '') {
        mostraralertasconfoco('* EL CAMPO RAZON SOCIAL ES OBLIGATORIO...', '#mdl_razon_social');
        return false;
    }
    
    if ($('#mdl_ruc').val() == '') {
        mostraralertasconfoco('* EL CAMPO RUC ES OBLIGATORIO...', '#mdl_ruc');
        return false;
    }
    
    $.ajax({
        url: 'proveedor/'+id_proveedor+'/edit',
        type: 'GET',
        data:
        {
            razon_social: $('#mdl_razon_social').val(),
            ruc: $('#mdl_ruc').val(),
            telefono: $('#mdl_telefono').val() || '-',
            contacto: $('#mdl_contacto').val() || '-',
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
                jQuery("#tabla_proveedores").jqGrid('setGridParam', {
                    url: 'proveedor/0?grid=proveedores'
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

function cambiar_estado_proveedor(id_proveedor,estado)
{
    $.ajax({
        url: 'proveedor/'+id_proveedor+'/edit',
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
                jQuery("#tabla_proveedores").jqGrid('setGridParam', {
                    url: 'proveedor/0?grid=proveedores'
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

jQuery(document).on("click", "#btn_buscar_proveedor", function(){
    $.ajax({
        url: 'proveedor/0?validar=validar_proveedores',
        type: 'GET',
        data:
        {
            razon_social:$('#txt_razon_social').val()
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
                $('#txt_razon_social').val('');
                $('#txt_razon_social').focus();
                jQuery("#tabla_proveedores").jqGrid('setGridParam', {
                    url: 'proveedor/0?grid=proveedores'
                }).trigger('reloadGrid');
            }
            else
            {
                jQuery("#tabla_proveedores").jqGrid('setGridParam', {
                    url: 'proveedor/0?grid=buscar_proveedores&razon_social='+$('#txt_razon_social').val()
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