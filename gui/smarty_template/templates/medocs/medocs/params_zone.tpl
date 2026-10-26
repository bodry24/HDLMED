
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
				<h4 class="panel-title">Constante</h4>
			</div>
			<div class="panel-body">
				<ul class="list-group">
					{{$listeConstantes}}
				</ul>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="form-group">
					<label class="control-label col-sm-4">Zone(Ward): </label>
					<div class="col-sm-8">
						<select class="form-control input-xs" name="ward" id="ward"><option value="">Sélectionner</option>{{$wardOptions}}</select>
					</div>
					<br>
				</div>
			</div>
			<div class="panel-body">
				<ul class="list-group" id="params_specialite">
					{{$listConsantesZone}}
				</ul>
			</div>
			<div class="panel-footer text-right">
				<input type="submit" name="enreg" id="enreg" onclick="return saveDetail();" class="btn btn-xs btn-primary" value="Enregistrer">
			</div>
		</div>
	</div>
</div>