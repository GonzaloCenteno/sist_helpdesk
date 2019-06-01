function imprimir_reporte_01(valor)
{
    if (valor === 1) 
    {
        window.open('rep_gerenciales/0?show=lista_proveedores');
    }
    if (valor ===2) 
    {
        window.open('rep_gerenciales/0?show=lista_proveedores_excel');
    }
}

jQuery(document).on("click", "#btn_imprimir_rep_02", function(){
    $("#txt_print_fec_inicio").val('');
    $("#txt_print_fec_fin").val('');
});

function imprimir_reporte_02(valor)
{
    fecha_inicio = $("#txt_print_fec_inicio").val();
    fecha_fin = $("#txt_print_fec_fin").val();
    if (fecha_inicio == '') {
        mostraralertasconfoco('* EL CAMPO FECHA INICIO ES OBLIGATORIO', '#txt_print_fec_inicio');
        return false;
    }
    if (fecha_fin == '') {
        mostraralertasconfoco('* EL CAMPO FECHA FIN ES OBLIGATORIO', '#txt_print_fec_fin');
        return false;
    }
    
    if (valor === 1) 
    {
        window.open('rep_gerenciales/0?show=evaluaciones&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin);
    }
    if (valor ===2) 
    {
        window.open('rep_gerenciales/0?show=evaluaciones_excel&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin);
    }
}

jQuery(document).on("click", "#btn_imprimir_rep_03", function(){
    $("#txt_print_fec_inicio_rep_03").val('');
    $("#txt_print_fec_fin_rep_03").val('');
});

function imprimir_reporte_03(valor)
{
    fecha_inicio = $("#txt_print_fec_inicio_rep_03").val();
    fecha_fin = $("#txt_print_fec_fin_rep_03").val();
    if (fecha_inicio == '') {
        mostraralertasconfoco('* EL CAMPO FECHA INICIO ES OBLIGATORIO', '#txt_print_fec_inicio_rep_03');
        return false;
    }
    if (fecha_fin == '') {
        mostraralertasconfoco('* EL CAMPO FECHA FIN ES OBLIGATORIO', '#txt_print_fec_fin_rep_03');
        return false;
    }
    
    if (valor === 1) 
    {
        window.open('rep_gerenciales/0?show=incidentes&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin);
    }
    if (valor ===2) 
    {
        window.open('rep_gerenciales/0?show=incidentes_excel&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin);
    }
}

jQuery(document).on("click", "#btn_imprimir_rep_04", function(){
    $("#txt_print_fec_inicio_rep_04").val('');
    $("#txt_print_fec_fin_rep_04").val('');
});

function imprimir_reporte_04(valor)
{
    fecha_inicio = $("#txt_print_fec_inicio_rep_04").val();
    fecha_fin = $("#txt_print_fec_fin_rep_04").val();
    if (fecha_inicio == '') {
        mostraralertasconfoco('* EL CAMPO FECHA INICIO ES OBLIGATORIO', '#txt_print_fec_inicio_rep_04');
        return false;
    }
    if (fecha_fin == '') {
        mostraralertasconfoco('* EL CAMPO FECHA FIN ES OBLIGATORIO', '#txt_print_fec_fin_rep_04');
        return false;
    }
    
    if (valor === 1) 
    {
        window.open('rep_gerenciales/0?show=calificacion&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin);
    }
    if (valor ===2) 
    {
        window.open('rep_gerenciales/0?show=calificacion_excel&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin);
    }
}