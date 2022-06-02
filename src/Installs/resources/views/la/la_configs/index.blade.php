@extends("la.layouts.app")

@section("contentheader_title", "Configuração")
@section("contentheader_description", "")
@section("section", "Configuração")
@section("sub_section", "Geral")
@section("htmlheader_title", "Configuração")

@section("headerElems")
@endsection

@section("main-content")

@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
<form action="{{route(config('laraadmin.adminRoute').'.la_configs.store')}}" method="POST">
	<!-- general form elements disabled -->
	<div class="card card-warning">
		<div class="card-header with-border">
			<p class="text-danger card-title">ATENÇÃO: Não altere estas configurações!</p>
		</div>
		<!-- /.card-header -->
		<div class="card-body">
			{{ csrf_field() }}
			<!-- text input -->
			<div class="row">
				<div class="col-4">
			<div class="form-group">
						<label>Nome do Site</label>
				<input type="text" class="form-control" placeholder="Lara" name="sitename" value="{{$configs->sitename}}">
			</div>
				</div>
				<div class="col-4">
			<div class="form-group">
						<label>Primeira palavra do nome do site</label>
				<input type="text" class="form-control" placeholder="Lara" name="sitename_part1" value="{{$configs->sitename_part1}}">
			</div>
				</div>
				<div class="col-4">
			<div class="form-group">
						<label>Segunda palavra do nome do site</label>
				<input type="text" class="form-control" placeholder="Admin 1.0" name="sitename_part2" value="{{$configs->sitename_part2}}">
			</div>
				</div>
			</div>
			<div class="row">
				<div class="col-2">
			<div class="form-group">
						<label>Abreviação</label>
				<input type="text" class="form-control" placeholder="LA" maxlength="2" name="sitename_short" value="{{$configs->sitename_short}}">
			</div>
				</div>
				<div class="col-10">
			<div class="form-group">
						<label>Descrição do site</label>
				<input type="text" class="form-control" placeholder="Description in 140 Characters" maxlength="140" name="site_description" value="{{$configs->site_description}}">
			</div>
				</div>
			</div>
			<!-- checkcard -->
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="sidebar_search" @if($configs->sidebar_search) checked @endif>
						Mostrar barra de pesquisa
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="show_messages" @if($configs->show_messages) checked @endif>
						Mostrar ícone de mensagens
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="show_notifications" @if($configs->show_notifications) checked @endif>
						Mostrar ícone de notificações
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="show_tasks" @if($configs->show_tasks) checked @endif>
						Mostrar ícone de tarefas
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="show_rightsidebar" @if($configs->show_rightsidebar) checked @endif>
						Mostrar ícone da barra lateral direita
					</label>
				</div>
			</div>
			<!-- select -->
			<div class="row">
				<div class="col-4">
			<div class="form-group">
						<label>Cor do Tema</label>
				<select class="form-control" name="skin">
					@foreach($skins as $name=>$property)
						<option value="{{ $property }}" @if($configs->skin == $property) selected @endif>{{ $name }}</option>
					@endforeach
				</select>
			</div>
				</div>
				<div class="col-4">
			<div class="form-group">
				<label>Layout</label>
				<select class="form-control" name="layout">
					@foreach($layouts as $name=>$property)
						<option value="{{ $property }}" @if($configs->layout == $property) selected @endif>{{ $name }}</option>
					@endforeach
				</select>
			</div>
				</div>
			</div>
			<div class="form-group">
				<label>Endereço de e-mail padrão</label>
				<input type="text" class="form-control" placeholder="To send emails to others via SMTP" maxlength="100" name="default_email" value="{{$configs->default_email}}">
			</div>
		</div><!-- /.box-body -->
		<div class="card-footer">
			<button type="submit" class="btn btn-primary">Salvar</button>
		</div><!-- /.card-footer -->
	</div><!-- /.card -->
</form>

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>

@endpush