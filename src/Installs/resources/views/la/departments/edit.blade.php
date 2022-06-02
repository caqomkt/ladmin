@extends("la.layouts.app")
@section("htmlheader_title", "Editando: ".$department->$view_col)
@section("section_url", url(config('laraadmin.adminRoute') . '/departments'))
@section("section", "Departmentos")
@section("sub_section", "Editar")
@section("contentheader_title")
<a href="{{ url(config('laraadmin.adminRoute') . '/departments') }}">Setores</a> :
@endsection
@section("contentheader_description", $department->$view_col)
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
		{!! Form::model($department, ['route' => [config('laraadmin.adminRoute') . '.departments.update', $department->id ], 'method'=>'PUT', 'id' => 'department-edit-form']) !!}
		@la_form($module)
		{{--
					@la_input($module, 'name')
					@la_input($module, 'tags')
					@la_input($module, 'color')
					--}}
		<br>
		<div class="form-group">
			{!! Form::submit( 'Atualizar', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/departments') }}" class="btn btn-default pull-right">Cancelar</a>
		</div>
		{!! Form::close() !!}
	</div>
</div>
@endsection
@push('scripts')
@endpush