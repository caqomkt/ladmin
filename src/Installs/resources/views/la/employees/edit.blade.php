@extends("la.layouts.app")
@section("contentheader_title")
<a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}">Equipe</a> :
@endsection
@section("contentheader_description", $employee->$view_col)
@section("section", "Equipe")
@section("section_url", url(config('laraadmin.adminRoute') . '/employees'))
@section("sub_section", "Editar")
@section("htmlheader_title", "Editar profissional: ".$employee->$view_col)
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
		{!! Form::model($employee, ['route' => [config('laraadmin.adminRoute') . '.employees.update', $employee->id ], 'method'=>'PUT', 'id' => 'employee-edit-form']) !!}
		@la_form($module)
		{{--
					@la_input($module, 'name')
					@la_input($module, 'designation')
					@la_input($module, 'gender')
					@la_input($module, 'mobile')
					@la_input($module, 'mobile2')
					@la_input($module, 'email')
					@la_input($module, 'dept')
					@la_input($module, 'city')
					@la_input($module, 'address')
					@la_input($module, 'about')
					@la_input($module, 'date_birth')
					@la_input($module, 'date_hire')
					@la_input($module, 'date_left')
					@la_input($module, 'salary_cur')
					--}}
		<div class="form-group">
			<label for="role">Role* :</label>
			<select class="form-control" required="1" data-placeholder="Select Role" rel="select2" name="role">
				@php
				$roles = Spatie\Permission\Models\Role::all(); // Utilizando o model do Spatie
				@endphp
				@foreach($roles as $role)
					@if($role->name != 'SUPER_ADMIN' || Auth::user()->hasRole('SUPER_ADMIN')) {{-- Checando role com Spatie --}}
						<option value="{{ $role->id }}" {{ $employee->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
				@endif
				@endforeach
			</select>
		</div>
		<br>
		<div class="form-group">
			{!! Form::submit( 'Atualizar', ['class'=>'btn btn-success']) !!} <a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}" class="btn btn-default pull-right">Cancelar</a>
		</div>
		{!! Form::close() !!}
	</div>
</div>
@endsection
@push('scripts')
@endpush