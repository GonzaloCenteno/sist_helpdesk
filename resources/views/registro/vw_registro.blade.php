@extends('principal.p_logueo')

@section('content')

<h4 class="fw-300 c-grey-900 mB-40">REGISTRO CROMOHELP</h4>
    
<div class="form-group">
    <label class="text-normal text-dark">USUARIO</label>
    <input type="text" class="form-control" id="usu_reg" name="usu_reg" placeholder="INGRESAR NOMBRE DE USUARIO" value="{{ old('usuario') }}" autocomplete="off">
</div>

<div class="form-group">
    <label class="text-normal text-dark">CONTRASEÑA</label>
    <input type="password" class="form-control" id="pass_reg" name="pass_reg" placeholder="INGRESAR CONTRASEÑA">
</div>

<hr>

<div class="form-group">
    <label class="text-normal text-dark">PUNTO DE VENTA</label>
    <select id="punto_venta" name="punto_venta" class="form-control" style="width: 100%;">
        @foreach($pvt as $punto)
            <option value="{{ $punto->pvt_id }}"> ..:: {{ $punto->pvt_desc }} ::.. </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <div class="peers ai-c jc-sb fxw-nw">

        <form method="GET" action="{{ route('logout') }}">
            {{ csrf_field() }}
            <div>
                <a href="{{ route('logout') }}" class="btn btn-danger btn-block">REGRESAR</a>
            </div>
        </form>

        <div>
            <button type="button" id="btn_guardar_datos" class="btn btn-primary btn-block">GUARDAR</button>
        </div>

    </div>
</div>

@section('page-js-script')
<script language="JavaScript" type="text/javascript">
    
    jQuery(document).ready(function($){
        $('#punto_venta').select2()
    });

    jQuery(document).on("click", "#btn_guardar_datos", function(){
        usuario = $('#usu_reg').val();
        password = $('#pass_reg').val();
        punto_venta = $('#punto_venta').val();

        if (usuario == '') {
            mostraralertasconfoco('* EL CAMPO USUARIO ES OBLIGATORIO...', '#usu_reg');
            return false;
        }
        if (password == '') {
            mostraralertasconfoco('* EL CAMPO PASSWORD ES OBLIGATORIO...', '#pass_reg');
            return false;
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'registro/create',
            type: 'GET',
            data: {
                usuario:usuario,
                password:password,
                punto_venta:punto_venta
            },
            beforeSend:function(){            
                MensajeEspera('ENVIANDO INFORMACION');        
            },
            success: function (data) 
            {
                if(data == 0)
                {
                    MensajeAdvertencia('DATOS ERRONEOS');
                    $('#usu_reg').val('');
                    $('#pass_reg').val('');
                    $('#usu_reg').focus();
                }
                else if(data == 'error')
                {
                    MensajeAdvertencia('EL USUARIO NO TIENE LOS PERMISOS ADECUADOS');
                    $('#pass_reg').val('');
                    $('#pass_reg').focus();
                }
                else if(data == 'incorrecto')
                {
                    MensajeAdvertencia('EL USUARIO NO ES ADMINISTRADOR');
                }
                else
                {
                    window.location.href = "{{URL::to('/')}}"
                }
            },
            error: function(data) {
                MensajeAdvertencia('* Error de Red...<br>* Contactese con el Administrador...');
                console.log('error');
                console.log(data);
            }
        });
    })
</script>
@stop
@endsection
