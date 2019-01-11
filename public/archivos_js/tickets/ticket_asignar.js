jQuery(document).ready(function($){
    
    jQuery("#tabla_asignar_tickets").jqGrid({
        url: 'ticketasignar/0?grid=asignar_tickets',
        datatype: 'json', mtype: 'GET',
        height: '450px', autowidth: true,
        toolbarfilter: true,
        sortable:false,
        //cmTemplate: { sortable: false },
        colNames: ['ID', 'TITULO', 'TIPO', 'AREA', 'PRIORIDAD', 'ESTADO', 'VER TICKET', 'ASIGNAR TICKET'],
        rowNum: 20, sortname: 'cabt_id', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE TICKETS POR ASIGNAR', align: "center",
        colModel: [
            {name: 'cabt_id', index: 'cabt_id', align: 'left',width: 20, hidden: true},
            {name: 'cabt_asunto', index: 'cabt_asunto', align: 'left', width: 40},
            {name: 'tip_desc', index: 'tip_desc', align: 'left', width: 20},
            {name: 'are_desc', index: 'are_desc', align: 'left', width: 25},
            {name: 'prio_desc', index: 'prio_desc', align: 'left', width: 12},
            {name: 'desc_est', index: 'desc_est', align: 'left', width: 20},
            {name: 'cabt_feccre', index: 'cabt_feccre', align: 'left', width: 18},
            {name: 'cabt_id', index: 'cabt_id', align: 'center', width: 25}
        ],
        pager: '#paginador_tabla_asignar_tickets',
        rowList: [10, 20, 30, 40, 50],
        onSelectRow: function (Id){},
        ondblClickRow: function (Id){}
    });
    
    $(window).on('resize.jqGrid', function () {
        $("#tabla_asignar_tickets").jqGrid('setGridWidth', $("#contenedor").width());
    });
    
});

function asignar_ticket()
{
    $.ajax({
        url: 'ticketasignar/0?datos=traer_personal',
        type: 'GET',
        beforeSend:function()
        {            
            MensajeEspera('CARGANDO INFORMACION');  
        },
        success: function(data) 
        {
            html="";
            if (data.respuesta == '00000') 
            {
                if (data.nro_tecnicos == 1) 
                {
                    html = html+'<option value='+data.datos.IDTIC+'>'+data.datos.USUNOM+'</option>';
                }
                else
                {
                    for(i=0;i<data.datos.length;i++)
                    {
                        html = html+'<option value='+data.datos[i].IDTIC+'>'+data.datos[i].USUNOM+'</option>';
                    }
                }
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL CARGAR LA INFORMACION');
                console.log(data);
            }
            
            $("#mdl_personal").select2({
                dropdownParent: $("#Modal_Asignar_Ticket")
            });
            $("#mdl_personal").html(html);
            swal.close();
        },
        error: function(data) {
            MensajeAdvertencia("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

jQuery(document).on("click", "#btn_asignar_ticket", function(){
    
    id_ticket = $('#tabla_asignar_tickets').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        url: 'ticketasignar/'+id_ticket+'/edit',
        type: 'GET',
        data:{
            id_tecnico:$('#mdl_personal').val()
        },
        beforeSend:function()
        {            
            MensajeEspera('GUARDANDO INFORMACION');  
        },
        success: function(data) 
        {
            if (data.respuesta == '00000') 
            {
                MensajeConfirmacion(data.mensaje);
                $('#btn_cerrar_modal').click();
                jQuery("#tabla_asignar_tickets").jqGrid('setGridParam', {
                    url: 'ticketasignar/0?grid=asignar_tickets'
                }).trigger('reloadGrid');
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
