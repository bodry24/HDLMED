<div class="row">
	<div class="col-sm-12">

	<form name="frmVisiteGrossesse" method="POST" action="">

		<div class="panel panel-primary">
			<div class="panel-heading text-center">
				VISITE POST NATALE
			</div>
			<div class="panel-body">
				
				<div class="row">
					<div class="col-sm-3">
						<label>Date</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="date_visite" id="date_visite" value="{{$date_visite}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Temps depuis l'accouchement</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="temps_accouchement" id="temps_accouchement" value="{{$temps_accouchement}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-12">
						<table class="table table-bordered">
							
							<tr>
								<td width="25%" rowspan="2"><label>Retour de couches</label></td>
								<td width="75%" colspan="5">
									<label class="radio-inline">
										<input type="radio" name="retour_couche" value="0" {{if $retour_couche==0}} checked="checked" {{/if}}>Non
									</label> 
								</td>
							</tr>
							<tr>
								<td width="10%">
									<label class="radio-inline">
										<input type="radio" name="retour_couche" value="1" {{if $retour_couche==1}} checked="checked" {{/if}}>Oui
									</label>
								</td>
								<td width="5%"><label>Date</label></td>
								<td width="15%">
									<input type="text" name="date_couche" value="{{$date_couche}}" id="date_couche" class="form-control input-xs">
								</td>
								<td width="10%"><label>Modalité</label></td>
								<td width="40%">
									<input type="text" name="modalite_couche" value="{{$modalite_couche}}" class="form-control input-xs">
								</td>
							</tr>
							
						</table>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Allaitement</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="allaitement" value="{{$allaitement}}" id="allaitement" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Examen</label>
					</div>
					<div class="col-sm-9">
						<textarea name="examen" class="form-control input-xs" rows="5">
							{{$examen}}
						</textarea>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>* Particularités</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="particularite" value="{{$particularite}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>* Contraception</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="contraception" value="{{$contraception}}" class="form-control input-xs">
					</div>
				</div>
				
			</div>
			<div class="panel-footer text-center">
				<input type="hidden" name="id_cpn" value="{{$idCpn}}" class="form-control input-xs">
				<input type="hidden" name="id" value="{{$idVisite}}" class="form-control input-xs">
				<input type="submit" name="save" value="Enregistrer" id="save" class="btn btn-sm btn-primary">
			</div>
		</div>
		<br>
	
	</form>

	</div>
</div>
