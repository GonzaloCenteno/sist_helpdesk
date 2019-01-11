jQuery(document).ready(function($){
    
    jQuery("#tabla_items").jqGrid({
        url: 'items/0?grid=items',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
//        pgbuttons: false,
//        pgtext: null, 
        //cmTemplate: { sortable: false },
        colNames: ['ID', 'DESCRIPCION', 'SERIE', 'CANT.', 'IDMARCA', 'MARCA','IDPROVEEODR', 'PROVEE.','IDFACTURA', 'FACTURA','PRECIO','FECHA','ESTADO'],
        rowNum: 10, sortname: 'item_id', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE ITEMS', align: "center",
        colModel: [
            {name: 'item_id', index: 'item_id', align: 'left',width: 10, hidden:true},
            {name: 'item_desc', index: 'item_desc', align: 'left', width: 25},
            {name: 'item_ser', index: 'item_ser', align: 'center', width: 15},
            {name: 'item_cant', index: 'item_cant', align: 'center', width: 10},
            {name: 'id_marca', index: 'id_marca', align: 'center', width: 20, hidden:true},
            {name: 'mar_id', index: 'mar_id', align: 'left', width: 20},
            {name: 'id_proveedor', index: 'id_proveedor', align: 'left', width: 10, hidden:true},
            {name: 'pro_id', index: 'pro_id', align: 'left', width: 20},
            {name: 'id_factura', index: 'id_factura', align: 'left', width: 10, hidden:true},
            {name: 'fact_id', index: 'fact_id', align: 'center', width: 20},
            {name: 'item_prec', index: 'item_prec', align: 'center', width: 10},
            {name: 'item_fec', index: 'item_fec', align: 'center', width: 10},
            {name: 'item_est', index: 'item_est', align: 'center', width: 10}
        ],
        pager: '#paginador_tabla_items',
        rowList: [10, 20, 30, 40, 50, 100000000],
        loadComplete: function() {
            $("option[value=100000000]").text('TODOS');
        },
        gridComplete: function () {
                var idarray = jQuery('#tabla_items').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tabla_items').jqGrid('getDataIDs')[0];
                        $("#tabla_items").setSelection(firstid);    
                    }
            },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){$('#btn_modificar_item').click();}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tabla_items").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
    $("#mdl_imarca").select2({
        dropdownParent: $("#Modal_Item")
    });
    
    $("#mdl_iproveedor").select2({
        dropdownParent: $("#Modal_Item")
    });
    
    $("#mdl_ifactura").select2({
        dropdownParent: $("#Modal_Item")
    });
    
});

function limpiar_datos_item()
{
    var now = new Date();
    var today = (now.getMonth() + 1) + '/' + now.getDate() + '/' + now.getFullYear();
    $('#mdl_idescripcion').val('');
    $('#mdl_iserie').val('');
    $('#mdl_icantidad').val('');
    $('#mdl_iprecio').val('');
    $('#mdl_ifec_registro').val(today);
    $("#mdl_imarca").select2("val", "1");
    $("#mdl_iproveedor").select2("val", "1");
    $("#mdl_ifactura").select2("val", "1");
}

jQuery(document).on("click", "#btn_nuevo_item", function(){
    $('#titulo_item').text('CREAR NUEVO ITEM');
    $('#btn_guardar_item').show();
    $('#btn_actualizar_item').hide();
    limpiar_datos_item();
})

jQuery(document).on("click", "#btn_guardar_item", function(){
    
    if ($('#mdl_idescripcion').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#mdl_idescripcion');
        return false;
    }
    
    if ($('#mdl_iserie').val() == '') {
        mostraralertasconfoco('* EL CAMPO SERIE ES OBLIGATORIO...', '#mdl_iserie');
        return false;
    }
    
    if ($('#mdl_icantidad').val() == '') {
        mostraralertasconfoco('* EL CAMPO CANTIDAD ES OBLIGATORIO...', '#mdl_icantidad');
        return false;
    }
    
    if ($('#mdl_iprecio').val() == '') {
        mostraralertasconfoco('* EL CAMPO PRECIO ES OBLIGATORIO...', '#mdl_iprecio');
        return false;
    }
    
    if ($('#mdl_ifec_registro').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA REGISTRO ES OBLIGATORIO...', '#mdl_ifec_registro');
        return false;
    }
    
    $.ajax({
        url: 'items/create',
        type: 'GET',
        data:
        {
            descripcion: $('#mdl_idescripcion').val(),
            serie: $('#mdl_iserie').val(),
            cantidad: $('#mdl_icantidad').val(),
            precio: $('#mdl_iprecio').val(),
            id_proveedor: $("#mdl_iproveedor").val(),
            id_marca: $("#mdl_imarca").val(),
            id_factura: $("#mdl_ifactura").val(),
            fecha: $('#mdl_ifec_registro').val()
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
                jQuery("#tabla_items").jqGrid('setGridParam', {
                    url: 'items/0?grid=items'
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

jQuery(document).on("click", "#btn_modificar_item", function(){
    
    id_item = $('#tabla_items').jqGrid ('getGridParam', 'selrow');
    
    if(id_item){
        $('#btn_nuevo_item').click();
        $('#titulo_item').text('MODIFICAR ITEM');
        $('#btn_guardar_item').hide();
        $('#btn_actualizar_item').show();
        
        $('#mdl_idescripcion').val($('#tabla_items').jqGrid ('getCell', id_item, 'item_desc'));
        $('#mdl_iserie').val($('#tabla_items').jqGrid ('getCell', id_item, 'item_ser'));
        $('#mdl_icantidad').val($('#tabla_items').jqGrid ('getCell', id_item, 'item_cant'));
        $('#mdl_iprecio').val($('#tabla_items').jqGrid ('getCell', id_item, 'item_prec'));
        $('#mdl_ifec_registro').val($('#tabla_items').jqGrid ('getCell', id_item, 'item_fec'));
        $("#mdl_imarca").select2("val", $('#tabla_items').jqGrid ('getCell', id_item, 'id_marca'));
        $("#mdl_iproveedor").select2("val", $('#tabla_items').jqGrid ('getCell', id_item, 'id_proveedor'));
        $("#mdl_ifactura").select2("val", $('#tabla_items').jqGrid ('getCell', id_item, 'id_factura'));
                
    }else{
        mostraralertasconfoco("NO HAY NINGUN ITEM SELECCIONADO","#tabla_items");
    }
    
})

jQuery(document).on("click", "#btn_actualizar_item", function(){
    
    id_item = $('#tabla_items').jqGrid ('getGridParam', 'selrow');
    
    if ($('#mdl_idescripcion').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#mdl_idescripcion');
        return false;
    }
    
    if ($('#mdl_iserie').val() == '') {
        mostraralertasconfoco('* EL CAMPO SERIE ES OBLIGATORIO...', '#mdl_iserie');
        return false;
    }
    
    if ($('#mdl_icantidad').val() == '') {
        mostraralertasconfoco('* EL CAMPO CANTIDAD ES OBLIGATORIO...', '#mdl_icantidad');
        return false;
    }
    
    if ($('#mdl_iprecio').val() == '') {
        mostraralertasconfoco('* EL CAMPO PRECIO ES OBLIGATORIO...', '#mdl_iprecio');
        return false;
    }
    
    if ($('#mdl_ifec_registro').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA REGISTRO ES OBLIGATORIO...', '#mdl_ifec_registro');
        return false;
    }
    
    $.ajax({
        url: 'items/'+id_item+'/edit',
        type: 'GET',
        data:
        {
            descripcion: $('#mdl_idescripcion').val(),
            serie: $('#mdl_iserie').val(),
            cantidad: $('#mdl_icantidad').val(),
            precio: $('#mdl_iprecio').val(),
            id_proveedor: $("#mdl_iproveedor").val(),
            id_marca: $("#mdl_imarca").val(),
            id_factura: $("#mdl_ifactura").val(),
            fecha: $('#mdl_ifec_registro').val(),
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
    
})

jQuery(document).on("click", "#btn_buscar_item", function(){   
    
    if ($('#txt_fecha_desde').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA DESDE ES OBLIGATORIO...', '#txt_fecha_desde');
        return false;
    }
    
    if ($('#txt_fecha_hasta').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA HASTA ES OBLIGATORIO...', '#txt_fecha_hasta');
        return false;
    }
    
    jQuery("#tabla_items").jqGrid('setGridParam', {
        url: 'items/0?grid=buscar_items&serie='+$('#txt_serie').val()+'&descripcion='+$('#txt_descripcion').val()+'&fecha_desde='+$('#txt_fecha_desde').val()+'&fecha_hasta='+$('#txt_fecha_hasta').val()
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