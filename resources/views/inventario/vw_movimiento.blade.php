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
                        <button id="btn_nuevo_movimiento" data-toggle="modal" data-target="#Modal_Movimiento" data-backdrop="static" data-keyboard="false" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        <button id="btn_modificar_movimiento" class="btn" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
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
                            @if($num_ite == 1)
                                <option value="{{ $item->IDIT }}"> {{ $item->DPIT }} - {{ $item->SEIT }} </option>
                            @else
                                @foreach($item as $it)
                                    <option value="{{ $it->IDIT }}"> {{ $it->DPIT }} - {{ $it->SEIT }} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_pvt_origen" class="fw-500">SELECCIONE UN PUNTO DE VENTA - (ORIGEN):</label>
                        <select id="mdl_pvt_origen" class="form-control" style="width: 100%;">
                            @foreach($punto_venta as $pvt_o)
                                <option value="{{ $pvt_o->IDPVT }}"> {{ $pvt_o->DPVT }} </option>
                            @endforeach
                        </select>
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
                        <input type="text" class="form-control start-date rounded text-center" id="mdl_mfecha" placeholder="SELECCIONAR UNA FECHA" name="mdl_mfecha" data-dateformat='mm/dd/yy' data-mask="99/99/9999" value="<?php echo date("m/d/Y"); ?>" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
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
@stop
@endsection
