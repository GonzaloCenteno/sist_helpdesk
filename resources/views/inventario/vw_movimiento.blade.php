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
            <h2 class="c-grey-900 text-center">MOVIMIENTOS</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="txt_descripcion_item" class="fw-500">BUSCAR POR DESCRIPCION ITEM:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="txt_descripcion_item" name="txt_descripcion_item" placeholder="DESCRIPCION ITEM" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="fw-500">FECHA DESDE:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded text-center" id="txt_fecha_desde" placeholder="SELECCIONAR FECHA" name="txt_fecha_desde" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
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
                                <input type="text" class="form-control start-date rounded text-center" id="txt_fecha_hasta" placeholder="SELECCIONAR FECHA" name="txt_fecha_hasta" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3" style="padding-top: 29px;">                    
                        <button id="btn_buscar_movimiento" class="btn btn-danger" type="button"><i class="fa fa-search"></i> BUSCAR</button>
                        @if( $permiso[0]->btn_new == 1 )
                            <button id="btn_nuevo_movimiento" data-toggle="modal" data-target="#Modal_Movimiento" data-backdrop="static" data-keyboard="false" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        @else
                            <button onclick="sin_permiso();" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        @endif
                        @if( $permiso[0]->btn_edit == 1 )
                            <button style="display:none;" id="btn_nuevo_movimiento" data-toggle="modal" data-target="#Modal_Movimiento" data-backdrop="static" data-keyboard="false" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                            <button id="btn_modificar_movimiento" class="btn" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        @else
                            <button onclick="sin_permiso();" class="btn" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_movimientos"></table>
                            <div id="paginador_tabla_movimientos"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Movimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_movimiento"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="mdl_mitem" class="fw-500">SELECCIONE UN ITEM:</label>
                        <select id="mdl_mitem" class="form-control" style="width: 100%;">
                            <option value="0">..:: DESPLEGAR LISTA ::..</option>
                            @if($num_ite == 1)
                                <option value="{{ $item->IDIT }}"> {{ $item->DPIT }} - {{ $item->SEIT }} </option>
                            @else
                                @foreach($item as $it)
                                    <option value="{{ $it->IDIT }}"> {{ $it->DPIT }} - {{ $it->SEIT }} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-12 text-center" id="punto_venta_origen">
                        
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_pvt_destino" class="fw-500">SELECCIONE UN PUNTO DE VENTA - (DESTINO):</label>
                        <select id="mdl_pvt_destino" class="form-control" style="width: 100%;">
                            @foreach($punto_venta as $pvt_d)
                                <option value="{{ $pvt_d->IDPVT }}"> {{ $pvt_d->DPVT }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_fecha" class="fw-500">FECHA:</label>
                        <input type="text" class="form-control start-date rounded text-center" id="mdl_mfecha" placeholder="SELECCIONAR UNA FECHA" name="mdl_mfecha" data-dateformat='mm/dd/yy' data-mask="99/99/9999" value="<?php echo date("d-m-Y"); ?>" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_guardar_movimiento"><i class="fa fa-plus-square"></i> GUARDAR DATOS</button>
                <button type="button" class="btn btn-primary" id="btn_actualizar_movimiento"><i class="fa fa-plus-square"></i> MODIFICAR DATOS</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/inventario/movimiento.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
    jQuery(document).ready(function($){
    
        jQuery("#tabla_movimientos").jqGrid({
            url: 'movimientos/0?grid=movimientos',
            datatype: 'json', mtype: 'GET',
            height: '450px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            pgbuttons: false,
            pgtext: null,
            //cmTemplate: { sortable: false },
            colNames: ['ID', 'IDITEM', 'ITEM', 'IDPVT_O', 'PUNTO VENTA ORIGEN', 'IDPVT_D','PUNTO VENTA DESTINO', 'USUARIO', 'ID_USUARIO', 'FECHA', 'ESTADO'],
            rowNum: 20, sortname: 'mov_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_movimiento" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE MOVIMIENTOS -', align: "center",
            colModel: [
                {name: 'mov_id', index: 'mov_id', align: 'left',width: 10, hidden:true},
                {name: 'id_item', index: 'id_item', align: 'center', width: 15, hidden:true},
                {name: 'item_id', index: 'item_id', align: 'left', width: 30},
                {name: 'id_pvt_ori', index: 'id_pvt_ori', align: 'center', width: 10, hidden:true},
                {name: 'pvt_ori', index: 'pvt_ori', align: 'left', width: 19},
                {name: 'id_pvt_des', index: 'id_pvt_des', align: 'left', width: 20, hidden:true},
                {name: 'pvt_des', index: 'pvt_des', align: 'left', width: 19},
                {name: 'usu_id', index: 'usu_id', align: 'left', width: 10},
                {name: 'id_usuario', index: 'id_usuario', align: 'left', width: 15,hidden:true},
                {name: 'mov_fec', index: 'mov_fec', align: 'center', width: 10},
                {name: 'mov_est', index: 'mov_est', align: 'center', width: 10}
            ],
            pager: '#paginador_tabla_movimientos',
            rowList: [20, 30, 40, 50, 100000000],
            loadComplete: function() {
                $("option[value=100000000]").text('TODOS');
            },
            gridComplete: function () {
                    var idarray = jQuery('#tabla_movimientos').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_movimientos').jqGrid('getDataIDs')[0];
                            $("#tabla_movimientos").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    $('#btn_modificar_movimiento').click();
                }
                else
                {
                    sin_permiso();
                }
            }
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_movimientos").jqGrid('setGridWidth', $("#contenedor").width());
        });

        $("#mdl_mitem").select2({
            dropdownParent: $("#Modal_Movimiento")
        });

        $("#mdl_pvt_destino").select2({
            dropdownParent: $("#Modal_Movimiento")
        });

    });
    
    $('#mdl_mitem').on('select2:selecting', function(e) {
        //console.log('Selecting: ' , e.params.args.data);
        //console.log(e.params.args.data.id);
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'movimientos/'+e.params.args.data.id+'?datos=recuperar_pvt_origen',
            type: 'GET',
            beforeSend:function()
            {            
                $('#punto_venta_origen').block({
                    message: "<p class='ClassMsgBlock'><img src={{ asset('img/cargando.gif') }} style='width: 18px;position: relative;top: -1px;'/>RECUPERANDO INFORMACION</p>",
                    css: { border: '2px solid #006000',background:'white',width: '62%'}
                });
            },
            success: function(data) 
            {
                html = '';
                if (data.msg == 1) 
                { 
                    html = '<input type="hidden" id="mdl_pvt_origen" value="'+data.datos[0].pvt_des+'"><h4>PUNTO ORIGEN: '+data.datos[0].pvt_desc+'</h4>'
                }
                else
                {
                    html = '<input type="hidden" id="mdl_pvt_origen" value="'+data.datos[0].pvt_id+'"><h4>PUNTO ORIGEN: '+data.datos[0].pvt_desc+'</h4>'
                }
                $("#punto_venta_origen").html(html);
                $("#punto_venta_origen").unblock();
            },
            error: function(data) {
                MensajeAdvertencia("hubo un error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
            }
        });
    });
    
</script>
@stop
@endsection
