<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ LAConfigs::getByKey('site_description') }}">
    <meta name="author" content="Dwij IT Solutions">

    <meta property="og:title" content="{{ LAConfigs::getByKey('sitename') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ LAConfigs::getByKey('site_description') }}" />

    <meta property="og:url" content="http://laraadmin.com/" />
    <meta property="og:sitename" content="laraAdmin" />
    <meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@laraadmin" />
    <meta name="twitter:creator" content="@laraadmin" />

    <title>{{ LAConfigs::getByKey('sitename') }}</title>

    <link href="{{ asset('la-assets/favicon.ico') }}" rel="favicon">


    <link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom styles for this template -->
    <link href="{{ asset('/la-assets/css/main.css') }}" rel="stylesheet">

    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>



</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">{{ LAConfigs::getByKey('sitename') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#home">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">O sistema</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">O Contato</a>

                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                <li><a href="{{ url('/login') }}">Acessar o sistema</a></li>
                <!--<li><a href="{{ url('/register') }}">Register</a></li>-->
                @else
                <li><a href="{{ url(config('laraadmin.adminRoute')) }}">Olá, {{ Auth::user()->name }}!</a></li>
                @endif
            </ul>
        </div>
    </nav>
    <main role="main" >
    <section id="home" name="home"></section>
    <div id="headerwrap">
        <div class="container">
            <div class="row centered">
                <div class="col-lg-12">
                    <h1>SIMPLEI</h1>
                    <h3>{{ LAConfigs::getByKey('sitename_part1') }} <b><a>{{ LAConfigs::getByKey('sitename_part2') }}</a></b></h3>

                    <h3>@if (Auth::guest())
                        <a href="{{ url('/login') }}" class="btn btn-lg btn-success">Acessar sistema</a>
                        @else
                        <small>Logado(a) como {{ Auth::user()->name }}</small><br>
                        <a href="{{ url(config('laraadmin.adminRoute')) }}" class="btn btn-lg btn-success">Acessar sistema</a>
                        <a href="{{ url('/logout') }}" class="btn btn-danger btn-lg">Não é você? Trocar usuário</a>
                        @endif
                    </h3><br>
                    <hr>
                    <h3>{{ LAConfigs::getByKey('site_description') }}</h3>
                </div>
                <div class="col-lg-2">
                    <h5>Funcionalidades incríveis</h5>
                    <p>em um moderno painel de administração</p>
                    <img class="d-none d-md-block d-lg-block" src="{{ asset('/la-assets/img/arrow1.png') }}">
                </div>
                <div class="col-lg-8">
                    <img class="img-responsive" style="width: 100%;" src="{{ asset('/la-assets/img/app-bg.png') }}" alt="">
                </div>
                <div class="col-lg-2">
                    <br>
                    <img class="d-none d-md-block d-lg-block" src="{{ asset('/la-assets/img/arrow2.png') }}">
                    <h5>A ferramenta definitiva</h5>
                    <p>para gestão do seu negócio</p>
                </div>
            </div>
        </div>
        <!--/ .container -->
    </div>
    <!--/ #headerwrap -->


    <section id="about" name="about"></section>
    <!-- INTRO WRAP -->
    <div id="intro">
        <div class="container">
            <div class="row centered">
                <h1>Uma arquitetura desenhada para planilhas</h1>
                <br>
                <br>
                <div class="col-lg-4">
                    <i class="fa fa-cubes" style="font-size:100px;height:110px;"></i>
                    <h3>Modular</h3>
                    <p>Gerencie os dados de forma rápida e prática.</p>
                </div>
                <div class="col-lg-4">
                    <i class="fa fa-paper-plane" style="font-size:100px;height:110px;"></i>
                    <h3>Cobranças por e-mail</h3>
                    <p>Com um clique seu cliente recebe a cobrança.</p>
                </div>
                <div class="col-lg-4">
                    <i class="fa fa-cubes" style="font-size:100px;height:110px;"></i>
                    <h3>Personalizável</h3>
                    <p>Permite personalizar boletos com dados relevantes.</p>
                </div>
            </div>
            <br>
            <hr>
        </div>
        <!--/ .container -->
    </div>
    <!--/ #introwrap -->

    <!-- FEATURES WRAP -->
    <div class="container">
        
            <div class="row">
                <div class="col-lg-5 centered">
                    <img class="centered" src="{{ asset('/la-assets/img/mobile.png') }}" alt="">
                </div>

                <div class="col-lg-7">
                    <h3 class="feature-title">O que é o SIMPLEI?</h3><br>
                    <ol>
                        <li><strong>O SIMPLEI</strong> permite que você controle o fluxo de caixa de sua empresa de forma fácil e eficaz.</li>
                        <li>Pelo <strong>painel administrativo</strong> você pode adicionar os seus colaboradores e definir níveis de acesso.</li>
                        <li>Possui suporte a emissão de <strong>orçamentos</strong> e cotações.</li>
                    </ol><br>

                    <h3 class="feature-title">Como o SIMPLEI pode ajudar minha empresa?</h3><br>
                    <ol>
                        <li><strong>Gestão:</strong> Permite o controle total das finanças da sua empresa</li>
                        <li><strong>Superior:</strong> Sistema desenvolvido por quem vivencia as tarefas de gestão financeira.</li>
                        <li><strong>Relatórios:</strong> Além de exibir os relatórios pelo próprio sistema é possível exportá-los para seu editor de planilhas de forma fácil.</li>
                        <li><b>Controle de despesas: </b>Menu dedicado para registro de despesas.</li>
                        <li><strong>Dashboard:</strong> Ao fazer login já é possível visualizar o fluxo de caixa da sua empresa.</li>
                        <!-- <li><strong>Online Code Editor</strong> allows developers to customise the generated Module Views &amp; Files.</li> -->
                    </ol>
                </div>
            </div>
    </div>
    
    <section id="contact" name="contact"></section>
    <div id="footerwrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <h3>Fale conosco</h3><br>
                    <p>CAQO MARKETING<br />
                        Recreio dos Bandeirantes, RJ<br />
                        Rio de Janeiro<br />
                        Brasil
                    </p>
                    <div class="contact-link"><i class="fa fa-envelope-o"></i> <a href="mailto:contato@caqo.com.br">contato@caqo.com.br</a></div>
                    <div class="contact-link"><i class="fa fa-cube"></i> <a href="http://caqo.com.br">caqo.com.br</a></div>
                    <div class="contact-link"><i class="fa fa-building"></i> <a href="http://simplei.com.br">simplei.com.br</a></div>
                </div>

                <div class="col-lg-7">
                    <h3>Contato</h3>
                    <br>
                    <form role="form" action="#" method="post" enctype="plain">
                        <div class="form-group">
                            <label for="name1">Nome</label>
                            <input type="name" name="Name" class="form-control" id="name1" placeholder="Nome">
                        </div>
                        <div class="form-group">
                            <label for="email1">E-mail</label>
                            <input type="email" name="Mail" class="form-control" id="email1" placeholder="E-mail">
                        </div>
                        <div class="form-group">
                            <label>Mensagem</label>
                            <textarea class="form-control" name="Message" rows="3"></textarea>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-large btn-success">ENVIAR</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    </main>
    <footer class="text-center footer p-2 bg-dark">
        <div class="container">
            <span class="text-muted"> <strong>Copyright &copy; 2021. Desenvolvido por <a target="_blank" href="https://caqo.com.br"><b>CAQO Marketing</b></a></strong>
            </span>
        </div>
    </footer>
    

    <script src="{{ asset('/la-assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script>
        $('.carousel').carousel({
            interval: 3500
        })
    </script>
</body>

</html>