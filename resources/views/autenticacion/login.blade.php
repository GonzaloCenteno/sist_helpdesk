@extends('principal.p_logueo')

@section('content')

<h4 class="fw-300 c-grey-900 mB-40">INICIO CROMOHELP</h4>
<form action="{{ route('login') }}" method="POST">
    {{ csrf_field() }}
    <div class="form-group {{ $errors->has('usuario') ? ' was-validated' : '' }}">
        <label class="text-normal text-dark">USUARIO</label>
        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="INGRESAR NOMBRE DE USUARIO" value="{{ old('usuario') }}">
        {!! $errors->first('usuario', '<span class="text-danger">:message</span>') !!}
    </div>
    
    <div class="form-group {{ $errors->has('password') ? ' was-validated' : '' }}">
        <label class="text-normal text-dark">CONTRASEÑA</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="INGRESAR CONTRASEÑA">
        {!! $errors->first('password', '<span class="text-danger">:message</span>') !!}
    </div>
    
    <div class="form-group">
        <div class="peers ai-c jc-sb fxw-nw">
            
            <div>
                <button type="submit" class="btn btn-primary btn-block">INICIAR SESION</button>
            </div>
            
        </div>
    </div>
    <div class="form-group">
        <h3 class="text-danger"><b>{!! Session::has('msg') ? Session::get("msg") : '' !!}</b></h3>
    </div>
</form>
@endsection
