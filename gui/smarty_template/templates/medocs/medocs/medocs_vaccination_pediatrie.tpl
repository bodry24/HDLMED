<div class="row">
	<div class="col-sm-12">
		{{$listingVaccination}}
	</div>
</div>

<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">         
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times</span>
                </button>
                <h5 class="modal-title text-center"><b>MISE A JOUR VACCINATION</b></h5>
            </div> 

            <div class="modal-body">
               <div class="row">
               		<div class="form-group">
               			<label class="col-sm-4 control-label text-right">Date</label> 
               			<div class="col-sm-8">

               				<input type="hidden" name="updatePid" id="updatePid">
		                    <input type="hidden" name="updateVacPid" id="updateVacPid">
		                    <input type="hidden" name="updateVid" id="updateVid">
		                    <input type="text" name="dateEffectuee" id="dateEffectuee" class="form-control input-sm">
               			
               			</div>
               		</div>
               </div>
            </div>

            <div class="modal-footer">
            	<div class="form-group">
      				<div class="col-sm-12 text-center">
      				    <input type="button" value="Valider" id="update" class="btn btn-sm btn-primary">
                		<!-- <input id="frmSubmit" type="submit" class="btn btn-sm btn-primary" value="Valider">   -->   
      				</div>
      			</div>	
            </div>
         </div>
    </div>
</div>

<div class="modal fade" id="modalForm2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">         
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times</span>
                </button>
                <h5 class="modal-title text-center"><b>AUTRE VACCIN</b></h5>
            </div> 

            <div class="modal-body">
               <div class="row">
               		<div class="form-group">
               			<label class="col-sm-4 control-label text-right">Libellé</label> 
               			<div class="col-sm-8">

               				<input type="text" name="nom_vaccin" id="nom_vaccin" class="form-control input-sm">
		                   
               			</div>
               		</div>
					<br><br>
               		<div class="form-group">
               			<div class="col-sm-4 text-right"><label class="text-right">Période</label></div>
               			<div class="col-sm-4"><input type="text" name="periode_vaccin" id="periode_vaccin" class="form-control input-sm"></div>
               			<div class="col-sm-4">
               				<select name="type_periode" id="type_periode" class="form-control input-sm col-sm-4">
	                            <option value="semaines" size="30">Semaine(s)</option>
	                            <option value="mois" size="30">Mois</option>
	                            <option value="annees" size="30">Année(s)</option>
	                        </select>
               			</div>
               			
               			
               		</div>

               </div>
				
             
            </div>

            <div class="modal-footer">
            	<div class="form-group">
      				<div class="col-sm-12 text-center">
      				   
                		 <input type="button" name="nouveau_vaccin" id="nouveau_vaccin" value="Valider" class="btn btn-sm btn-primary">
      				</div>
      			</div>	
            </div>
         </div>
    </div>
</div>