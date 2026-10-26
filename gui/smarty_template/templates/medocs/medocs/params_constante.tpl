<ol class="breadcrumb">
	<li>Parametrage</li>
	<li>Constante</li>
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
				<h4 class="panel-title">Liste des Constante</h4>
			</div>
			<div class="body">
				<br>
				<table class="table table-striped table-bordered" id="datatable">
					<thead>
						<tr>
							<th>#</th>
							<th>Nom</th>
							<th>Type Constante</th>
							<th>Unité Défaut</th>
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
						<label class="label-control col-sm-4">Nom</label>
						<div class="col-sm-8">
							<input type="text" name="name" id="name" value="{{$name}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">LD var</label>
						<div class="col-sm-8">
							<input type="text" name="LD_var" id="LD_var" value="{{$LD_var}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">LD Description</label>
						<div class="col-sm-8">
							<input type="text" name="LD_description" id="LD_description" value="{{$LD_description}}" class="form-control input-xs">
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Type de Valeurs</label>
						<div class="col-sm-8">
						
							<select name="val_type" id="val_type" class="form-control input-xs">
								<option value="text" {{$defaultText}}>Texte</option>
								<option value="number" {{$defaultNumber}}>Nombre</option>
								<option value="select" {{$defaultList}}>Liste Personnalisée</option>
							</select>
							
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Type de constante</label>
						<div class="col-sm-8">
						
							<select name="measurement_type" id="measurement_type" class="form-control input-xs" onchange="return loadUnitDefault(this.value)">
								{{$selectOptionsTypeConstante}}
							</select>
							
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Valeur par Défaut</label>
						<div class="col-sm-8">
							<select name="unit_default" id="unit_default" class="form-control input-xs">
								{{$selectOptionsUnitDefault}}
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="label-control col-sm-4">Valeurs Possible</label>
						<div class="col-sm-8">
							<input type="text" name="val_possible" id="val_possible" class="form-control input-xs" value="{{$val_possible}}">
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