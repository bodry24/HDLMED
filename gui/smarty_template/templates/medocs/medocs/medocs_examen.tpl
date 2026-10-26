<form name="SaveBulletin" class="form-horizontal" action="" method="POST" onsubmit="return formControl();">
	<div class="row">
		<div class="form-group col-sm-12 text-center">
			<input type="submit" class="btn btn-sm btn-primary" name="saveBulletin" value="Enregister"/>
			<input type="submit" class="btn btn-sm btn-primary" name="save2NewBulletin" value="Enregister & Nouveau"/>
			<input type="reset" class="btn btn-sm btn-default" name="cancer" value="Annuler"/>
		</div>		
	</div>
	<div class="row">
		
		<div class="form-group col-sm-6 col-md-4 col-xs-12">
			<label class="control-label col-sm-4 col-md-6">Nom du Medécin Prescripteur</label>
			<div class="col-sm-8 col-md-6">
				<input class="form-control input-sm" type="input" name="medecin" id="medecin" value="{{$medecin}}">
			</div>
		</div>
		
		<div class="form-group col-sm-6  col-md-4 col-xs-12">
			<label class="control-label col-sm-4 col-md-6">Date prescription</label>
			<div class="col-sm-8 col-md-6">{{$datePrescription}}</div>
		</div>			

		<div class="form-group col-sm-6  col-md-4 col-xs-12">
			<label class="control-label col-sm-4 col-md-6">Numéro d'amission</label>
			<div class="col-sm-8 col-md-6">
				<input class="form-control input-sm" type="input" name="encounter_nr" id="encounter_nr" value="{{$encounter_nr}}" readonly="readonly">
			</div>
		</div>

		<div class="form-group col-sm-6  col-md-4 col-xs-12">
			<label class="control-label col-sm-4 col-md-6">Personnel</label>
			<div class="col-sm-8 col-md-6">
				<label class="control-label" for="oui">Oui</label>&nbsp;<input type="radio" name="personnel" id="oui" value="O">
				<label class="control-label" for="oui">Non</label>&nbsp;<input type="radio" checked name="personnel" id="non" value="N">
			</div>
		</div>


		<div class="form-group col-sm-6  col-md-4 col-xs-12">
			<label class="control-label col-sm-4 col-md-6">Numéro de re&ccedil;u</label>
			<div class="col-sm-8 col-md-6">
				<input class="form-control input-sm" type="input" name="code_recu" id="code_recu">
			</div>
		</div>
		
	</div>

	<div class="row" style="padding: 15px;">
		<div class="col-sm-6">
			<div class="form-group"  style="padding-right: 15px;">
				<label>Traitement en cours</label>
				<textarea name="traitement" class="form-control input-sm" rows="2">{{$traitement}}</textarea>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group" style="padding-left: 15px;">
				<label>Renseignements Cliniques ou Notes</label>
				<textarea name="renseignement" class="form-control input-sm" rows="2">{{$renseignement}}</textarea>
			</div>
		</div>
	</div>
		
	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title">Liste des actes de Radio</h3></div>
				<div class="panel-body">
					{{$listeExamens}}
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title">Panier des actes</h3></div>
				<div class="panel-body">
					<div id="panier" class="col-sm-12" style="height:310px; overflow:scroll;">
		    			<table class="table table-striped" id="tbPanier" width="100%">
		    				<tr style="background-color:#CCCCCC">
		    					<th style="width:10px">N&deg;</th>
		    					<th>Acte</th>
		    					<th>Prix</th>
		    					<th style="width:50px">Option</th>
		    				</tr>
		    			</table>
		    		</div>
				</div>
			</div>
		</div>

		<div class="form-group col-sm-12 text-center">
			<input type="submit" class="btn btn-sm btn-primary" name="saveBulletin" value="Enregister"/>
			<input type="submit" class="btn btn-sm btn-primary" name="save2NewBulletin" value="Enregister & Nouveau"/>
			<input type="reset" class="btn btn-sm btn-default" name="cancer" value="Annuler"/>
		</div>		
	</div>

</form>

<br><br>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Liste des Demandes d'Examens</h4>
			</div>
			<div class="body">
				<br>
				<table class="table table-striped table-bordered" id="datatable">
					<thead style="background-color: #02327E;color:#fff;">
						<tr>
							<th class="text-center" width="5%">N°</th>
							<th class="text-center" width="10%">Date Demande</th>
							<th class="text-center" width="15%">Prescripteur</th>
							<th class="text-center" width="20%">Examens</th>
							<th class="text-center" width="20%">Traitement</th>
							<th class="text-center" width="20%">Renseignements cliniques ou Notes</th>
							<th class="text-center" width="10%">Action</th>
						</tr>
					</thead>
					<tbody>
						{{$listing}}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="modalDetail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">DETAILS DE LA DEMANDE</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12">
                      
                        <table class="table">
                            <thead>
                            	<tr>
	                            	<th width="10%">N°</th>
	                            	<th width="50%">Désignation</th>
	                            	<th width="20%">Prix </th>
	                            	<th width="10%">Option</th>
	                            </tr>
                            </thead>
                            <tbody id="detailBody">
                            	
                            </tbody>
                        </table>
                        
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>