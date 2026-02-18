<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Mi Aplicación</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nifty.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/demo/nifty-demo.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/pace/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('plugins/pace/pace.min.js') }}"></script>
</head>

<body>
    <div id="container" class="cls-container">
        <div id="bg-overlay" class="bg-img img-balloon"></div>

        <div class="cls-header cls-header-lg">
            <div class="cls-brand">
                <a class="box-inline" href="{{ route('login') }}">
                    <span class="brand-title">Mi Aplicación <span class="text-thin">Portal</span></span>
                </a>
            </div>
        </div>

        <div class="cls-content">
            <div class="cls-content-sm panel rounded">
                <div class="panel-body">
                    <p class="pad-btm">Acceso al portal</p>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input id="email" name="email" type="email" class="form-control" 
                                       placeholder="Email" value="{{ old('email') }}" required autofocus />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                                <input id="password" name="password" type="password" class="form-control" 
                                       placeholder="Contraseña" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-8 text-left checkbox">
                                <label class="form-checkbox form-icon">
                                    <input type="checkbox" name="remember"> Recuérdame
                                </label>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-success text-uppercase rounded">
                                        Entrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/fast-click/fastclick.min.js') }}"></script>
    <script src="{{ asset('js/nifty.js') }}"></script>
</body>
</html>
