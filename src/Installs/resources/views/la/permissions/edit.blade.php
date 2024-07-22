@extends("la.layouts.app")
@section("contentheader_title")
<a href="{{ url(config('laraadmin.adminRoute') . '/permissions') }}">Permissões</a> :
@endsection
@section("contentheader_description", $permission->$view_col)
@section("section", "Permissions")
@section("section_url", url(config('laraadmin.adminRoute') . '/permissions'))
@section("sub_section", "Edit")
@section("htmlheader_title", "Editando persmissão: ".$permission->$view_col)
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
<div class="card">
	<div class="card-header">
		Preencha os dados abaixo
	</div>
	<div class="card-body">
		{!! Form::model($permission, ['route' => [config('laraadmin.adminRoute') . '.permissions.update', $permission->id ], 'method'=>'PUT', 'id' => 'permission-edit-form']) !!}
		@la_form($module)
		{{--
					@la_input($module, 'name')
					@la_input($module, 'guard_name')
					@la_input($module, 'description')
					--}}
		<br>
		<div class="form-group">
			{!! Form::submit( 'Atualizar', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/permissions') }}" class="btn btn-default pull-right">Cancelar</a>
		</div>
		{!! Form::close() !!}
	</div>
</div>
@endsection
@push('scripts')
@endpush