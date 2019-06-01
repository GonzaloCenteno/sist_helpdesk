@extends('principal.p_inicio')

@section('content')
<style>
.modal-body {
    max-height: calc(100vh - 210px);
    overflow-y: auto;
}       
</style>
<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>
    <div class="masonry-item col-md-12">
        <div class="bgc-white p-20 bd">
            <h2 class="c-grey-900 text-center">PROVEEDORES</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="txt_razon_social" class="fw-500">BUSCAR POR RAZON SOCIAL:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="txt_razon_social" name="txt_razon_social" placeholder="ESCRIBIR RAZON SOCIAL" autocomplete="off">
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 29px;">
                        <button id="btn_buscar_proveedor" class="btn btn-danger btn-block" type="button"><i class="fa fa-search"></i> BUSCAR</button>
                    </div>
                    @if( $permiso[0]->btn_new == 1 )
                        <div class="form-group col-md-2" style="padding-top: 29px;">
                            <button id="btn_nuevo_proveedor" data-toggle="modal" data-target="#Modal_Proveedor" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        </div>
                    @else
                        <div class="form-group col-md-2" style="padding-top: 29px;">
                            <button onclick="sin_permiso();" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        </div>
                    @endif
                    @if( $permiso[0]->btn_edit == 1 )
                        <button style="display:none;" id="btn_nuevo_proveedor" data-toggle="modal" data-target="#Modal_Proveedor" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        <div class="form-group col-md-2" style="padding-top: 29px;">
                            <button id="btn_modificar_proveedor" class="btn btn-block" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        </div>
                    @else
                        <div class="form-group col-md-2" style="padding-top: 29px;">
                            <button class="btn btn-block" onclick="sin_permiso();" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        </div>
                    @endif
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_proveedores"></table>
                            <div id="paginador_tabla_proveedores"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Proveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_proveedor"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="mdl_razon_social" class="fw-500">RAZON SOCIAL:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_razon_social" name="mdl_razon_social" placeholder="ESCRIBIR RAZON SOCIAL" autocomplete="off">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_ruc" class="fw-500">RUC:</label>
                        <input type="text" class="form-control text-center rounded" id="mdl_ruc" maxlength="11" name="mdl_ruc" placeholder="ESCRIBIR RUC" autocomplete="off" onkeypress="return soloNumeroTab(event);">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_telefono" class="fw-500">TELEFONO:</label>
                        <input type="text" class="form-control text-center rounded" id="mdl_telefono" name="mdl_telefono" placeholder="ESCRIBIR TELEFONO" autocomplete="off" onkeypress="return soloNumeroTab(event);">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_contacto" class="fw-500">CONTACTO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_contacto" name="mdl_contacto" placeholder="ESCRIBIR NOMBRE CONTACTO" autocomplete="off">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_contacto" class="fw-500">DIRECCION:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_direccion" name="mdl_direccion" placeholder="ESCRIBIR DIRECCION" autocomplete="off">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_contacto" class="fw-500">SERVICIO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_servicio" name="mdl_servicio" placeholder="ESCRIBIR PRODUCTO/SERVICIO" autocomplete="off">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_contacto" class="fw-500">CORREO:</label>
                        <input type="email" class="form-control text-center text-uppercase rounded" id="mdl_correo" name="mdl_correo" placeholder="ESCRIBIR CORREO ELECTRONICO" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_guardar_proveedor"><i class="fa fa-plus-square"></i> GUARDAR DATOS</button>
                <button type="button" class="btn btn-primary" id="btn_actualizar_proveedor"><i class="fa fa-plus-square"></i> MODIFICAR DATOS</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/inventario/proveedores.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
    jQuery(document).ready(function($){
    
        jQuery("#tabla_proveedores").jqGrid({
            url: 'proveedor/0?grid=proveedores',
            datatype: 'json', mtype: 'GET',
            height: '450px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            pgbuttons: false,
            pgtext: null,
            colNames: ['ID', 'RAZON SOCIAL', 'RUC', 'TELEFONO', 'CONTACTO', 'DIRECCION', 'SERVICIO', 'CORREO', 'ESTADO'],
            rowNum: 10, sortname: 'pro_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_proveedor" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE PROVEEDORES -', align: "center",
            colModel: [
                {name: 'pro_id', index: 'pro_id', align: 'left',width: 10, hidden:true},
                {name: 'pro_raz', index: 'pro_raz', align: 'left', width: 30},
                {name: 'pro_ruc', index: 'pro_ruc', align: 'center', width: 8},
                {name: 'pro_tel', index: 'pro_tel', align: 'center', width: 8},
                {name: 'pro_con', index: 'pro_con', align: 'left', width: 10},
                {name: 'pro_dir', index: 'pro_dir', align: 'left', width: 20},
                {name: 'pro_serv', index: 'pro_serv', align: 'left', width: 10},
                {name: 'pro_correo', index: 'pro_correo', align: 'left', width: 12},
                {name: 'pro_est', index: 'pro_est', align: 'center', width: 10}
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
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    $('#btn_modificar_proveedor').click();
                }
                else
                {
                    sin_permiso();
                }
            }
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
</script>
@stop
@endsection
