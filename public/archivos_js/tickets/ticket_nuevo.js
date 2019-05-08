jQuery(document).ready(function($){
    
    CKEDITOR.replace('descripcion');
    
});

function limpiar_datos()
{
    var now = new Date();
    var today =  now.getDate() + '/' + (now.getMonth() + 1) + '/' + now.getFullYear();
    $('#cbxtipo').val('0');
    $('#cbxarea').val('0');
    $('#cbxpri').val('0');
    $('#txfecha').val(today);
    $('#intitulo').val('');
    CKEDITOR.instances['descripcion'].setData('');
    $('#file').val('');
}

jQuery(document).on("click", "#btn_enviar_datos", function(){
    tipo = $('#cbxtipo').val();
    area = $('#cbxarea').val();
    prioridad = $('#cbxpri').val();
    fecha = $('#txfecha').val();
    titulo = $('#intitulo').val();
    var descripcion = CKEDITOR.instances['descripcion'].getData();

    if (tipo == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UN TIPO...', '#cbxtipo');
        return false;
    }
    if (area == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UN AREA...', '#cbxarea');
        return false;
    }
    if (prioridad == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UN PRIORIDAD...', '#cbxpri');
        return false;
    }
    if (fecha == '') {
        mostraralertasconfoco('* EL CAMPO FECHA ES OBLIGATORIO...', '#txfecha');
        return false;
    }
    if (titulo == '') {
        mostraralertasconfoco('* EL CAMPO TITULO ES OBLIGATORIO...', '#intitulo');
        return false;
    }
    if (descripcion == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#descripcion');
        return false;
    }
    
    for ( instance in CKEDITOR.instances )
       CKEDITOR.instances[instance].updateElement();

    
    var form= new FormData($("#FormularioTicket")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'ticketnuevo',
        type: 'POST',
        dataType: 'json',
        data: form,
        processData: false,
        contentType: false,
        beforeSend:function(){            
            MensajeEspera('ENVIANDO INFORMACION');        
        },
        success: function (data) {
            //console.log(data);
            if(data.respuesta[0] == '00000')
            {
                limpiar_datos();
                MensajeConfirmacion(data.mensaje[0] + ' ' + data.texto);
            }
            else if(data.respuesta[0] == '90001')
            {
                MensajeAdvertencia(data.mensaje[0]);
            }
            else
            {
                ValidacionServidor(data.error);
            }
        },
        error: function(data) {
            MensajeAdvertencia('* Error de Red...<br>* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });
})