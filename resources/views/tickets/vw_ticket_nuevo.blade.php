@extends('principal.p_inicio')

@section('content')
<style>
.modal-body {
    max-height: calc(100vh - 210px);
    overflow-y: auto;
}       
hr {
    border: 1px solid #7A7878;
}
</style>
<div class="row gap-20 masonry pos-r">
    <div class="masonry-sizer col-md-6"></div>
    <div class="masonry-item col-md-12">
        <div class="bgc-white p-20 bd">
            <h2 class="c-grey-900 text-center">NUEVO TICKET</h2>
            <div class="mT-30" style="padding-bottom: 220px;">
                <div class = "alert alert of danger print-error-msg" style = " display : none " >  
                    <ul> </ul>
                </div>
                <form enctype="multipart/form-data" id="FormularioTicket" name="FormularioTicket" method="POST">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" data-token="{{ csrf_token() }}"> 
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="cbxtipo" class="fw-500">TIPO:</label>
                            <select id="cbxtipo" name="cbxtipo" class="form-control rounded">
                                <option selected value="0">..:: SELECCIONAR UN TIPO ::..</option>
                                @for ($x = 0; $x < $numtip; $x++)          
                                <option value="{{ $datos['TIPO']->IDTIP[$x] }}">{{ $datos['TIPO']->DESTIP[$x] }}</option>
                                @endfor             
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cbxarea" class="fw-500">DIRIGIDO A:</label>
                            <select id="cbxarea" name="cbxarea" class="form-control rounded">
                                <option selected value="0">..:: SELECCIONAR UN AREA ::..</option>
                                @for ($x = 0; $x < $numare; $x++)          
                                <option value="{{ $datos['AREA']->IDAREA[$x] }}">{{ $datos['AREA']->DESAREA[$x] }}</option>
                                @endfor 
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cbxsubarea" class="fw-500">SUB-AREA:</label>
                            <select id="cbxsubarea" name="cbxsubarea" class="form-control rounded">
                                <option selected value="0">..:: SELECCIONAR UNA SUBAREA ::..</option>
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cbxpri" class="fw-500">PRIORIDAD:</label>
                            <select id="cbxpri" name="cbxpri" class="form-control rounded">
                                <option selected value="0">..:: SELECCIONAR UNA PRIORIDAD ::..</option>
                                @for ($x = 0; $x < $numpri; $x++)          
                                <option value="{{ $datos['PRIORIDAD']->IDPRI[$x] }}">{{ $datos['PRIORIDAD']->DESPRI[$x] }}</option>
                                @endfor 
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="fw-500">FECHA:</label>
                            <div class="timepicker-input input-icon form-group">
                                <div class="input-group">
                                    <div class="input-group-addon bgc-white bd bdwR-0">
                                        <i class="ti-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control rounded" readonly="true" id="txfecha" placeholder="SELECCIONAR UNA FECHA" name="txfecha" data-dateformat='dd/mm/yy' data-mask="99/99/9999" value="<?php echo date("d/m/Y"); ?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="intitulo" class="fw-500">TITULO:</label>
                        <input type="text" class="form-control text-center text-uppercase rounded" id="intitulo" name="intitulo" placeholder="DESCRIPCION CORTA" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="editor" class="fw-500 rounded">DESCRIPCION:</label>
                        <textarea  id="descripcion" name="descripcion"></textarea >
                    </div>
                    <div class="form-group col-md-12">
                        <label for="adjuntar archivo" class="fw-500">Adjuntar archivo:</label>
                        <input type='file' name='file' id='file' placeholder="CARGAR UN ARCHIVO">
                    </div>
                    <div class="form-group">
                        @if( $permiso[0]->btn_new == 1 )
                            <button type="button" id="btn_enviar_datos" class="btn btn-primary btn-lg btn-block">ENVIAR DATOS</button>
                        @else
                            <button type="button"  onclick="sin_permiso();" class="btn btn-primary btn-lg btn-block">ENVIAR DATOS</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@section('page-js-script')
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/tickets/ticket_nuevo.js') }}"></script>
<script>
    $('#{{ $permiso[0]->men_sistema }}').addClass('open');
    $('.{{ $permiso[0]->sme_ruta }}').addClass('selector_submenu');
    
    jQuery(document).on("change","#cbxarea",function(){
        $.ajax({
            url: 'ticketnuevo/'+$(this).val()+'?datos=traer_subareas',
            type: 'GET',
            beforeSend:function()
            {            
                $('#cbxsubarea').parent().block({
                    message: "<p class='ClassMsgBlock'><img src={{ asset('img/cargando.gif') }} style='width: 18px;position: relative;top: -1px;'/>PROCESANDO INFORMACION</p>",
                    css: { border: '2px solid #006000',background:'white',width: '62%'}
                });
            },
            success: function(data) 
            {
                html = '<option selected value="0">..:: SELECCIONAR UNA SUBAREA ::..</option>';
                for(i=0;i<data.length;i++)
                {
                    html = html+'<option value='+data[i].suba_id+'>'+data[i].suba_desc+'</option>';
                }
                
                $("#cbxsubarea").html(html);
                $("#cbxsubarea").parent().unblock();
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
