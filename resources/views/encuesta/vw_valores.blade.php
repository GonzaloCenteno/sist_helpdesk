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
            <h2 class="c-grey-900 text-center">VALORES</h2>
            <div class="mT-30" style="padding-bottom: 499px;">

                <div class="form-row">
                    @if( $permiso[0]->btn_new == 1 )
                        <div class="form-group col-md-3">
                            <button id="btn_nuevo_valor" data-toggle="modal" data-target="#Modal_Valor" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        </div>
                    @else
                        <div class="form-group col-md-3">
                            <button onclick="sin_permiso();" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        </div> 
                    @endif
                    @if( $permiso[0]->btn_edit == 1 )
                        <button style="display:none;" id="btn_nuevo_valor" data-toggle="modal" data-target="#Modal_Valor" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-block" type="button"><i class="fa fa-plus-square"></i> NUEVO</button>
                        <div class="form-group col-md-3">
                            <button class="btn btn-block" id="btn_modificar_valor" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        </div>
                    @else
                        <div class="form-group col-md-3">
                            <button class="btn btn-block" onclick="sin_permiso();" style="background-color:#D48411;color:white;" type="button"><i class="fa fa-pencil"></i> MODIFICAR</button>
                        </div>
                    @endif
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-12" id="contenedor">
                            <table id="tabla_valores"></table>
                            <div id="paginador_tabla_valores"></div>                         
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- VENTANA MODAL -->
<div class="modal fade" id="Modal_Valor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo_valor"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="FormularioValor" name="FormularioValor" method="post" enctype="multipart/form-data">
                    <div class="form-row">
                        @csrf
                        <div class="form-group col-md-12">
                            <label for="desc_valor" class="fw-500">DESCRIPCION:</label>
                            <input type="text" class="form-control text-center text-uppercase rounded" id="desc_valor" name="desc_valor" placeholder="DESCRIPCION DEL VALOR" autocomplete="off">
                        </div>
                        <div class="form-group col-md-6 text-center">
                            <img src="{{asset('img/product.png')}}" id="form_imagen1" style="width: 200px;height: 200px;border: 1px solid #fff; outline: 1px solid #bfbfbf;">   
                        </div>
                        <div class="form-group col-md-6 text-center">
                            <img src="{{asset('img/product.png')}}" id="form_imagen2" style="width: 200px;height: 200px;border: 1px solid #fff; outline: 1px solid #bfbfbf;">   
                        </div>
                        <div class="form-group col-md-6">
                            <label for="img1_valor" class="fw-500">IMAGEN 1:</label>
                            <input type="file" class="form-control text-center text-uppercase rounded" id="img1_valor" name="img1_valor" onchange="return validarExtensionArchivo1()">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="img2_valor" class="fw-500">IMAGEN 2:</label>
                            <input type="file" class="form-control text-center text-uppercase rounded" id="img2_valor" name="img2_valor" onchange="return validarExtensionArchivo2()">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_guardar_valor"><i class="fa fa-plus-square"></i> GUARDAR DATOS</button>
                <button type="button" class="btn btn-primary" id="btn_actualizar_valor"><i class="fa fa-plus-square"></i> MODIFICAR DATOS</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_modal"><i class="fa fa-times"></i> CERRAR VENTANA</button>
            </div>
        </div>
    </div>
</div>


@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/encuesta/valores.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
    jQuery(document).ready(function($){
    
        jQuery("#tabla_valores").jqGrid({
            url: 'valores/0?grid=valores',
            datatype: 'json', mtype: 'GET',
            height: '450px', autowidth: true,
            toolbarfilter: true,
            sortable:false,
            pgbuttons: false,
            pgtext: null,
            //cmTemplate: { sortable: false },
            colNames: ['ID', 'DESCRIPCION','IMAGEN','ESTADO'],
            rowNum: 10, sortname: 'val_id', sortorder: 'desc', viewrecords: true, caption: '<button id="btn_act_table_valores" type="button" class="btn btn-danger"><i class="fa fa-gear"></i> ACTUALIZAR <i class="fa fa-gear"></i></button> - LISTA DE VALORES -', align: "center",
            colModel: [
                {name: 'val_id', index: 'val_id', align: 'left',width: 10, hidden:true},
                {name: 'val_desc', index: 'val_desc', align: 'left', width: 80},
                {name: 'val_img', index: 'val_img', align: 'center', width: 20, formatter: formatImage},
                {name: 'val_est', index: 'val_est', align: 'center', width: 20}
            ],
            pager: '#paginador_tabla_valores',
            rowList: [10, 20, 30, 40, 50, 100000000],
            loadComplete: function() {
                $("option[value=100000000]").text('TODOS');
            },
            gridComplete: function () {
                    var idarray = jQuery('#tabla_valores').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_valores').jqGrid('getDataIDs')[0];
                            $("#tabla_valores").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                permiso = {!! json_encode($permiso[0]->btn_edit) !!};
                if(permiso == 1)
                {
                    $('#btn_modificar_valor').click();
                }
                else
                {
                    sin_permiso();
                }
            }
        });

        function formatImage(cellValue, options, rowObject) {
            var imageHtml = "<img src='data:image/png;base64," + cellValue + "' originalValue='" + cellValue + "' style='width: 60px;height: 60px;border: 1px solid #fff; outline: 1px solid #bfbfbf;'/>";
            return imageHtml;
        }

        $(window).on('resize.jqGrid', function () {
            $("#tabla_valores").jqGrid('setGridWidth', $("#contenedor").width());
        });

    });
</script>
@stop
@endsection
