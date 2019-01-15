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
            <h2 class="c-grey-900 text-center">ITEMS</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="txt_descripcion" class="fw-500">BUSCAR POR DESCRIPCION ITEM:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="txt_descripcion" name="txt_descripcion" placeholder="DESCRIPCION ITEM" autocomplete="off">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="txt_serie" class="fw-500">BUSCAR POR FACTURA:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="txt_serie" name="txt_serie" placeholder="SERIE" autocomplete="off">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="fw-500">FECHA DESDE:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_fecha_desde" placeholder="SELECCIONAR FECHA" name="txt_fecha_desde" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="fw-500">FECHA HASTA:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_fecha_hasta" placeholder="SELECCIONAR FECHA" name="txt_fecha_hasta" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3" style="padding-top: 29px;">                    
                        <button id="btn_buscar_item" class="btn btn-danger" type="button"><i class="fa fa-search"></i> BUSCAR</button>
                        <button id="btn_nuevo_item" data-toggle="modal" data-target="#Modal_Item" data-backdrop="static" data-keyboard="false" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        <button id="btn_modificar_item" class="btn" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_items"></table>
                            <div id="paginador_tabla_items"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_item"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="mdl_idescripcion" class="fw-500">DESCRIPCION:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_idescripcion" name="mdl_idescripcion" placeholder="INGRESAR DESCRIPCION" autocomplete="off">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_iserie" class="fw-500">SERIE:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_iserie" name="mdl_iserie" placeholder="INGRESAR SERIE" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mdl_icantidad" class="fw-500">CANTIDAD:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_icantidad" name="mdl_icantidad" placeholder="INGRESAR CANTIDAD" autocomplete="off" onkeypress="return soloNumeroTab(event);">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mdl_iprecio" class="fw-500">PRECIO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="mdl_iprecio" name="mdl_iprecio" placeholder="INGRESAR PRECIO" autocomplete="off" onkeypress="return soloNumeroTab(event);">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="mdl_ifec_registro" class="fw-500">FECHA REGISTRO:</label>
                        <input type="text" class="form-control start-date rounded" id="mdl_ifec_registro" placeholder="SELECCIONAR FECHA" name="mdl_ifec_registro" data-dateformat='mm/dd/yy' data-mask="99/99/9999" value="<?php echo date("m/d/Y"); ?>" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_imarca" class="fw-500">SELECCIONE UNA MARCA:</label>
                        <div class="text-center" id="select_marca_i"><input type="hidden" id="id_marca"><label id="desc_marca_i" class="fw-500"></label></div>
                        <select id="mdl_imarca" onchange="select_marca();" class="form-control" style="width: 100%;">
                            @if($num_mar == 1)
                                <option value="{{ $marca->ID }}"> {{ $marca->DESC }} </option>
                            @else
                                @foreach($marca as $marc)
                                    <option value="{{ $marc->ID }}"> {{ $marc->DESC }} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_iproveedor" class="fw-500">SELECCIONE UN PROVEEDOR:</label>
                        <div class="text-center" id="select_proveedor_i"><input type="hidden" id="id_proveedor"><label id="desc_proveedor_i" class="fw-500"></label></div>
                        <select id="mdl_iproveedor" onchange="select_proveedor();" class="form-control" style="width: 100%;">
                            @if($num_pro == 1)
                                <option value="{{ $proveedor->IDPRO }}"> {{ $proveedor->RAZSOC }} </option>
                            @else
                                @foreach($proveedor as $prov)
                                    <option value="{{ $prov->IDPRO }}"> {{ $prov->RAZSOC }} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_ifactura" class="fw-500">SELECCIONE UNA FACTURA:</label>.
                        <div class="text-center" id="select_factura_i"><input type="hidden" id="id_factura"><label id="desc_factura_i" class="fw-500"></label></div>
                        <select id="mdl_ifactura" onchange="select_factura();" class="form-control" style="width: 100%;">
                            @if($num_fac == 1)
                                <option value="{{ $factura->IDPRO }}"> {{ $factura->SERIE }} - {{ $factura->NUM }}</option>
                            @else
                                @foreach($factura as $fact)
                                    <option value="{{ $fact->IDPRO }}"> {{ $fact->SERIE }} - {{ $fact->NUM }} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_guardar_item"><i class="fa fa-plus-square"></i> GUARDAR DATOS</button>
                <button type="button" class="btn btn-primary" id="btn_actualizar_item"><i class="fa fa-plus-square"></i> MODIFICAR DATOS</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/inventario/item.js') }}"></script>
@stop
@endsection
