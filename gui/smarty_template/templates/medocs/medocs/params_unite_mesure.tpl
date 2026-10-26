<ol class="breadcrumb">
	<li>Parametrage</li>
	<li>Unité de mesure des Constantes</li>
</ol>
{{if $msg}}
    <div class="alert alert-{{$msgType}}" style="font-size:12px;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <p class="check">{{$msg}}</p>
    </div>
{{/if}}
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Liste des Unités de mesure</h4>
			</div>
			<div class="body">
				<br>
				<table class="table table-striped table-bordered" id="datatable">
					<thead style="background-color: #D9EDF7">
						<tr>
							<th>#</th>
							<th>Désignation</th>
							<th>Type</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						{{$listing}}
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<form class="form-horizontal" action="" method="post" onsubmit="return checkForm();">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Formulaire d'enregistrement</h4>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="label-control col-sm-4">Désignation</label>
						<div class="col-sm-8">
							<input type="text" name="name" id="name" value="{{$name}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Designation abrégé</label>
						<div class="col-sm-8">
							<input type="text" name="id" id="id" value="{{$id}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">LD_var</label>
						<div class="col-sm-8">
							<input type="text" name="LD_var" id="LD_var" value="{{$LD_var}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">LD Description </label>
						<div class="col-sm-8">
							<input type="text" name="LD_description" id="LD_description" value="{{$LD_description}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Type </label>
						<div class="col-sm-8">
							<select name="unit_type_nr" id="unit_type_nr" class="form-control input-xs">
								{{$selectOptionsTypeConstante}}
							</select>
						</div>
					</div>
				
				</div>
				
				<div class="panel-footer">
				    <input type="hidden" name="nr" id="nr" value="{{$nr}}">
					<input type="submit" name="save" id="save" class="btn btn-sm btn-primary" value="Enregistrer">
				</div>
			</div>
		</form>
	</div>
</div>