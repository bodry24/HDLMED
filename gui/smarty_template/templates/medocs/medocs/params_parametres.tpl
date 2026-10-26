
<ol class="breadcrumb">
	<li>Parametrage</li>
	<li>Paramètres</li>
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
				<h4 class="panel-title">Liste des Paramètres</h4>
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
							<input type="text" name="designation" id="designation" value="{{$designation}}" class="form-control input-xs">
						</div>
					</div>
					
					<div class="form-group">
						<label class="label-control col-sm-4">Nom dans la Base de Données</label>
						<div class="col-sm-8">
							<input type="text" name="nameValue" id="nameValue" value="{{$nameValue}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Type Input</label>
						<div class="col-sm-8">
							<select name="typeInput" id="typeInput" class="form-control">
								{{$selectOptions}}
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Valeur par Défaut</label>
						<div class="col-sm-8">
							<input type="text" name="defaultValue" id="defaultValue" value="{{$defaultValue}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Valeur</label>
						<div class="col-sm-8">
							<input type="text" name="value" id="value" value="{{$value}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Description du champ</label>
						<div class="col-sm-8">
							<input type="text" name="placeholder" id="placeholder" value="{{$placeholder}}" class="form-control input-xs">
						</div>
					</div>
				</div>
				<div class="panel-footer">
				    <input type="hidden" name="id" id="id" value="{{$id}}">
					<input type="submit" name="save" id="save" class="btn btn-sm btn-primary" value="Enregistrer">
				</div>
			</div>
		</form>
	</div>
</div>