<div class="row">
	<div class="col-sm-12">
		<form method="post">
		
			<table class="table table-bordered table-responsive">
				{{$formConstante}}
			</table>

			<div class="row">
				<div class="col-sm-12 text-center">
					<input type="submit" name="save" id="save" value="Enregistrer" class="btn btn-sm btn-primary">
				</div>
			</div>
			
		</form>
	</div>
</div>
<br><br>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-bordered table-responsive" id="exemple">
			<thead>
				<th class="text-center">N°</th>
				<th class="text-center">Date et heure</th>
				<th class="text-center">N° Admission</th>
				<th class="text-center">Enrégistrer Par</th>
				<th class="text-center">Actions</th>
			</thead>
			<tbody>
				{{$listingMeasurement}}
			</tbody>

		</table>
	</div>
</div>

<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">         
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times</span>
                </button>
                <h4 class="modal-title text-center">DETAIL DES CONSTANTES PRISES </h4>
            </div>   
            <div class="modal-body">
                <table class="table table-bordered table-striped-hover table-responsive">
                	<thead>
                		<tr>
                			<th class="text-center">N°</th>
                			<th class="text-center">Constante</th>
                			<th class="text-center">Valeur</th>
                			<th class="text-center">Unité de mesure</th>
                		</tr>
                	</thead>
                	<tbody id="detailContent">
                		
                	</tbody> 
                </table>
            </div>
         </div>
    </div>
</div>
