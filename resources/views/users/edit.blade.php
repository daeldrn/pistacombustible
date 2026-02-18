@extends('layouts.app')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Formulario de Edición</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group @error('name') has-error @enderror">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ old('name', $user->name) }}" placeholder="Ingrese nombre completo" required>
                        @error('name')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group @error('email') has-error @enderror">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" 
                               value="{{ old('email', $user->email) }}" placeholder="Ingrese email" required>
                        @error('email')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group @error('password') has-error @enderror">
                        <label>Nueva Contraseña <small class="text-muted">(dejar en blanco para mantener la actual)</small></label>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Ingrese nueva contraseña (mínimo 6 caracteres)">
                        @error('password')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group @error('password_confirmation') has-error @enderror">
                        <label>Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Confirme la nueva contraseña">
                        @error('password_confirmation')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group @error('activo') has-error @enderror">
                        <label>Estado</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="activo" value="1" {{ old('activo', $user->activo) ? 'checked' : '' }}>
                                Usuario Activo
                            </label>
                        </div>
                        @error('activo')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Actualizar
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
