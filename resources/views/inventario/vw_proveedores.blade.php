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
                    <div class="form-group col-md-2" style="padding-top: 29px;">
                        <button id="btn_nuevo_proveedor" data-toggle="modal" data-target="#Modal_Proveedor" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 29px;">
                        <button id="btn_modificar_proveedor" class="btn btn-block" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                    </div>
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
@stop
@endsection
