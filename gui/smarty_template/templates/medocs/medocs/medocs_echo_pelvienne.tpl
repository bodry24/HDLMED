{{if $listeEchographie}}

	<div class="row">
		<div class="col-sm-12">
			
				<div class="panel panel-primary">
					<div class="panel-heading">
						LISTE DES ECHOGRAPHIES PELVIENNES
					</div>
					<div class="panel-body">
						<table class="table table-bordered table-striped-hover" id="exemple">
							{{**<thead style="background-color: #00009C; color:white;">**}}
							<thead>
								<th class="text-center" width="5%">N°</th>
								<th class="text-center" width="15%">Date examen</th>
								<th class="text-center" width="25%">Prescripteur</th>
								<th class="text-center" width="25%">Réalisateur</th>
								<th class="text-center" width="10%">Actions</th>
							</thead>
							<tbody>
								{{$listing}}
							</tbody>
						</table>
					</div>
				</div>

		</div>
	</div>

{{else}}

<form method="POST" action="{{$urlAction}}" onsubmit="return controlForm();">
<center>
	<a href="{{$urlListe}}" class="btn-sm btn-success" style="color:white;text-decoration: none;">Liste des echographies pelviennes réalisées</a>
</center>
<br>
<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<label>PID</label>
			<input type="text" name="pid" value="{{$pid}}" id="pid" class="form-control input-sm" readonly="readonly">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Nom </label>
			<input type="text" name="name_last" value="{{$name_last}}" id="name_last" class="form-control input-sm" readonly="readonly">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Prénoms</label>
			<input type="text" name="name_first" value="{{$name_first}}" id="name_first" class="form-control input-sm" readonly="readonly">
		</div>
	</div>
	
	<div class="col-sm-3">
		<div class="form-group">
			<label>Age</label>
			<input type="text" name="age" value="{{$age}}" id="age" class="form-control input-sm" readonly="readonly">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<label>Marque échographe</label>
			<input type="text" name="marque" value="{{$marque}}" id="marque" class="form-control input-sm">
		</div>
	</div>
	
	<div class="col-sm-2">
		<div class="form-group">
			<label>Type</label>
			<input type="text" name="type" value="{{$type}}" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Date de mise en service</label>
			<input type="text" name="date_service_machine" value="{{$date_service_machine}}" id="date_service_machine" class="form-control input-sm">
		</div>
	</div>
	
</div>

<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<label>Indication de l'examen</label>
			<input type="text" name="indication_examen" value="{{$indication_examen}}" id="indication_examen" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Prescripteur</label>
			<input type="text" name="prescripteur" value="{{$prescripteur}}" id="prescripteur" class="form-control input-sm">
		</div>
	</div>
	
	<div class="col-sm-3">
		<div class="form-group">
			<label>Date de l'examen</label>
			<input type="text" name="date_examen" value="{{$date_examen}}" id="date_examen" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Date des dernières règles (DDR)</label>
			<input type="text" name="ddr" value="{{$ddr}}" id="ddr" class="form-control input-sm">
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<label>Voie d'abord utilisé</label>
			<input type="text" name="voie" value="{{$voie}}" id="voie" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Difficultés techniques</label>
			<div class="radio">
				<label class="radio-inline"><input type="radio" name="difficulte_technique" value="O" id="checkDifficulte">OUI</label>

				<label class="radio-inline"><input type="radio" name="difficulte_technique" value="N">NON</label>
			</div>
		</div>
	</div>
	
	<div class="col-sm-6">
		<div class="form-group">
			<label>Directive</label>
			<input type="text" name="description_technique" value="{{$description_technique}}" id="description_technique" class="form-control input-sm">
		</div>
	</div>
</div>
<hr>
<h4 class="text-center">UTERUS</h4>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Position</label>
			<div class="checkbox">
				<label class="checkbox-inline"><input type="checkbox" name="position" value="anteverse"> Antéversé</label>
				<label class="checkbox-inline"><input type="checkbox" name="position" value="retroverse"> Rétroversé </label>
				<label class="checkbox-inline"><input type="checkbox" name="position" value="intermediaire"> Intermédiaire</label>
				<label class="checkbox-inline"><input type="checkbox" name="position" value="rotation">Rotation </label>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Contours réguliers</label>
			<div class="radio">
				<label class="radio-inline"><input type="radio" name="contours" value="O">OUI</label>

				<label class="radio-inline"><input type="radio" name="contours" value="N">NON</label>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-6">
		<label>Aspect homogène</label>
		<div class="radio">
			<label class="radio-inline"><input type="radio" name="aspect_homogene" value="O">OUI</label>

			<label class="radio-inline"><input type="radio" name="aspect_homogene" value="N">NON</label>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Col : aspect habituel</label>
			<div class="radio">
				<label class="radio-inline"><input type="radio" name="col" value="O">OUI</label>

				<label class="radio-inline"><input type="radio" name="col" value="N">NON</label>
			</div>
		</div>
	</div>
</div>
<hr>
<h4 class="text-center">ENDOMETRE</h4>


<div class="row">
	<div class="col-sm-4">
		<label>Aspect compatible avec le jour du cycle ou le statut hormonal </label>
		<div class="radio">
			<label class="radio-inline"><input type="radio" name="aspect_endometre" value="O">OUI</label>

			<label class="radio-inline"><input type="radio" name="aspect_endometre" value="N">NON</label>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label>Aspect homogene</label>
			<div class="radio">
				<label class="radio-inline"><input type="radio" name="aspect_homogene_endometre" value="O">OUI</label>

				<label class="radio-inline"><input type="radio" name="aspect_homogene_endometre" value="N">NON</label>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label>Présence d'une image endométriale</label>
			<div class="radio">
				<label class="radio-inline"><input type="radio" name="image_endometriale" value="O">OUI</label>

				<label class="radio-inline"><input type="radio" name="image_endometriale" value="N">NON</label>
			</div>
		</div>
	</div>
</div>
<hr>
<h4 class="text-center">ANNEXES</h4>
<b><center>-Pour chaque ovaire, préciser</center></b>
<div class="row">
	<div class="col-sm-3">
		si visible
		<div class="radio">
			<label class="radio-inline"><input type="radio" name="visible_ovaire" value="O">OUI</label>

			<label class="radio-inline"><input type="radio" name="visible_ovaire" value="N">NON</label>
		</div>
	</div>
	<div class="col-sm-3">
		Si vu, en position normale 
		<div class="radio">
			<label class="radio-inline"><input type="radio" name="position_normale" value="O">OUI</label>

			<label class="radio-inline"><input type="radio" name="position_normale" value="N">NON</label>
		</div>
	</div>
	<div class="col-sm-3">
		Si présence de follicule(s) ou de corps jaune
		<div class="radio">
			<label class="radio-inline"><input type="radio" name="presence_follicule" value="O">OUI</label>

			<label class="radio-inline"><input type="radio" name="presence_follicule" value="N">NON</label>
		</div>
	</div>
	<div class="col-sm-3">
		Si présence d'une image kystique ovarienne (> 30 mm) ou latéro-utérine
		<div class="radio">
			<label class="radio-inline"><input type="radio" name="presence_image_kystique" value="O">OUI</label>

			<label class="radio-inline"><input type="radio" name="presence_image_kystique" value="N">NON</label>
		</div>
	</div>
</div>
<b><center>-Anomalie tubaire </center></b>
<div class="row">
	<div class="col-sm-12 text-center">

		<div class="radio">
			<label class="radio-inline"><input type="radio" name="anomalie_tubaire" value="O">OUI</label>

			<label class="radio-inline"><input type="radio" name="anomalie_tubaire" value="N">NON</label>
		</div>
	</div>
</div>
<hr>
<h4 class="text-center">EPANCHEMENT</h4>
<div class="row">
	<div class="col-sm-6">
		<label>Présence</label>
		<div class="radio">

			<label class="radio-inline"><input type="radio" name="presence_epanchement" value="O" id="checkEpanchement">OUI</label>
			<label class="radio-inline"><input type="radio" name="presence_epanchement" value="N">NON</label>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="radio">
			<label>Préciser la localisation</label>
			<select name="localisation_epanchement" id="localisation_epanchement" class="form-control input-sm">
				{{$optionsLocalisationEpanchement}}
			</select>
		</div>
	</div>
</div>
<hr>
<h4 class="text-center">CONCLUSION</h4>
<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<textarea name="conclusion" rows="3"  id="conclusion" class="form-control input-sm">{{$conclusion}}</textarea> 
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12 text-center">
		<input type="hidden" name="id" value="{{$id}}">
		<input type="submit" name="save" id="save" value="Enregistrer" class="btn btn-sm btn-primary">
	</div>
</div>

<br>
</form>

{{/if}}