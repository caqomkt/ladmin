@extends('la.layouts.auth')
@section('htmlheader_title')
    Redefinição de senha
@endsection
@section('content')
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/home') }}"><b>{{ LAConfigs::getByKey('sitename_part1') }} </b>{{ LAConfigs::getByKey('sitename_part2') }}</a>
        </div><!-- /.login-logo -->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Erro!</strong> Os dados digitados são inválidos.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="login-box-body">
            <p class="login-box-msg">Redefinir senha</p>
            <form action="{{ url('/password/email') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="E-mail" name="email" value="{{ old('email') }}"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-2">
                    </div>
                    <div class="col-xs-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Link para redefinição de senha</button>
                    </div>
                    <div class="col-xs-2">
                    </div>
                </div>
            </form>
            <a href="{{ url('/login') }}">Acessar</a><br>
            <!--<a href="{{ url('/register') }}" class="text-center">Register a new membership</a>-->
        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
    @include('la.layouts.partials.scripts_auth')
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>
@endsection
