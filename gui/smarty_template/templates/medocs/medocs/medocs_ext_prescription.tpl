<div class="row">
	<div class="col-sm-12">

		<form method="post" action="" name="reportform" onsubmit="return chechSubmit();">
			<table class="table table-bordered table-responsive">
				<thead>
			    	<tr style="background-color:#02327E; color:white;font-weight: bold">
					    <th class="text-center" width="30%"> Médicament</th>
					    <th class="text-center" width="15%">Quantité</th>
					    <th class="text-center" width="20%">Type d'Application</th>
					    <th class="text-center" >Posologie</th>
					    <th class="text-center" width="5%">-</th>
					</tr>
					<tr>
						<td>
							<input type="text" name="medicament" id="medicament" class="form-control input-sm">
							<input type="hidden" name="article" id="article">
						</td>
						<td><input type="text" name="qte" id="qte" class="form-control input-sm"></td>
						<td>
							<select name="application_type_nr" id="application_type_nr" class="form-control input-sm">
								<option value=""></option>
								{{$selectOptions}}
							</select>
						</td>
						<td>
							<input type="text" name="posologie" id="posologie" value="" class="form-control input-sm">
						</td>
						<td>
							<sapn class="btn btn-sm btn-primary glyphicon glyphicon-plus" onclick="addRow();"></sapn>
						</td>
						
					</tr>
			    </thead>
			    <tbody id="tableContent">

					{{$elementsPrescription}}
					
			    </tbody>
			    <tfoot>
			    	<tr>
			    		<td colspan="5" class="text-center">
			    			<input type="hidden" name="prescriber_id" value="{{$prescriberId}}">
						    <input type="hidden" name="prescriber" size=50 maxlength=60 value="{{$prescriber}}" readonly>
						    <input type="hidden" id="encounter_nr" name="encounter_nr" value="{{$encounterNr}}">
						    <input type="hidden" id ="pid" name="pid" value="{{$pid}}">
						    <input type="hidden" name="mode" value="{{$mode}}">
						    <input type="hidden" name="type" value="PRODUIT" id="type"/>

			    			<input type="submit" name="save" id="save" class="btn btn-sm btn-primary" value="Enregistrer">
			    		</td>
			    	</tr>
			    </tfoot>
			</table>
		</form>

	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-primary">
			<div class="panel-heading" style="background-color: #02327E">
				<h4 class="panel-title">Liste des Ordonnances</h4>
			</div>
			<div class="body">
				<br>
				<table id="exemple" class="table table-bordered table-striped-hover" style="padding: 5px;">
					<thead>
		                <tr>
		                    <th class="text-center">N°</th>
					        <th class="text-center">Code Ordonnance</th>
					    	<th class="text-center">Date</th>
					        <th class="text-center">Prescripteur</th>
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
