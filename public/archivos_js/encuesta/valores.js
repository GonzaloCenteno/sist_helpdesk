
function limpiar_datos_valores()
{
    $('#desc_valor').val('');
    $('#img1_valor').val('');
    $('#img2_valor').val('');
    $("#form_imagen1").attr("src","img/product.png");
    $("#form_imagen2").attr("src","img/product.png");
}

jQuery(document).on("click", "#btn_nuevo_valor", function(){
    $('#titulo_valor').text('CREAR NUEVO VALOR');
    $('#btn_guardar_valor').show();
    $('#btn_actualizar_valor').hide();
    limpiar_datos_valores();
})

jQuery(document).on("click", "#btn_guardar_valor", function(){
    
    if ($('#desc_valor').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#desc_valor');
        return false;
    }
    
    if ($('#img1_valor').val() == '') {
        mostraralertasconfoco('* EL CAMPO IMAGEN 1 ES OBLIGATORIO...', '#img1_valor');
        return false;
    }
    
    if ($('#img2_valor').val() == '') {
        mostraralertasconfoco('* EL CAMPO IMAGEN 2 ES OBLIGATORIO...', '#img2_valor');
        return false;
    }
    
    var datos = new FormData($("#FormularioValor")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'valores?tipo=1',
        type: 'POST',
        dataType: 'json',
        data: datos,
        processData: false,
        contentType: false,
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function (data) 
        {
            if (data.respuesta == '00000') 
            {
                MensajeConfirmacion(data.mensaje);
                $('#btn_cerrar_modal').click();
                jQuery("#tabla_valores").jqGrid('setGridParam', {
                    url: 'valores/0?grid=valores'
                }).trigger('reloadGrid');
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL ENVIAR LA INFORMACION');
                console.log(data.mensaje);
            }
        },
        error: function(data) {
            MensajeAdvertencia('* Error de Red...<br>* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });
    
})

jQuery(document).on("click", "#btn_modificar_valor", function(){
    
    id_valor = $('#tabla_valores').jqGrid ('getGridParam', 'selrow');
    
    if(id_valor){
        
        $.ajax({
            url: 'valores/'+id_valor+'?show=datos_valor',
            type: 'GET',
            beforeSend:function(){            
                MensajeEspera('CARGANDO INFORMACION');  
            },
            success: function(data) 
            {
                $('#btn_nuevo_valor').click();
                $('#titulo_valor').text('MODIFICAR VALOR');
                $('#btn_guardar_valor').hide();
                $('#btn_actualizar_valor').show();
                
                $("#desc_valor").val(data[0].val_desc);
                $("#form_imagen1").attr("src","data:image/png;base64,"+data[0].val_img);
                $("#form_imagen2").attr("src","data:image/png;base64,"+data[0].val_img2);
                swal.close();
            },
            error: function(data) {
                MensajeAdvertencia("hubo un error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
            }
        });
        
    }else{
        mostraralertasconfoco("NO HAY NINGUN VALOR SELECCIONADO","#tabla_valores");
    }
    
})

jQuery(document).on("click", "#btn_actualizar_valor", function(){
    
    id_valor = $('#tabla_valores').jqGrid ('getGridParam', 'selrow');
    
    if ($('#desc_valor').val() == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#desc_valor');
        return false;
    }
    
    if ($('#img1_valor').val() == '') {
        mostraralertasconfoco('* EL CAMPO IMAGEN 1 ES OBLIGATORIO...', '#img1_valor');
        return false;
    }
    
    if ($('#img2_valor').val() == '') {
        mostraralertasconfoco('* EL CAMPO IMAGEN 2 ES OBLIGATORIO...', '#img2_valor');
        return false;
    }
    
    var datos = new FormData($("#FormularioValor")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'valores?tipo=2&id_valor='+id_valor,
        type: 'POST',
        dataType: 'json',
        data: datos,
        processData: false,
        contentType: false,
        beforeSend:function()
        {            
            MensajeEspera('ENVIANDO INFORMACION');  
        },
        success: function (data) 
        {
            if (data.respuesta == '00000') 
            {
                MensajeConfirmacion(data.mensaje);
                $('#btn_cerrar_modal').click();
                jQuery("#tabla_valores").jqGrid('setGridParam', {
                    url: 'valores/0?grid=valores'
                }).trigger('reloadGrid');
            }
            else
            {
                MensajeAdvertencia('OCURRIO UN PROBLEMA AL ENVIAR LA INFORMACION');
                console.log(data.mensaje);
            }
        },
        error: function(data) {
            MensajeAdvertencia('* Error de Red...<br>* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });
    
})

function cambiar_estado_valor(id_valor,estado)
{
    $.ajax({
        url: 'valores/'+id_valor+'/edit',
        type: 'GET',
        data:
        {
            estado: estado,
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
                jQuery("#tabla_valores").jqGrid('setGridParam', {
                    url: 'valores/0?grid=valores'
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

jQuery(document).on("click", "#btn_act_table_valores", function(){
    jQuery("#tabla_valores").jqGrid('setGridParam', {
        url: 'valores/0?grid=valores'
    }).trigger('reloadGrid');
})

function validarExtensionArchivo1(){
    var fileInput = document.getElementById('img1_valor');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.png|\.jpg|\.jpeg)$/i;
    var file = fileInput.files[0];
    if(!allowedExtensions.exec(filePath)){
        alertaArchivo("SOLO SE PUEDEN SUBIR ARCHIVOS DE TIPO .PNG / .JPG / .JPEG");
        fileInput.value = '';
        $("#form_imagen1").attr("src","img/product.png");
        return false;
    }
//    else if(file.size > 380000){
//        alertaArchivo("IMAGEN EXEDE EL TAMAÑO PERMITIDO");
//        fileInput.value = '';
//        $("#form_imagen1").attr("src","img/product.png");
//        return false;
//    }
    else{
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#form_imagen1").attr("src",e.target.result);
            }
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}

function validarExtensionArchivo2(){
    var fileInput = document.getElementById('img2_valor');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.png|\.jpg|\.jpeg)$/i;
    var file = fileInput.files[0];
    if(!allowedExtensions.exec(filePath)){
        alertaArchivo("SOLO SE PUEDEN SUBIR ARCHIVOS DE TIPO .PNG / .JPG / .JPEG");
        fileInput.value = '';
        $("#form_imagen2").attr("src","img/product.png");
        return false;
    }
//    else if(file.size > 380000){
//        alertaArchivo("IMAGEN EXEDE EL TAMAÑO PERMITIDO");
//        fileInput.value = '';
//        $("#form_imagen2").attr("src","img/product.png");
//        return false;
//    }
    else{
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#form_imagen2").attr("src",e.target.result);
            }
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}