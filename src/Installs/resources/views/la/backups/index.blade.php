@extends("la.layouts.app")

@section("contentheader_title", "Backups")
@section("contentheader_description", "Lista de Backups")
@section("section", "Backups")
@section("sub_section", "Listing")
@section("htmlheader_title", "Lista de Backups")

@section("headerElems")
@la_access("Backups", "create")
	<button class="btn btn-success btn-sm pull-right" id="CreateBackup">Criar Backup</button>
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
	<p>{{session('confirmacao')}}</p>
</div>
@endif
<div class="card">
	<div class="card-body">
		<table id="example1" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
			<th>A��es</th>
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
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/backup_dt_ajax') }}",
		"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json",
				"decimal": ",",
				"thousands": ".",
				"lengthMenu": "Exibir _MENU_ records",
				buttons: {
					colvis: 'Ocultar colunas'
				},
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	
	$("#CreateBackup").on("click", function() {
		$.ajax({
			url: "{{ url(config('laraadmin.adminRoute') . '/create_backup_ajax') }}",
			method: 'POST',
			beforeSend: function() {
				$("#CreateBackup").html('<i class="fa fa-refresh fa-spin"></i> Criando Backup...');
			},
			headers: {
		    	'X-CSRF-Token': $('input[name="_token"]').val()
    		},
			success: function( data ) {
				if(data.status == "success") {
					$("#CreateBackup").html('<i class="fa fa-check"></i> Backup Criado!');
					$('body').pgNotification({
						style: 'circle',
						title: 'Backup Creation',
						message: data.message,
						position: "top-right",
						timeout: 0,
						type: "success",
						thumbnail: '<img width="40" height="40" style="display: inline-block;" src="{{ asset('la-assets/img/laraadmin_logo_white.png') }}" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg" alt="">'
					}).show();
					setTimeout(function() {
						window.location.reload();
					}, 1000);
				} else {
					$("#CreateBackup").html('Create Backup');
					$('body').pgNotification({
						style: 'circle',
						title: 'Falha ao criar Backup',
						message: data.message,
						position: "top-right",
						timeout: 0,
						type: "danger",
						thumbnail: '<img width="40" height="40" style="display: inline-block;" src="{{ asset('la-assets/img/laraadmin_logo_white.png') }}" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg" alt="">'
					}).show();
					console.error(data.output);
				}
			}
		});
	});
});
</script>
@endpush
