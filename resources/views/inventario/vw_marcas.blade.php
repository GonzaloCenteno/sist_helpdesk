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
            <h2 class="c-grey-900 text-center">MARCAS</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="descripcion_marca" class="fw-500">BUSCAR POR DESCRIPCION:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="descripcion_marca" name="descripcion_marca" placeholder="ESCRIBIR DESCRIPCION DE LA MARCA" autocomplete="off">
                    </div>
                    <div class="form-group col-md-2" style="padding-top: 29px;">
                        <button id="btn_buscar_marca" class="btn btn-danger btn-block" type="button"><i class="fa fa-search"></i> BUSCAR</button>
                    </div>
                    @if( $permiso[0]->btn_new == 1 )
                        <div class="form-group col-md-2" style="padding-top: 29px;">
                            <button id="btn_nueva_marca" data-toggle="modal" data-target="#Modal_Marca" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        </div>
                    @else
                        <div class="form-group col-md-2" style="padding-top: 29px;">
                            <button onclick="sin_permiso();" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        </div>
                    @endif
                    @if( $permiso[0]->btn_edit == 1 )
                        <button style="display:none;" id="btn_nueva_marca" data-toggle="modal" data-target="#Modal_Marca" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        <div class="form-group col-md-2" style="padding-top: 29px;">
                            <button class="btn btn-block" id="btn_modificar_marca" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
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
                            <table id="tabla_marcas"></table>
                            <div id="paginador_tabla_marcas"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Marca" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_marca"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="descripcion" class="fw-500">DESCRIPCION:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="descripcion" name="descripcion" placeholder="DESCRIPCION DE LA MARCA" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_guardar_marca"><i class="fa fa-plus-square"></i> GUARDAR DATOS</button>
                <button type="button" class="btn btn-primary" id="btn_actualizar_marca"><i class="fa fa-plus-square"></i> MODIFICAR DATOS</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/inventario/marcas.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
    jQuery(document).ready(function($){
    
        jQuery("#tabla_marcas").jqGrid({
            url: 'marcas/0?grid=marcas',
            datatype: 'json', mtype: 'GET',
            height: '450px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            pgbuttons: false,
            pgtext: null,
            //cmTemplate: { sortable: false },
            colNames: ['ID', 'DESCRIPCION', 'ESTADO'],
            rowNum: 10, sortname: 'mar_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_marca" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE MARCAS -', align: "center",
            colModel: [
                {name: 'mar_id', index: 'mar_id', align: 'left',width: 10, hidden:true},
                {name: 'mar_desc', index: 'mar_desc', align: 'left', width: 80},
                {name: 'mar_est', index: 'mar_est', align: 'center', width: 20}
            ],
            pager: '#paginador_tabla_marcas',
            rowList: [10, 20, 30, 40, 50, 100000000],
            loadComplete: function() {
                $("option[value=100000000]").text('TODOS');
            },
            gridComplete: function () {
                    var idarray = jQuery('#tabla_marcas').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_marcas').jqGrid('getDataIDs')[0];
                            $("#tabla_marcas").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                     $('#btn_modificar_marca').click();
                }
                else
                {
                    sin_permiso();
                }
            }
        });

        $(window).on('resize.jqGrid', function () {
            $("#tabla_marcas").jqGrid('setGridWidth', $("#contenedor").width());
        });

        $("#descripcion_marca").keypress(function (e) {
            if (e.which == 13) {
                $('#btn_buscar_marca').click();
            }
        });

    });
</script>
@stop
@endsection
