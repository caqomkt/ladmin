@extends("la.layouts.app")

@section("contentheader_title", "Usuários")
@section("contentheader_description", "Lista de Usuários")
@section("section", "Usuários")
@section("sub_section", "Lista")
@section("htmlheader_title", "Lista de Usuários")

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

@endsection

@push('styles')
@endpush

@push('scripts')
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/user_dt_ajax') }}",
			"language": {
				                "url": "{{ asset('la-assets/plugins/datatables/portuguese-brasil.json') }}",
			}
	});
		
	});
</script>
@endpush