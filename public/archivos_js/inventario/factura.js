jQuery(document).ready(function($){
    
    jQuery("#tabla_facturas").jqGrid({
        url: 'facturas/0?grid=facturas',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        pgbuttons: false,
        pgtext: null, 
        //cmTemplate: { sortable: false },
        colNames: ['ID', 'SERIE', 'NUMERO', 'MONTO', 'FECHA REGISTRO', 'ID_PRODUCTO','PRODUCTO'],
        rowNum: 10, sortname: 'fact_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_factura" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE FACTURAS - ', align: "center",
        colModel: [
            {name: 'fact_id', index: 'fact_id', align: 'left',width: 10, hidden:true},
            {name: 'fact_serie', index: 'fact_serie', align: 'center', width: 20},
            {name: 'fact_num', index: 'fact_num', align: 'center', width: 20},
            {name: 'fact_monto', index: 'fact_monto', align: 'center', width: 20},
            {name: 'fact_fec', index: 'fact_fec', align: 'center', width: 20},
            {name: 'id_producto', index: 'id_producto', align: 'left', width: 10, hidden:true},
            {name: 'pro_id', index: 'pro_id', align: 'left', width: 40}
        ],
        pager: '#paginador_tabla_facturas',
        rowList: [10, 20, 30, 40, 50, 100000000],
        loadComplete: function() {
            $("option[value=100000000]").text('TODOS');
        },
        gridComplete: function () {
                var idarray = jQuery('#tabla_facturas').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tabla_facturas').jqGrid('getDataIDs')[0];
                        $("#tabla_facturas").setSelection(firstid);    
                    }
            },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){$('#btn_modificar_factura').click();}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tabla_facturas").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
    $("#mdl_producto").select2({
        dropdownParent: $("#Modal_Factura")
    });
    
});

function limpiar_datos_factura()
{
    var now = new Date();
    var today = (now.getMonth() + 1) + '/' + now.getDate() + '/' + now.getFullYear();
    $('#mdl_serie').val('');
    $('#mdl_numero').val('');
    $('#mdl_monto').val('');
    $('#mdl_fecha').val(today);
    $("#mdl_producto").select2("val", "1");
}

jQuery(document).on("click", "#btn_nueva_factura", function(){
    $('#titulo_factura').text('CREAR NUEVA FACTURA');
    $('#btn_guardar_factura').show();
    $('#btn_actualizar_factura').hide();
    $('#select_proveedor_f').hide();
    limpiar_datos_factura();
})

jQuery(document).on("click", "#btn_guardar_factura", function(){
    
    if ($('#mdl_serie').val() == '') {
        mostraralertasconfoco('* EL CAMPO SERIE ES OBLIGATORIO...', '#mdl_serie');
        return false;
    }
    
    if ($('#mdl_numero').val() == '') {
        mostraralertasconfoco('* EL CAMPO NUMERO ES OBLIGATORIO...', '#mdl_numero');
        return false;
    }
    
    if ($('#mdl_monto').val() == '') {
        mostraralertasconfoco('* EL CAMPO MONTO ES OBLIGATORIO...', '#mdl_monto');
        return false;
    }
    
    if ($('#mdl_fecha').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA ES OBLIGATORIO...', '#mdl_fecha');
        return false;
    }
    
    $.ajax({
        url: 'facturas/create',
        type: 'GET',
        data:
        {
            serie: $('#mdl_serie').val(),
            numero: $('#mdl_numero').val(),
            monto: $('#mdl_monto').val(),
            fecha: $('#mdl_fecha').val(),
            id_producto: $('#mdl_producto').val()
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
                jQuery("#tabla_facturas").jqGrid('setGridParam', {
                    url: 'facturas/0?grid=facturas'
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

jQuery(document).on("click", "#btn_modificar_factura", function(){
    
    id_factura = $('#tabla_facturas').jqGrid ('getGridParam', 'selrow');
    
    if(id_factura){
        $('#btn_nueva_factura').click();
        $('#titulo_factura').text('MODIFICAR FACTURA');
        $('#btn_guardar_factura').hide();
        $('#btn_actualizar_factura').show();
        
        $('#select_proveedor_f').show();
        $('#id_proveedor_f').val($('#tabla_facturas').jqGrid ('getCell', id_factura, 'id_producto'));
        $('#desc_proveedor_f').text('PROVEEDOR ACTUAL: ' + $('#tabla_facturas').jqGrid ('getCell', id_factura, 'pro_id'));
        
        $('#mdl_serie').val($('#tabla_facturas').jqGrid ('getCell', id_factura, 'fact_serie'));
        $('#mdl_numero').val($('#tabla_facturas').jqGrid ('getCell', id_factura, 'fact_num'));
        $('#mdl_monto').val($('#tabla_facturas').jqGrid ('getCell', id_factura, 'fact_monto'));
        $('#mdl_fecha').val($('#tabla_facturas').jqGrid ('getCell', id_factura, 'fact_fec'));
                
    }else{
        mostraralertasconfoco("NO HAY NINGUNA FACTURA SELECCIONADA","#tabla_facturas");
    }
    
})

function select_proveedor()
{
    $('#id_proveedor_f').val($('#mdl_producto').val());
}

jQuery(document).on("click", "#btn_actualizar_factura", function(){
    
    id_factura = $('#tabla_facturas').jqGrid ('getGridParam', 'selrow');
    
    if ($('#mdl_serie').val() == '') {
        mostraralertasconfoco('* EL CAMPO SERIE ES OBLIGATORIO...', '#mdl_serie');
        return false;
    }
    
    if ($('#mdl_numero').val() == '') {
        mostraralertasconfoco('* EL CAMPO NUMERO ES OBLIGATORIO...', '#mdl_numero');
        return false;
    }
    
    if ($('#mdl_monto').val() == '') {
        mostraralertasconfoco('* EL CAMPO MONTO ES OBLIGATORIO...', '#mdl_monto');
        return false;
    }
    
    if ($('#mdl_fecha').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA ES OBLIGATORIO...', '#mdl_fecha');
        return false;
    }
    
    $.ajax({
        url: 'facturas/'+id_factura+'/edit',
        type: 'GET',
        data:
        {
            serie: $('#mdl_serie').val(),
            numero: $('#mdl_numero').val(),
            monto: $('#mdl_monto').val(),
            fecha: $('#mdl_fecha').val(),
            id_producto: $('#id_proveedor_f').val()
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
                jQuery("#tabla_facturas").jqGrid('setGridParam', {
                    url: 'facturas/0?grid=facturas'
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

jQuery(document).on("click", "#btn_buscar_factura", function(){   
    
    if ($('#txt_fecha_desde').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA DESDE ES OBLIGATORIO...', '#txt_fecha_desde');
        return false;
    }
    
    if ($('#txt_fecha_hasta').val() == '') {
        mostraralertasconfoco('* EL CAMPO FECHA HASTA ES OBLIGATORIO...', '#txt_fecha_hasta');
        return false;
    }
    
    jQuery("#tabla_facturas").jqGrid('setGridParam', {
        url: 'facturas/0?grid=buscar_facturas&serie_num='+$('#txt_serie_num').val()+'&fecha_desde='+$('#txt_fecha_desde').val()+'&fecha_hasta='+$('#txt_fecha_hasta').val()
    }).trigger('reloadGrid');
             
})

jQuery(document).on("click", "#btn_act_table_factura", function(){
    jQuery("#tabla_facturas").jqGrid('setGridParam', {
        url: 'facturas/0?grid=facturas'
    }).trigger('reloadGrid');
    
    $('#txt_serie_num').val('');
    $('#txt_fecha_desde').val('');
    $('#txt_fecha_hasta').val('');
})