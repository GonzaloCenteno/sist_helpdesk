jQuery(document).ready(function($){
    
    jQuery("#tabla_historial_tickets").jqGrid({
        url: 'tickethistorial/0?grid=tickets',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        //cmTemplate: { sortable: false },
        pgbuttons: false,
        pgtext: null, 
        colNames: ['ID', 'TITULO', 'TIPO', 'AREA', 'PRIORIDAD', 'ESTADO', 'FECHA', 'VER TICKET'],
        rowNum: 10, sortname: 'cabt_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_tickets_historial" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE TICKETS CREADOS -', align: "center",
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
        pager: '#paginador_tabla_historial_tickets',
        rowList: [10, 20, 30, 40, 50, 100000000],
        loadComplete: function() {
            $("option[value=100000000]").text('TODOS');
        },
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tabla_historial_tickets").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
});

jQuery(document).on("click", "#btn_buscar_historial", function(){
   
    jQuery("#tabla_historial_tickets").jqGrid('setGridParam', {
        url: 'tickethistorial/0?grid=buscar_tickets&titulo='+$('#titulo_ht').val()+'&fecha_desde='+$('#fecha_desde_ht').val()+'&fecha_hasta='+$('#fecha_hasta_ht').val()
    }).trigger('reloadGrid');
        
})

jQuery(document).on("click", "#btn_act_table_tickets_historial", function(){
    jQuery("#tabla_historial_tickets").jqGrid('setGridParam', {
        url: 'tickethistorial/0?grid=tickets'
    }).trigger('reloadGrid');
    
    $('#titulo_ht').val('');
    $('#fecha_desde_ht').val('');
    $('#fecha_hasta_ht').val('');
})

jQuery(document).on("click", "#btn_ver_info_encuesta", function(){
    id_ticket = $('#tabla_historial_tickets').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        url: 'tickethistorial/0?show=traer_historial_encuesta',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('CARGANDO INFORMACION');  
        },
        data:
        {
            id_ticket:id_ticket
        },
        success: function(data) 
        {     
            if (data.rpta == 0) 
            {
                MensajeAdvertencia("EL TICKET NO TIENE ENCUESTA REALIZADA");
                html="";

                html = html+'<div class="form-group col-md-7"><label for="preguntas" class="fw-500"><b> --- </b></label></div>\n\
                             <div class="form-group col-md-2"><label for="preguntas" class="fw-500"><b> --- </b></label></div>';
                
                $('.modal-body').scrollTop(0);
                $('#mdl_obsr_encuesta').text("----");
                $("#info_encuesta").html(html);
            }
            else
            {
                $('#btn_cerrar_sesion').click();
                html="";
                for(i=0;i<data.encuesta.length;i++)
                {
                    html = html+'<div class="form-group col-md-7"><label for="preguntas" class="fw-500"><b> '+data.encuesta[i].pre_desc+' </b></label></div>\n\
                                 <div class="form-group col-md-2"><label for="preguntas" class="fw-500"><b> '+data.encuesta[i].val_desc+' </b></label></div>\n\
                                 <img class="form-control text-center" src="data:image/png;base64,'+data.encuesta[i].val_img+'" border="0" style="width: 80px;height: 60px;"/>';
                }
                $('.modal-body').scrollTop(0);
                $('#mdl_obsr_encuesta').html(data.observacion[0].obs_desc);
                $("#info_encuesta").html(html);
                swal.close();
            }
            //console.log(data.observacion[0].obs_desc);
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
})