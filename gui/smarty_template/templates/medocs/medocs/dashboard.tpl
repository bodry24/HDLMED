<div class="col-sm-6">
	<div class="panel panel-primary">
		<div class="panel-heading">
			RECHERCHE
		</div>
		<div class="panel-body">
			<form class="form-horizontal" name="form_search" id="form_search" action="" method="POST">
				<div class="row">
					<div class="form-group col-sm-9">
						<input type="text" name="search" class="form-control">
					</div>
					<div class="form-group col-sm-3">
						<input type="submit" name="chercher" value="Chercher" class="btn btn-success">
					</div>
				</div>
			</form>
		</div>
	</div>

	{{if $listingResearch}}
	<div class="panel panel-primary">
		<div class="panel-heading">
			RESULTAT RECHERCHE
		</div>
		<div class="panel-body">

			<table class="table table-bordered table-striped table-hover" id="datatable2">
				<thead>
					<tr>
						<th class="text-center">N°</th>
						<th class="text-center">Nom</th>
						<th class="text-center">Prénoms</th>
						<th class="text-center">Nom d'épouse</th>
						<th class="text-center">Sexe</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					{{$listingResearch}}
				</tbody>
			</table>
		</div>
	</div>
	{{else}}
	<div class="panel panel-primary">
		<div class="panel-heading" >
			LISTE DES OCCUPATIONS
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-12">
					<div class="well well-sm text-center">
						<b>Passation de service</b>
						<div class="btn-group">
						  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Sélectionner le département <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a href="{{$urlUrgence}}">Urgence</a></li>
						    <li role="separator" class="divider"></li>
						    <li><a href="{{$urlPediatrie}}">Pédiatrie</a></li>
						    <li role="separator" class="divider"></li>
						   	<li class="dropdown-submenu">
				                <a tabindex="-1" href="#">Hospitalisation</a>
				                <ul class="dropdown-menu">
				                  <!-- <li><a tabindex="-1" href="#">Second level</a></li> -->
				                  <li><a href="{{$urlChirurgical}}">Chirurgical</a></li>
				                  <li><a href="{{$urlMedical}}">Médical</a></li>
				                </ul>
				            </li>
						    <li role="separator" class="divider"></li> 
						    <li><a href="#">Accouchement</a></li>
						    <li role="separator" class="divider"></li>
						    <li><a href="#">Soins Intensifs</a></li>
						   
						  </ul>
						</div>

					</div>
				</div>
			</div>
			
			
			<table class="table table-bordered table-striped table-hover" id="idOccupation">
				<thead>
					<tr>
						<th class="text-center">N°</th>
						<th class="text-center">Nom et Prénoms</th>
						<th class="text-center">N° Admission</th>
						<th class="text-center">Chambre</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					{{$listingOccupation}}
				</tbody>
			</table>
		</div>
	</div>
	{{/if}}
</div>
<div class="col-sm-6">
	<div class="panel panel-primary">
		<div class="panel-heading">
			LISTE DES RENDEZ VOUS
		</div>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-hover" id="idRendezVous">
				<thead>
					<tr>
						<th class="text-center">N°</th>
						<th class="text-center">Nom et Prénoms</th>
						<th class="text-center">Plage</th>
						<th class="text-center">Nature</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					{{$listingAppointment}}
				</tbody>
			</table>
		</div>
	</div>
</div>