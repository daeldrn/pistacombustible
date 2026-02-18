@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary panel-colorful">
            <div class="pad-all text-center">
                <span class="text-3x text-thin">{{ $stats['users'] }}</span>
                <p>Usuarios</p>
                <i class="fa fa-users fa-3x"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-info panel-colorful">
            <div class="pad-all text-center">
                <span class="text-3x text-thin">{{ $stats['orders'] }}</span>
                <p>Pedidos</p>
                <i class="fa fa-shopping-cart fa-3x"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-success panel-colorful">
            <div class="pad-all text-center">
                <span class="text-3x text-thin">{{ $stats['sales'] }}</span>
                <p>Ventas</p>
                <i class="fa fa-line-chart fa-3x"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-warning panel-colorful">
            <div class="pad-all text-center">
                <span class="text-3x text-thin">{{ $stats['messages'] }}</span>
                <p>Mensajes</p>
                <i class="fa fa-envelope fa-3x"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Usuarios Recientes</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay usuarios registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
