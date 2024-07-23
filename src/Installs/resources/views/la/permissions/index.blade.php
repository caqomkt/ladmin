@extends("la.layouts.app")
@section("contentheader_title", "Permissões")
@section("contentheader_description", "Lista de permissões")
@section("section", "Permissions")
@section("sub_section", "Listing")Permissões")
@section("headerElems")
@la_access("Permissions", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Adicionar Permissão</button>
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
<div class="card card-success">
	<!--<div class="card-header"></div>-->
	<div class="card-body">
		<table id="example1" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
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
@la_access("Permissions", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
<h4 class="modal-title" id="myModalLabel">Adicionar Permissão</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
			</div>
			{!! Form::open(['action' => 'LA\PermissionsController@store', 'id' => 'permission-add-form']) !!}
			<div class="modal-body">
				<div class="card-body">
                    @la_form($module)
					{{--
					@la_input($module, 'name')
					@la_input($module, 'guard_name')
					@la_input($module, 'description')
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
        ajax: "{{ url(config('laraadmin.adminRoute') . '/permission_dt_ajax') }}",
		"language": {
				                "url": "{{ asset('la-assets/plugins/datatables/portuguese-brasil.json') }}",
			}
	});
});
</script>
@endpush
