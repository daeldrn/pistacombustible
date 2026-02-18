@extends('layouts.app')

@section('title', 'Crear Usuario')
@section('page-title', 'Crear Nuevo Usuario')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Formulario de Usuario</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group @error('name') has-error @enderror">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ old('name') }}" placeholder="Ingrese nombre completo" required>
                        @error('name')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group @error('email') has-error @enderror">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" 
                               value="{{ old('email') }}" placeholder="Ingrese email" required>
                        @error('email')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group @error('password') has-error @enderror">
                        <label>Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Ingrese contraseña (mínimo 6 caracteres)" required>
                        @error('password')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group @error('password_confirmation') has-error @enderror">
                        <label>Confirmar Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Confirme la contraseña" required>
                        @error('password_confirmation')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group @error('activo') has-error @enderror">
                        <label>Estado</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                                Usuario Activo
                            </label>
                        </div>
                        @error('activo')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-default">
                            <i class="fa fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
