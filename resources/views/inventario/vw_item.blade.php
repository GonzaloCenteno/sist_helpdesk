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
                        @if( $permiso[0]->btn_new == 1 )
                            <button id="btn_nuevo_item" data-toggle="modal" data-target="#Modal_Item" data-backdrop="static" data-keyboard="false" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        @else
                            <button onclick="sin_permiso();" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        @endif
                        @if( $permiso[0]->btn_edit == 1 )
                            <button style="display:none;" id="btn_nuevo_item" data-toggle="modal" data-target="#Modal_Item" data-backdrop="static" data-keyboard="false" class="btn btn-success" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                            <button id="btn_modificar_item" class="btn" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        @else
                            <button onclick="sin_permiso();" class="btn" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        @endif
                        
                        
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
                        <input type="text" class="form-control start-date rounded" id="mdl_ifec_registro" placeholder="SELECCIONAR FECHA" name="mdl_ifec_registro" data-dateformat='mm/dd/yy' data-mask="99/99/9999" value="<?php echo date("d/m/Y"); ?>" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_imarca" class="fw-500">SELECCIONE UNA MARCA:</label>
                        <select id="mdl_imarca" class="form-control" style="width: 100%;">
                            @for ($x = 0; $x < $nummar; $x++)          
                                <option value="{{ $datos['MARCA'][$x]->ID }}">{{ $datos['MARCA'][$x]->DESC }}</option>
                            @endfor 
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_iproveedor" class="fw-500">SELECCIONE UN PROVEEDOR:</label>
                        <select id="mdl_iproveedor" class="form-control" style="width: 100%;">
                            @for ($x = 0; $x < $numpro; $x++)          
                                <option value="{{ $datos['PROVEEDOR'][$x]->IDPRO }}">{{ $datos['PROVEEDOR'][$x]->RAZSOC }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="mdl_ifactura" class="fw-500">SELECCIONE UNA FACTURA:</label>.
                        <select id="mdl_ifactura" class="form-control" style="width: 100%;">
                            @for ($x = 0; $x < $numfac; $x++)          
                                <option value="{{ $datos['FACTURA'][$x]->IDPRO }}">{{ $datos['FACTURA'][$x]->SERIE }} - {{ $datos['FACTURA'][$x]->NUM }}</option>
                            @endfor
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

<!-- MODAL EVALUACION -->
<div class="modal fade" id="Modal_Evaluacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_evaluacion"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row" id="FormularioRdbtn">
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA SOLICITUD:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_fecha_solicitud" placeholder="SELECCIONAR FECHA" name="txt_fecha_solicitud" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="fw-500">FECHA ENTREGA:</label>
                        <div class="timepicker-input input-icon form-group">
                            <div class="input-group">
                                <div class="input-group-addon bgc-white bd bdwR-0">
                                    <i class="ti-calendar"></i>
                                </div>
                                <input type="text" class="form-control start-date rounded" id="txt_fecha_entrega" placeholder="SELECCIONAR FECHA" name="txt_fecha_entrega" placeholder="Datepicker" data-provide="datepicker" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 text-center">
                        <center>
                        <fieldset>
                            <legend class="fw-500">PUNTAJE PRECIO</legend>
                            <input type="radio" name="rdbtn_puntaje_precio" class="rdbtn" style="height:30px; width:5%" value="1" />
                            <label for="sizeSmall">OTRAS OPCIONES</label>
                            <input type="radio" name="rdbtn_puntaje_precio" class="rdbtn" style="height:30px; width:5%" value="2" />
                            <label for="sizeMed">4TA OPCION</label>
                            <input type="radio" name="rdbtn_puntaje_precio" class="rdbtn" style="height:30px; width:5%" value="3" />
                            <label for="sizeLarge">3RA OPCION</label>
                            <input type="radio" name="rdbtn_puntaje_precio" class="rdbtn" style="height:30px; width:5%" value="4" />
                            <label for="sizeLarge">2DA OPCION</label>
                            <input type="radio" name="rdbtn_puntaje_precio" class="rdbtn" style="height:30px; width:5%" value="5" />
                            <label for="sizeLarge">1RA OPCION</label>
                        </fieldset>
                        </center>
                    </div>
                    
                    <div class="form-group col-md-12 text-center">
                        <center>
                        <fieldset>
                            <legend class="fw-500">CALIDAD</legend>          
                            <input type="radio" name="rdbtn_calidad" class="rdbtn" style="height:30px; width:5%" value="1" />
                            <label for="sizeSmall">DEFICIENTE</label>
                            <input type="radio" name="rdbtn_calidad" class="rdbtn" style="height:30px; width:5%" value="2" />
                            <label for="sizeMed">MALO</label>
                            <input type="radio" name="rdbtn_calidad" class="rdbtn" style="height:30px; width:5%" value="3" />
                            <label for="sizeLarge">REGULAR</label>
                            <input type="radio" name="rdbtn_calidad" class="rdbtn" style="height:30px; width:5%" value="4" />
                            <label for="sizeLarge">BUENO</label>
                            <input type="radio" name="rdbtn_calidad" class="rdbtn" style="height:30px; width:5%" value="5" />
                            <label for="sizeLarge">MUY BUENO</label>
                        </fieldset>
                        </center>
                    </div>
                    
                    <div class="form-group col-md-12 text-center">
                        <center>
                        <fieldset>
                            <legend class="fw-500">DISPONIBILIDAD DE STOCK</legend>          
                            <input type="radio" name="rdbtn_disp_stock" class="rdbtn" style="height:30px; width:5%" value="1" />
                            <label for="sizeSmall">MAS DE 30 DIAS</label>
                            <input type="radio" name="rdbtn_disp_stock" class="rdbtn" style="height:30px; width:5%" value="2" />
                            <label for="sizeMed">29 A 20 DIAS</label>
                            <input type="radio" name="rdbtn_disp_stock" class="rdbtn" style="height:30px; width:5%" value="3" />
                            <label for="sizeLarge">19 A 10 DIAS</label>
                            <input type="radio" name="rdbtn_disp_stock" class="rdbtn" style="height:30px; width:5%" value="4" />
                            <label for="sizeLarge">9 A 5 DIAS</label>
                            <input type="radio" name="rdbtn_disp_stock" class="rdbtn" style="height:30px; width:5%" value="5" />
                            <label for="sizeLarge">MENOS DE 5 DIAS</label>
                        </fieldset>
                        </center>
                    </div>
                    
                    <div class="form-group col-md-12 text-center">
                        <center>
                        <fieldset>
                            <legend class="fw-500">CREDITO</legend>          
                            <input type="radio" name="rdbtn_credito" class="rdbtn" style="height:30px; width:5%" value="1" />
                            <label for="sizeSmall">DEFICIENTE</label>
                            <input type="radio" name="rdbtn_credito" class="rdbtn" style="height:30px; width:5%" value="2" />
                            <label for="sizeMed">MALO</label>
                            <input type="radio" name="rdbtn_credito" class="rdbtn" style="height:30px; width:5%" value="3" />
                            <label for="sizeLarge">REGULAR</label>
                            <input type="radio" name="rdbtn_credito" class="rdbtn" style="height:30px; width:5%" value="4" />
                            <label for="sizeLarge">BUENO</label>
                            <input type="radio" name="rdbtn_credito" class="rdbtn" style="height:30px; width:5%" value="5" />
                            <label for="sizeLarge">MUY BUENO</label>
                        </fieldset>
                        </center>
                    </div>
                    
                    <div class="form-group col-md-12 text-center">
                        <center>
                        <fieldset>
                            <legend class="fw-500">ENTREGA DE DOCUMENTOS</legend>          
                            <input type="radio" name="rdbtn_entr_docs" class="rdbtn" style="height:30px; width:5%" value="1" />
                            <label for="sizeSmall">MAS DE 30 DIAS</label>
                            <input type="radio" name="rdbtn_entr_docs" class="rdbtn" style="height:30px; width:5%" value="2" />
                            <label for="sizeMed">29 A 20 DIAS</label>
                            <input type="radio" name="rdbtn_entr_docs" class="rdbtn" style="height:30px; width:5%" value="3" />
                            <label for="sizeLarge">19 A 10 DIAS</label>
                            <input type="radio" name="rdbtn_entr_docs" class="rdbtn" style="height:30px; width:5%" value="4" />
                            <label for="sizeLarge">9 A 5 DIAS</label>
                            <input type="radio" name="rdbtn_entr_docs" class="rdbtn" style="height:30px; width:5%" value="5" />
                            <label for="sizeLarge">MENOS DE 5 DIAS</label>
                        </fieldset>
                        </center>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_guardar_evaluacion"><i class="fa fa-plus-square"></i> GUARDAR DATOS</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal_eva"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/inventario/item.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
    jQuery(document).ready(function($){
    
        jQuery("#tabla_items").jqGrid({
            url: 'items/0?grid=items',
            datatype: 'json', mtype: 'GET',
            height: '450px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            pgbuttons: false,
            pgtext: null,
            //cmTemplate: { sortable: false },
            colNames: ['ID', 'DESCRIPCION', 'SERIE', 'CANT.', 'IDMARCA', 'MARCA','IDPROVEEODR', 'PROVEE.','IDFACTURA', 'FACTURA','PRECIO','FECHA','ESTADO','EVALUACION'],
            rowNum: 20, sortname: 'item_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_item" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE ITEMS -', align: "center",
            colModel: [
                {name: 'item_id', index: 'item_id', align: 'left',width: 10, hidden:true},
                {name: 'item_desc', index: 'item_desc', align: 'left', width: 25},
                {name: 'item_ser', index: 'item_ser', align: 'center', width: 15},
                {name: 'item_cant', index: 'item_cant', align: 'center', width: 10},
                {name: 'id_marca', index: 'id_marca', align: 'center', width: 20, hidden:true},
                {name: 'mar_id', index: 'mar_id', align: 'left', width: 20},
                {name: 'id_proveedor', index: 'id_proveedor', align: 'left', width: 10, hidden:true},
                {name: 'pro_id', index: 'pro_id', align: 'left', width: 20},
                {name: 'id_factura', index: 'id_factura', align: 'left', width: 10, hidden:true},
                {name: 'fact_id', index: 'fact_id', align: 'center', width: 20},
                {name: 'item_prec', index: 'item_prec', align: 'center', width: 10},
                {name: 'item_fec', index: 'item_fec', align: 'center', width: 10},
                {name: 'item_est', index: 'item_est', align: 'center', width: 10},
                {name: 'id_calif', index: 'id_calif', align: 'center', width: 12}
            ],
            pager: '#paginador_tabla_items',
            rowList: [20, 30, 40, 50, 100000000],
            loadComplete: function() {
                $("option[value=100000000]").text('TODOS');
            },
            gridComplete: function () {
                    var idarray = jQuery('#tabla_items').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_items').jqGrid('getDataIDs')[0];
                            $("#tabla_items").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    $('#btn_modificar_item').click();
                }
                else
                {
                    sin_permiso();
                }
            }
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_items").jqGrid('setGridWidth', $("#contenedor").width());
        });

        $("#mdl_imarca").select2({
            dropdownParent: $("#Modal_Item")
        });

        $("#mdl_iproveedor").select2({
            dropdownParent: $("#Modal_Item")
        });

        $("#mdl_ifactura").select2({
            dropdownParent: $("#Modal_Item")
        });

    });
</script>
@stop
@endsection
