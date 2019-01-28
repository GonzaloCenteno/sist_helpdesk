jQuery(document).ready(function($){
    
    jQuery("#tabla_preguntas").jqGrid({
        url: 'preguntas/0?grid=preguntas',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        pgbuttons: false,
        pgtext: null,
        //cmTemplate: { sortable: false },
        colNames: ['ID', 'DESCRIPCION','ESTADO'],
        rowNum: 10, sortname: 'pre_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_preguntas" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE PREGUNTAS -', align: "center",
        colModel: [
            {name: 'pre_id', index: 'pre_id', align: 'left',width: 10, hidden:true},
            {name: 'pre_desc', index: 'pre_desc', align: 'left', width: 80},
            {name: 'pre_est', index: 'pre_est', align: 'center', width: 20}
        ],
        pager: '#paginador_tabla_preguntas',
        rowList: [10, 20, 30, 40, 50, 100000000],
        loadComplete: function() {
            $("option[value=100000000]").text('TODOS');
        },
        gridComplete: function () {
                var idarray = jQuery('#tabla_preguntas').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tabla_preguntas').jqGrid('getDataIDs')[0];
                        $("#tabla_preguntas").setSelection(firstid);    
                    }
            },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){$('#btn_modificar_pregunta').click();}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tabla_preguntas").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
});

function limpiar_datos_pregunta()
{
    $('#desc_pregunta').val('');
}

jQuery(document).on("click", "#btn_nueva_pregunta", function(){
    $('#titulo_pregunta').text('CREAR NUEVA PREGUNTA');
    $('#btn_guardar_pregunta').show();
    $('#btn_actualizar_pregunta').hide();
    limpiar_datos_pregunta();
})

jQuery(document).on("click", "#btn_guardar_pregunta", function(){
    
    if ($('#desc_pregunta').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#desc_pregunta');
        return false;
    }
    
    $.ajax({
        url: 'preguntas/create',
        type: 'GET',
        data:
        {
            descripcion: $('#desc_pregunta').val()
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
                jQuery("#tabla_preguntas").jqGrid('setGridParam', {
                    url: 'preguntas/0?grid=preguntas'
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

jQuery(document).on("click", "#btn_modificar_pregunta", function(){
    
    id_pregunta = $('#tabla_preguntas').jqGrid ('getGridParam', 'selrow');
    
    if(id_pregunta){
        
        $('#btn_nueva_pregunta').click();
        $('#titulo_pregunta').text('MODIFICAR PREGUNTA');
        $('#btn_guardar_pregunta').hide();
        $('#btn_actualizar_pregunta').show();
        
        $('#desc_pregunta').val($('#tabla_preguntas').jqGrid ('getCell', id_pregunta, 'pre_desc'));
        
    }else{
        mostraralertasconfoco("NO HAY NINGUNA PREGUNTA SELECCIONADA","#tabla_preguntas");
    }
    
})

jQuery(document).on("click", "#btn_actualizar_pregunta", function(){
    
    id_pregunta = $('#tabla_preguntas').jqGrid ('getGridParam', 'selrow');
    
    if ($('#desc_pregunta').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#desc_pregunta');
        return false;
    }
    
    $.ajax({
        url: 'preguntas/'+id_pregunta+'/edit',
        type: 'GET',
        data:
        {
            descripcion: $('#desc_pregunta').val(),
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
                jQuery("#tabla_preguntas").jqGrid('setGridParam', {
                    url: 'preguntas/0?grid=preguntas'
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

function cambiar_estado_pregunta(id_pregunta,estado)
{
    $.ajax({
        url: 'preguntas/'+id_pregunta+'/edit',
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
                jQuery("#tabla_preguntas").jqGrid('setGridParam', {
                    url: 'preguntas/0?grid=preguntas'
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

jQuery(document).on("click", "#btn_act_table_preguntas", function(){
    jQuery("#tabla_preguntas").jqGrid('setGridParam', {
        url: 'preguntas/0?grid=preguntas'
    }).trigger('reloadGrid');
})