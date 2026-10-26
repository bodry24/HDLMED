{{if $crumbArray}}
<div class="row">
	<div class="col-sm-12">
		<ul class="breadcrumb"  style="margin-right: 0px;margin-left: 0px;">
			{{foreach from=$crumbArray item=crumb}}
				<li class="{{$breadcrumb.class}}">
					{{if $crumb.class=="active"}}
						{{$crumb.titre}}
					{{else}}
						<a href="{{$crumb.url}}">{{$crumb.titre}}</a>
					{{/if}}
				</li>
			{{/foreach}}
		</ul>
	</div>
</div>
{{/if}}

<div class="row">
	<div class="col-sm-9">
		<form name="passation" action="" method="POST">
			<table class="table table-bordered table-striped-hover">
				<thead>
					<th class="text-center" width="5%">N°</th>
					<th class="text-center" width="15%">Occupation</th>
					<th class="text-center" width="15%">Nom & Prénoms</th>
					<th class="text-center" width="15%">Age</th>
					<th class="text-center" width="50%">Passation</th>
				</thead>
				<tbody>
					{{$form}}
				</tbody>
				<tfoot>
					{{$submit}}
				</tfoot>
			</table>
		</form>

	</div>

	<div class="col-sm-3">
		<div class="list-group">
		  <a href="{{$urlUrgence}}" class="list-group-item {{if $activeMenuDroite=='urgence'}}active{{/if}}">Urgence</a>
		  <a href="{{$urlPediatrie}}" class="list-group-item {{if $activeMenuDroite=='pediatrie'}}active{{/if}}">Pédiatrie</a>
		  <a href="{{$urlChirurgical}}" class="list-group-item {{if $activeMenuDroite=='chirurgical'}}active{{/if}}">Hospitalisation Chirurgical</a>
		  <a href="{{$urlMedical}}" class="list-group-item {{if $activeMenuDroite=='medical'}}active{{/if}}">Hospitalisation Médical</a>
		  <a href="{{$urlAccouchement}}" class="list-group-item {{if $activeMenuDroite=='accouchement'}}active{{/if}}">Accouchement</a>
		  <a href="{{$urlSoinsIntensif}}" class="list-group-item {{if $activeMenuDroite=='intensif'}}active{{/if}}">Soins Intensifs</a>
		</div> 
		<br><br><br><br>
		<div class="panel panel-primary" style="border-color: #02327E;">
			<div class="panel-heading" style="background: #02327E;color:white;">
				LISTE DES PASSATIONS
			</div>
			<div class="panel-body">
				<table class="table table-bordered table-striped-hover" id="exemple">
				<thead>
					<th class="text-center" width="5%">N°</th>
					<th class="text-center" width="35%">Date & Heure</th>
					<th class="text-center" width="35%">Medécin</th>
					<th class="text-center" width="20%">Actions</th>
				</thead>
				<tbody>
					{{$listing}}
				</tbody>
			</table>
			</div>
			
		</div>
	</div>
</div>