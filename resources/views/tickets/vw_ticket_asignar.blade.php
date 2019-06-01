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
            <h2 class="c-grey-900 text-center">ASIGNAR TICKET</h2>
            <div class="mT-30" style="padding-bottom: 500px;">
<!--                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="titulo" class="fw-500">BUSCAR POR TITULO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="titulo" name="titulo" placeholder="ESCRIBIR TITULO DEL TICKET" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="fw-500">FECHA DESDE:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control bdc-grey-200 start-date rounded" id="fecha_desde" placeholder="SELECCIONAR UNA FECHA" name="fecha_desde" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
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
                                <input type="text" class="form-control bdc-grey-200 start-date rounded" id="fecha_hasta" placeholder="SELECCIONAR UNA FECHA" name="fecha_hasta" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 22px;">
                        <button id="btn_buscar_datos" class="btn btn-danger btn-sm btn-block" type="button"><span class="btn-label"><i class="fa fa-search"></i></span> BUSCAR</button>
                    </div>
                </div>-->

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_asignar_tickets"></table>
                            <div id="paginador_tabla_asignar_tickets"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Asignar_Ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">INFORMACION TECNICOS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="titulo" class="fw-500">SELECCIONE UNA PERSONA:</label>
                        <select id="mdl_personal" class="form-control" style="width: 100%;">
<!--                            <option value="01"> ENERO </option>-->
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal">CERRAR VENTANA</button>
                <button type="button" class="btn btn-primary" id="btn_asignar_ticket">ASIGNAR TICKET</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/tickets/ticket_asignar.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
</script>
@stop
@endsection
