@extends('la.layouts.auth')
@section('htmlheader_title')
Log in
@endsection
@section('content')

<body class="login-page" cz-shortcut-listen="true" style="min-height: 496.802px;">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>{{ LAConfigs::getByKey('sitename_part1') }} </b>{{ LAConfigs::getByKey('sitename_part2') }}</a>
        </div>
        <div class="card">
        <div class="card-body login-card-body">
        @if (count($errors) > 0)
        
            
                <div class="alert alert-danger">
                    <strong>Erro!</strong> Os dados digitados são inválidos.
                    <!--
                        <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
-->
                </div>
                @endif

                <p class="login-box-msg">Inicie sua sessão</p>
                <form action="{{ url('/login') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" placeholder="E-mail" name="email" required />
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Senha" name="password" required />
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember">
                                <label for="remember">
                                    Lembrar
                                </label>


                            </div>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Acessar o sistema</button>
                        </div>
                    </div>
                </form>

                <hr>
                    @include('auth.partials.social_login')
                    <div class="d-flex justify-content-center">                    <a  class="p-2 m-2 badge badge-light" href="{{ url('/password/reset') }}">Esqueceu sua senha?</a>
</div>
                <!--<a href="{{ url('/register') }}" class="text-center">Register a new membership</a>-->
            </div><!-- /.login-box-b    ody -->
            
            <p style="text-align:center;">Desenvolvido por <a href="http:caqo.com.br" target="_blank">CAQO Marketing</a></p>
        </div>
    </div><!-- /.login-box -->
    @include('la.layouts.partials.scripts_auth')
    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>
@endsection
