@extends('la.layouts.app')
@section('htmlheader_title')
Detalhes do departamento
@endsection
@section('main-content')
<div id="page-content" class="profile2">
	<div class="bg-primary clearfix">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-3">
					<div class="card-header">
						<h4>Departamento</h4>
					</div>
					<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
						<li class="">
							<a class="nav-link" href="{{ url(config('laraadmin.adminRoute') . '/departments') }}" data-toggle="tooltip" data-placement="right" title="Voltar para lista">
								<i class="fa fa-chevron-left"></i>Voltar
							</a>
						</li>
						<li class="active">
							<a class="nav-link active" data-toggle="tab" href="#tab-general-info" role="tab" aria-controls="tab-general-info" aria-selected="true"><i class="fa fa-bars"></i> Ficha
							</a>
						</li>
						@la_access("Departments", "edit")
						<li>
							<a class="nav-link btn btn-outline-warning" href="{{ url(config('laraadmin.adminRoute') . '/departments/'.$department->id.'/edit') }}" onclick="return confirm('Deseja realmente editar?')">
								<i class="fa fa-pencil"></i> Editar
							</a>
						</li>
						@endla_access
						@la_access("Departments", "delete")
						<li>
							{{ Form::open(['route' => [config('laraadmin.adminRoute') . '.departments.destroy', $department->id], 'method' => 'delete', 'style'=>'display:inline']) }}
							<button class="nav-link btn btn-outline-danger" type="submit" onclick="return confirm('Deseja realmente excluir?')">
								<i class="fa fa-times"></i> Excluir </button>
							{{ Form::close() }}
						</li>
						@endla_access
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade show active" id="tab-general-info" role="tabpanel" aria-labelledby="tab-general-infotab">
							<section class="content">

								<div class="card-body">
									@la_display($module, 'name')
									@la_display($module, 'tags')
									@la_display($module, 'color')
								</div>
						</div>
						</section>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection