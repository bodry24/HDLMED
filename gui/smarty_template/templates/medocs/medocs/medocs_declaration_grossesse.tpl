<div class="row">
	<div class="col-sm-12">

	<form name="frmDeclarationGrossesse" method="POST" action="">

	<div class="panel panel-primary">
		<div class="panel-heading">
			DECLARATION DE GROSSESSE
		</div>
		<div class="panel-body">

			<ul  class="nav nav-tabs" style="margin-left: 15px;margin-right: 15px;">
				<li class="active"><a  href="#1b" data-toggle="tab">INFORMATIONS GENERALES</a></li>
				<li><a href="#2b" data-toggle="tab">ANTECEDENT</a></li>
				<li><a href="#3b" data-toggle="tab">ANTECEDENT CONJOINT</a></li>
			</ul>
			<br>
			<div class="tab-content" role="tabpane" >
			  	<div class="tab-pane active" id="1b">
			  		<div class="row">
			  			<div class="col-sm-6 text-right">
			  				<label>Date declaration grossesse</label>
			  			</div>
			  			<div class="col-sm-6 text-center">
							<input type="text" name="date_declaration" id="date_declaration" value="{{$date_declaration}}" class="form-control input-sm" style="width: 200px;">
			  			</div>
			  		</div>
			  		<div class="row">
						<div class="col-sm-2">
							<label>Date des dernières règles normales</label>
							<input type="text" name="date_derniere_regle" id="date_derniere_regle" value="{{$date_derniere_regle}}" class="form-control input-sm" >
						</div>
						<div class="col-sm-2">
							<label>Date de Fécondation problème</label>
							<input type="text" name="date_fecondation" id="date_fecondation" class="form-control input-sm" value="{{$date_fecondation}}">
						</div>
						<div class="col-sm-2">
							<label>Datation écho.: Fécondation le</label>
							<input type="text" name="date_datation_echo" id="date_datation_echo" class="form-control input-sm" value="{{$date_datation_echo}}">
						</div>
						<div class="col-sm-2">
							<label>Selon l'écographie réalisée le</label>
							<input type="text" name="date_echo" id="date_echo" class="form-control input-sm" value="{{$date_echo}}">
						</div>
						<div class="col-sm-2">
							<label>A<br>&nbsp;</label>
							<input type="text" name="lieu_echo" id="lieu_echo" class="form-control input-sm" value="{{$lieu_echo}}">
						</div>
						<div class="col-sm-2">
							<label>Date d'Accouchemeent<br>&nbsp;</label>
							<input type="text" name="date_accouchement" id="date_accouchement" class="form-control input-sm" value="{{$date_accouchement}}">
						</div>
					</div>
					<br>	
					<div class="row">
						<div class="col-sm-3">
							<label>Parité</label>
							<input type="text" name="parite" id="parite" class="form-control input-sm" value="{{$parite}}">
						</div>
						<div class="col-sm-3">
							<label>Poids avant Grossesse</label>
							<input type="text" name="poids_avant_grossesse" id="poids_avant_grossesse" class="form-control input-sm" value="{{$poids_avant_grossesse}}">
						</div>
						<div class="col-sm-3">
							<label>Taille</label>
							<input type="text" name="taille" id="taille" class="form-control input-sm" value="{{$taille}}">
						</div>
						<div class="col-sm-3">
							<label>Bassin</label>
							<input type="text" name="bassin" id="bassin" class="form-control input-sm" value="{{$bassin}}">
						</div>
					</div>
			  	</div>
			  	<!-- Antécédent du patient -->
			  	<div class="tab-pane" id="2b">
			  		
			  		<div class="row">
			  			<div class="col-sm-2">
			  				<label>Contraception</label>
			  			</div>
			  			<div class="col-sm-2">
							<input type="text" name="contraception" id="contraception" class="form-control input-sm" value="{{$contraception}}">
			  			</div>
			  			<div class="col-sm-2">
			  				<label>Algies Pelviennes</label>
			  			</div>
			  			<div class="col-sm-2">
							<input type="text" name="algie" id="algie" class="form-control input-sm" value="{{$algie}}">
			  			</div>
			  			<div class="col-sm-2">
			  				<label>Infections pelviennes</label>
			  			</div>
			  			<div class="col-sm-2">
							<input type="text" name="infection" id="infection" class="form-control input-sm" value="{{$infection}}">
			  			</div>
			  		</div>

			  	</div>
			  	<!-- Antécédent du Conjoint -->
			  	<div class="tab-pane" id="3b">
			  		<div class="row">
			  			<div class="col-sm-2">
			  				<label>Groupe</label>
			  			</div>
			  			<div class="col-sm-2">
							<input type="text" name="groupe_conjoint" id="groupe_conjoint" class="form-control input-sm" value="{{$groupe_conjoint}}">
			  			</div>
			  			<div class="col-sm-2">
			  				<label>Rhésus</label>
			  			</div>
			  			<div class="col-sm-2">
							<input type="text" name="rhesus_conjoint" id="rhesus_conjoint" class="form-control input-sm" value="{{$rhesus_conjoint}}">
			  			</div>
			  			<div class="col-sm-2">
			  				<label>Electrophorèse de l'hémoglobine</label>
			  			</div>
			  			<div class="col-sm-2">
							<input type="text" name="elctrophorese_conjoint" id="elctrophorese_conjoint" class="form-control input-sm" value="{{$elctrophorese_conjoint}}">
			  			</div>
			  		</div>
			  	</div>
			</div>
		</div>
		<div class="panel-footer text-center">
			<input type="hidden" name="id" value="{{$id}}">
			<input type="submit" name="save" value="Enregistrer" id="save" class="btn btn-sm btn-primary">
		</div>
	</div>

	</form>

	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				LISTE DES GROSSESSES 
			</div>
			<div class="panel-body">
				<table class="table table-bordered table-striped-hover" id="exemple">
					<thead>
						<th class="text-center">N°</th>
						<th class="text-center">Date</th>
						<th class="text-center">Parité</th>
						<th class="text-center">Date dernière regle</th>
						<th class="text-center">Poids avant Grossesse</th>
						<th class="text-center">Actions</th>
					</thead>
					<tbody>
						{{$listing}}
					</tbody>
				</table>
			</div>
		</div>	
	</div>	
</div>
