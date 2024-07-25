@extends("la.layouts.app")
@section("contentheader_title", "Departamentos")
@section("contentheader_description", "Lista de setores")
@section("section", "Setores")
@section("sub_section", "Listing")
@section("htmlheader_title", "Lista de setores")
@section("headerElems")
@la_access("Departments", "create")
<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Adicionar Departamento</button>
@endla_access
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
@if(session('confirmacao'))
<div class="alert alert-success">
	{{session('confirmacao')}}
</div>
@endif
<div class="card">
	<div class="card-body">
	<table id="example1" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] ?? ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
					<th>Ações</th>
			@endif
		</tr>
		</thead>
		<tbody>
		</tbody>
		</table>
	</div>
</div>

@la_access("Departments", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">	
				<h4 class="modal-title" id="myModalLabel">Adicionar Departamento</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			{!! Form::open(['action' => 'LA\DepartmentsController@store', 'id' => 'department-add-form']) !!}
			<div class="modal-body">
				<div class="card-body">
                    @la_form($module)
					
					{{--
					@la_input($module, 'name')
					@la_input($module, 'tags')
					@la_input($module, 'color')
					--}}
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				{!! Form::submit( 'Salvar', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endla_access
@endsection
@push('styles')
@endpush
@push('scripts')
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/department_dt_ajax') }}",
			"language": {
				                "url": "{{ asset('la-assets/plugins/datatables/portuguese-brasil.json') }}",
			}
	});
});
</script>
@endpush