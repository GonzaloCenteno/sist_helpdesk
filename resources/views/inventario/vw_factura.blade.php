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
            <h2 class="c-grey-900 text-center">FACTURA</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="titulo" class="fw-500">BUSCAR POR FACTURA:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="txt_serie_num" name="txt_serie_num" placeholder="ESCRIBIR SERIE O NUMERO" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="fw-500">FECHA DESDE:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_fecha_desde" placeholder="SELECCIONAR UNA FECHA" name="txt_fecha_desde" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="fw-500">FECHA HASTA:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_fecha_hasta" placeholder="SELECCIONAR UNA FECHA" name="txt_fecha_hasta" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3" style="padding-top: 29px;">                    
                        <button id="btn_buscar_factura" class="btn btn-danger" type="button"><i class="fa fa-search"></i> BUSCAR</button>
                        @if( $permiso[0]->btn_new == 1 )
                            <button id="btn_nueva_factura" data-toggle="modal" data-target="#Modal_Factura" data-backdrop="static" data-keyboard="false" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        @else
                            <button onclick="sin_permiso();" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        @endif
                        @if( $permiso[0]->btn_edit == 1 )
                            <button style="display:none;" id="btn_nueva_factura" data-toggle="modal" data-target="#Modal_Factura" data-backdrop="static" data-keyboard="false" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                            <button id="btn_modificar_factura" class="btn" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        @else
                            <button onclick="sin_permiso();" class="btn" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_facturas"></table>
                            <div id="paginador_tabla_facturas"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Factura" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_factura"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="mdl_serie" class="fw-500">SERIE:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_serie" name="mdl_serie" placeholder="SERIE" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mdl_moneda" class="fw-500">MONEDA:</label>
                        <select id="mdl_moneda" class="form-control text-center text-uppercase rounded" style="width: 100%;">          
                            <option value="0">..:: SOLES S/ ::..</option>
                            <option value="1">..:: DOLARES $ ::..</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_numero" class="fw-500">NUMERO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_numero" name="mdl_numero" placeholder="NUMERO" autocomplete="off">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="mdl_monto" class="fw-500">MONTO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_monto" name="mdl_monto" placeholder="MONTO" autocomplete="off" onkeypress="return soloNumeroTab(event);">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="mdl_fecha" class="fw-500">FECHA:</label>
                        <input type="text" class="form-control start-date rounded" id="mdl_fecha" placeholder="SELECCIONAR UNA FECHA" name="txfecha" data-dateformat='mm/dd/yy' data-mask="99/99/9999" value="<?php echo date("d/m/Y"); ?>" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_producto" class="fw-500">SELECCIONE UN PROVEEDOR:</label>
                        <select id="mdl_producto" class="form-control" style="width: 100%;">
                            @if($num == 1)
                            <option value="{{ $proveedor->IDPRO }}"> {{ $proveedor->RAZSOC }} </option>
                            @else
                            @foreach($proveedor as $prov)
                            <option value="{{ $prov->IDPRO }}"> {{ $prov->RAZSOC }} </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_guardar_factura"><i class="fa fa-plus-square"></i> GUARDAR DATOS</button>
                <button type="button" class="btn btn-primary" id="btn_actualizar_factura"><i class="fa fa-plus-square"></i> MODIFICAR DATOS</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ARCHIVO -->
<div class="modal fade" id="Modal_Archivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_new_archivo"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <form enctype="multipart/form-data" id="FormularioArchivoFact" name="FormularioArchivoFact" method="POST">
                        @csrf
                        <div class="form-group col-md-12">
                            <label class="fw-500">SUBIR ARCHIVO:</label>
                            <div class="timepicker-input input-icon form-group">
                                <div class="input-group">
                                    <div class="input-group-addon bgc-white bd bdwR-0">
                                        <i class="fa fa-folder-open"></i>
                                    </div>
                                    <input type="file" class="form-control rounded" id="file" name="file">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_guardar_arcfactura"><i class="fa fa-plus-square"></i> GUARDAR DATOS</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal_eva"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/inventario/factura.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
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
            colNames: ['ID', 'SERIE', 'NUMERO', 'MONTO', 'FECHA REGISTRO', 'ID_PRODUCTO','PRODUCTO', 'ID_MON', 'MONEDA','ARCHIVAR'],
            rowNum: 20, sortname: 'fact_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_factura" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE FACTURAS - ', align: "center",
            colModel: [
                {name: 'fact_id', index: 'fact_id', align: 'left',width: 10, hidden:true},
                {name: 'fact_serie', index: 'fact_serie', align: 'center', width: 20},
                {name: 'fact_num', index: 'fact_num', align: 'center', width: 20},
                {name: 'fact_monto', index: 'fact_monto', align: 'center', width: 20},
                {name: 'fact_fec', index: 'fact_fec', align: 'center', width: 20},
                {name: 'id_producto', index: 'id_producto', align: 'left', width: 10, hidden:true},
                {name: 'pro_id', index: 'pro_id', align: 'left', width: 40},
                {name: 'id_moneda', index: 'id_moneda', align: 'left', width: 10, hidden:true},
                {name: 'id_mon', index: 'id_mon', align: 'center', width: 10},
                {name: 'fact_img', index: 'fact_img', align: 'center', width: 15}
            ],
            pager: '#paginador_tabla_facturas',
            rowList: [20, 30, 40, 50, 100000000],
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
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    $('#btn_modificar_factura').click();
                }
                else
                {
                    sin_permiso();
                }
            }
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_facturas").jqGrid('setGridWidth', $("#contenedor").width());
        });

        $("#mdl_producto").select2({
            dropdownParent: $("#Modal_Factura")
        });

    });
</script>
@stop
@endsection
