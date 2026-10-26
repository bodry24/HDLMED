<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Parametres</h4>
			</div>
			<div class="panel-body">
				<ul class="list-group">
					{{$listParams}}
				</ul>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="form-group">
					<label class="control-label col-sm-4">Spécialité: </label>
					<div class="col-sm-8">
						<select class="form-control input-xs" onchange="" name="specialite" id="specialite"><option value="">Selectionner</option>{{$specialiteOptions}}</select>
					</div>
					<br>
				</div>
			</div>
			<div class="panel-body">
				<ul class="list-group" id="params_specialite">
					{{$listParamsSpecialite}}
				</ul>
			</div>
			<div class="panel-footer text-right">
				<input type="submit" name="enreg" id="enreg" value="Enregistrer" onclick="return saveDetail();" class="btn btn-xs btn-primary">
			</div>
		</div>
	</div>
</div>