{{if $consulter}}

<div class="row">
	<div class="col-sm-12">
		
		<div class="panel panel-primary">
			<div class="panel-heading">
				LISTE DES RAPPORTS
			</div>
			<div class="panel-body">
				<table class="table table-bordered table-striped-hover" id="exemple">
					{{**<thead style="background-color: #00009C; color:white;">**}}
					<thead>
						<th class="text-center" width="5%">N°</th>
						<th class="text-center" width="30%">Expéditeur</th>
						<th class="text-center" width="30%">Patient</th>
						<th class="text-center" width="15%">Date</th>
						<th class="text-center" width="10%">Statut</th>
						<th class="text-center" width="10%">Actions</th>
					</thead>
					<tbody>
						{{$listingRapport}}
					</tbody>
				</table>
			</div>
		</div>

	</div>
</div>

{{else}}

<div class="row">
	<div class="col-sm-6">
		
		<div class="panel panel-primary">
			<div class="panel-heading">
				LISTE DES RAPPORTS
			</div>
			<div class="panel-body">
				<table class="table table-bordered table-striped-hover" id="exemple">
					{{**<thead style="background-color: #00009C; color:white;">**}}
					<thead>
						<th class="text-center" width="5%">N°</th>
						<th class="text-center" width="60%">Docteur</th>
						<th class="text-center" width="25%">Date</th>
						<th class="text-center" width="15%">Actions</th>
					</thead>
					<tbody>
						{{$listing}}
					</tbody>
				</table>
			</div>
		</div>

	</div>

	<div class="col-sm-6">
		<form name="frmContrat" action="" method="POST" onsubmit="return validation()">
			<div class="panel panel-primary">
				<div class="panel-heading">
					ENREGISTREMENT
				</div>
				<div class="panel-body">
					<div class="row">
						<label class="control-form">Docteur </label>
						<div class="form-group">
							<select name="recipient" class="form-control" id="recipient">
									{{$recipientOptions}}
							</select>
						</div>
					</div>
					<div class="row">
						<label class="control-form">Date et Heure</label>
						<div class="form-group">
							<input type="text" name="send_dt" id="send_dt" value="{{$send_dt}}" maxlength="10" class="form-control">
						</div>
					</div>
					<div class="row">
						<label class="control-form">Contenu</label>
						<div class="form-group">
							<textarea name="body" id="body" rows="3" cols="100%" class="form-control input-sm">{{$body}}</textarea>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<input type="hidden" name="id" value="{{$value}}">
					<input type="submit" name="save" value="Enrégistrer" class="btn btn-sm btn-primary">
				</div>
			</div>
			
		</form>
	</div>
	
</div>

{{/if}}

<div id="modalRapport" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title text-center">CONSULTATION DU RAPPORT</h4>
		</div>
		<div class="modal-body" id="contenu">
			
                
 	 	</div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	    </div>
    </div>

  </div>
</div>