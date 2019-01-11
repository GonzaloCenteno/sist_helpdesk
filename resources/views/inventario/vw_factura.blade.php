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
                        <button id="btn_nueva_factura" data-toggle="modal" data-target="#Modal_Factura" data-backdrop="static" data-keyboard="false" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        <button id="btn_modificar_factura" class="btn" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
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
                    <div class="form-group col-md-12">
                        <label for="mdl_serie" class="fw-500">SERIE:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_serie" name="mdl_serie" placeholder="SERIE" autocomplete="off">
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
                        <input type="text" class="form-control start-date rounded" id="mdl_fecha" placeholder="SELECCIONAR UNA FECHA" name="txfecha" data-dateformat='mm/dd/yy' data-mask="99/99/9999" value="<?php echo date("m/d/Y"); ?>" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
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


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/inventario/factura.js') }}"></script>
@stop
@endsection
