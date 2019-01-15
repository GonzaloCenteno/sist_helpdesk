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
            <h2 class="c-grey-900 text-center">TICKET ASIGNADOS</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="titulo" class="fw-500">BUSCAR POR TITULO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="txt_titulo" name="txt_titulo" placeholder="ESCRIBIR TITULO DEL TICKET" autocomplete="off">
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
                    <div class="form-group col-md-2" style="padding-top: 29px;">
                        <button id="btn_buscar_ticket_asignados" class="btn btn-danger btn-block" type="button"><i class="fa fa-search"></i> BUSCAR</button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_tickets_asignados"></table>
                            <div id="paginador_tabla_tickets_asignados"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Ticket_Asignados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">INFORMACION TICKET</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="titulo" class="fw-500">TITULO:</label>
                        <label class="bdc-grey-200" id="mdl_titulo"></label>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="titulo" class="fw-500">ESTADO:</label>
                        <label class="bdc-grey-200" id="mdl_estado"></label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="titulo" class="fw-500">TIPO:</label>
                        <label class="bdc-grey-200" id="mdl_tipo"></label>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="titulo" class="fw-500">AREA:</label>
                        <label class="bdc-grey-200" id="mdl_area"></label>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="titulo" class="fw-500">PRIORIDAD:</label>
                        <label class="bdc-grey-200" id="mdl_prioridad"></label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="titulo" class="fw-500">FECHA CREACION:</label>
                        <label class="bdc-grey-200" id="mdl_fecha_creacion_cab"></label>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="titulo" class="fw-500">FECHA ACTUALIZACION:</label>
                        <label class="bdc-grey-200" id="mdl_fecha_actualizacion"></label>
                    </div>
                </div>
                <hr>
                @if(session('rol') == 1 || session('rol') == 2)
                    <center><h4>CAMBIAR PRIORIDAD</h4></center>
                    <div class="form-row">
                        <div class="form-group col-md-9">
                            <select id="prioridad_asignados" name="prioridad_asignados" class="form-control rounded">
                                @foreach($prioridad as $pr)          
                                    <option value="{{ $pr->prio_id }}">{{ $pr->prio_desc }}</option>
                                @endforeach             
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <button type="button" class="btn btn-primary" id="btn_prioridad_asignados">MODIFICAR</button>
                        </div>
                    </div>
                    <hr>
                @else
                @endif
                <center><h3>RESPUESTAS</h3></center>
                <div class="form-row" id="detalle">
<!--                    <div class="form-group col-md-12">
                        <label for="titulo" class="fw-500">DESCRIPCION:</label>
                        <label class="form-control bdc-grey-200" id="mdl_descripcion"></label>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="titulo" class="fw-500">FECHA CREACION:</label>
                        <label class="bdc-grey-200" id="mdl_fecha_creacion_det"></label>
                    </div>-->
<!--                    <div class="form-group col-md-6">
                        <button id="btn_ver_archivo" class="btn btn-danger btn-sm btn-block" type="button"><span class="btn-label"><i class="fa fa-search"></i></span> VER ARCHIVO</button>
                    </div>-->
                </div>
                <hr>
                <div class="form-group">
                    <label for="editor" class="fw-500">RESPUESTA:</label>
                    <textarea  id="mdl_nueva_descripcion" name="mdl_nueva_descripcion">INGRESE UNA DESCRIPCION</textarea >
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal">CERRAR VENTANA</button>
                @if(session('rol') == 1 || session('rol') == 2)
                    <button type="button" class="btn btn-danger" id="btn_rechazar_ticket">RECHAZAR TICKET</button>
                @else
                @endif
                <button type="button" class="btn btn-primary" id="btn_responder_ticket_asignados">RESPONDER TICKET</button>
                <button type="button" class="btn btn-success" id="btn_cerrar_ticket_asignados">CERRAR TICKET</button>
            </div>
        </div>
    </div>
</div>


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/tickets/ticket_asignados.js') }}"></script>
@stop
@endsection
