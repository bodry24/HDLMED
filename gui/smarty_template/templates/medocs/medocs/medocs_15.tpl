
<form method="POST" action="" class="form-horizontal">
	<div class="row">

		<div class="col-sm-3">
			<div class="col-sm-12 form-group">
				<label>Date début</label>
				<input type="text" name="date_debut" value="{{$date_debut}}" id="date_debut" class="form-control input-sm">	
			</div>
		</div>

		<div class="col-sm-3">
			<div class="col-sm-12 form-group">
				<label>Date fin</label>
				<input type="text" name="date_fin" value="{{$date_fin}}" id="date_fin" class="form-control input-sm">	
			</div>
		</div>

		<div class="col-sm-3">
			<div class="col-sm-12 form-group">
				<label>Docteur</label>
				<select name="medecin" id="medecin" class="form-control input-sm">
					{{$listingDocteurs}}
				</select>	
			</div>
		</div>

		<div class="col-sm-3">
			<div class="col-sm-12 form-group">
				<br>
				<input type="submit" name="afficher" value="Afficher" class="btn btn-sm btn-primary">
			</div>
		</div>
		
	</div>
</form>
 
{{if $listing}}
<div class="row">
	<div class="col-sm-12">
		<hr>
		{{$linkPdf}}
		<hr>
		<table class="table table-responsive table-bordered table-striped-hover">
			<thead>
				<th class="text-center">N°</th>
				<th class="text-center">Date</th>
				<th class="text-center">Nom et Prénoms</th>
				<th class="text-center">Age</th>
				<th class="text-center">Sexe</th>
				<th class="text-center">Diagnostic</th>
				<th class="text-center">Bilan</th>
				<th class="text-center">Vaccin</th>
				<th class="text-center">Mensuration</th>
				<th class="text-center">Traitement</th>
			</thead>
			<tbody>
				{{$listing}}
			</tbody>
		</table>
	</div>
</div>
{{/if}}