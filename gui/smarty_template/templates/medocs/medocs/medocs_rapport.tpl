{{** Le Rapport elle memem **}}
<div class="row">
	<div class="col-sm-12">
		{{$content}}
	</div>
</div>


{{** Ajout d'élément au rapport **}}
<div id="addElement" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">AJOUT D'UN ELEMENT AU RAPPORT</h4>
      </div>
      <div class="modal-body">

        <form method="POST" action="" onsubmit="return chkAddElement();">
			<div class="row">
				<div class="form-group">
	        		<label class="col-sm-3">Nom de l'élément</label>
	        		<div class="col-sm-9">
	        			<input type="text" name="label_name" class="form-control input-sm">
	        		</div>
				</div>
				<br><br>
				<div class="form-group">
	        		<label class="col-sm-3">Ajouter</label>
	        		<div class="col-sm-6">
		        		
	        			<input type="radio" name="position" value="fin" checked="checked" class="radio-inline"> En dernière position
	        			<input type="radio" name="position" value="debut" class="radio-inline"> En première position
	        			<input type="radio" name="position" value="after" class="radio-inline"> Après
		        		
	        		</div>
	        		<div class="col-sm-3">
						<div id="selectLabel">
		        			<select name="list_label" class="form-control input-sm">
		        				{{$optionsLabel}}
		        			</select>
	        			</div>
	        		</div>
				</div>
				<br>
				<div class="form-group text-center">
					<input type="hidden" name="encounter_nr" value="{{$encounterNr}}">
					<input type="hidden" name="pid" value="{{$pid}}">
					<input type="hidden" name="mode" value="new">
					<input type="submit" name="add_colomn" value="Ajouter" class="btn btn-sm btn-primary">
				</div>

			</div>
			

        </form>
		

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
      </div>
    </div>

  </div>
</div>


{{** Configuration du rapport **}}
<div id="configurationRapport" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">CONFIGURATION DU RAPPORT</h4>
      </div>
      <div class="modal-body">

        <form method="POST" action="">
			<div class="row">
				<div class="col-sm-12">
					{{$formConfiguration}}
				</div>
			</div>
        </form>
		

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
      </div>
    </div>

  </div>
</div>
