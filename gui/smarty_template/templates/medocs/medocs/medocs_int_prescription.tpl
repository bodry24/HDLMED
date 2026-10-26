<div class="row">
	<div class="col-sm-12">

		<form method="post" action="" name="reportform" onsubmit="return chechSubmit();">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-3">
						<label class="control-form">Médicament</label>
						<input type="text" name="medicament" id="medicament" class="form-control input-sm" value="{{$medicament}}">
						<input type="hidden" name="bestellnum" id="bestellnum" value="{{$bestellnum}}" class="form-control input-sm">
					</div>
					<div class="col-sm-3">
						<label class="control-form">Repère</label>
						<select name="repere" id="repere" class="form-control input-sm" onchange="return loadRepere();">
							{{$optionsReperes}}
						</select>
					</div>
					<div class="col-sm-3">
						<label class="control-form">Dose</label>
						<input type="text" name="quantite" id="quantite" value="{{$quantite}}" class="form-control input-sm">
						
					</div>
					<div class="col-sm-3">
						<div class="cachePosologie">	
							<label class="control-form">Posologie</label>
							<select name="posologie" id="posologie" class="form-control input-sm">
								{{$optionsPosologies}}
							</select>
						</div>
						<div class="cacheUniteDuree">	
							<div class="row text-center">
								<label class="control-form">Fréquence</label>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<input type="text" name="duree" name="duree" value="{{$duree}}" class="form-control input-sm">
								</div>
								<div class="col-sm-6">
							        <select name="unite_duree" id="unite_duree" class="form-control input-sm">
								        {{$optionsUniteDurees}}
							        </select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<div class="row fluid"  style="margin-left:-15px;margin-right: -15px;">
							<div class="col-sm-12">
								<label class="control-form">Nombre de Jours</label>
								<input type="text" name="nbre_fois" id="nbre_fois" value="{{$nbre_fois}}" class="form-control input-sm" {{$readonly}}>
							</div>
							<input type="hidden" name="date_debut" value="{{$date_debut}}" id="date_debut" class="form-control input-sm">
							<input type="hidden" name="date_fin" value="{{$date_fin}}" id="date_fin" class="form-control input-sm">
							<input type="hidden" name="date_effective" value="{{$date_effective}}" id="date_effective" class="form-control input-sm">
							<!--
							<div class="col-sm-12">
								<label class="control-form">Date Début</label>
								<input type="text" name="date_debut" value="{{$date_debut}}" id="date_debut" class="form-control input-sm">
							</div>
							 <div class="col-sm-12">
								<label class="control-form">Date Fin</label>
								<input type="text" name="date_fin" value="{{$date_fin}}" id="date_fin" class="form-control input-sm">
							</div>
							<div class="col-sm-12">
								<label class="control-form">Date Effective</label>
								<input type="text" name="date_effective" value="{{$date_effective}}" id="date_effective" class="form-control input-sm">
							</div> -->
						</div>
					</div>
					<div class="col-sm-9">
						<label class="control-form">Commentaire</label>
						<textarea name="commentaire" id="commentaire" rows="5" class="form-control input-sm">{{$commentaire}}</textarea>
					</div>
				</div>
				<br>
				<div class="row">
					  <div class="col-sm-12 text-center">		
						<input type="hidden" name="id_prescription" value="{{$idPrescription}}">
					    <input type="hidden" id="encounter_nr" name="encounter_nr" value="{{$encounterNr}}">
					    <input type="hidden" id ="pid" name="pid" value="{{$pid}}">
					    <input type="hidden" name="mode" value="{{$mode}}">
						<input type="submit" name="save" id="save" class="btn btn-sm btn-primary" value="Enregistrer">
					</div>
				</div>
			</div>
			
			    		
		</form>

	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-primary">
			<div class="panel-heading" style="background-color: #02327E">
				<h4 class="panel-title">Traitements</h4>
			</div>
			<div class="body">
				<br>
				<table id="exemple" class="table table-bordered table-striped-hover" style="padding: 5px;">
					<thead>
		                <tr>
		                    <th class="text-center">N°</th>
					        <th class="text-center">Produit</th>
					        <th class="text-center">Dose</th>
					    	<th class="text-center">Posologie</th>
					        <th class="text-center">Unité</th>
					        <th class="text-center">Actions</th>
		                </tr>
		            </thead>
					<tbody>
						{{$listingOrdonnances}}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">         
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times</span>
                </button>
                <h4 class="modal-title text-center">DETAIL DE L'ORDONNANCE</h4>
            </div>   
            <div class="modal-body">
                <table class="table table-bordered table-striped-hover table-responsive">
                	<thead>
                		<tr>
                			<th class="text-center">N°</th>
                			<th class="text-center">Médicament</th>
                			<th class="text-center">Quantité</th>
                			<th class="text-center">Type d'application</th>
                			<th class="text-center">Posologie</th>
                		</tr>
                	</thead>
                	<tbody id="detailContent">
                		
                	</tbody> 
                </table>
            </div>
         </div>
    </div>
</div>
