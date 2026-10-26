<div class="row">
	<div class="col-sm-12 text-center">
		<input type="button" name="nouveau" value="Nouvelle Surveillance" class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal">
	</div>
	<form method="POST" action="">
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">SURVEILLANCE DE LA GROSSESSE</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-2">
								<label>Date</label>
								<input type="text" name="date_surveillance" id="date_surveillance" class="form-control input-xs" value="{{$date_surveillance}}">
							</div>
							
							<div class="col-sm-2">
								<label>Age de la grossesse</label>
								<input type="text" name="age" id="age" class="form-control input-xs" value="{{$age}}">
							</div>
							<div class="col-sm-2">
								<label>TA</label>
								<input type="text" name="ta" id="ta" class="form-control input-xs" value="{{$ta}}">
							</div>
							<div class="col-sm-2">
								<label>Poids Prise</label>
								<input type="text" name="poids" id="poids" class="form-control input-xs" value="{{$poids}}">
							</div>
							<div class="col-sm-2">
								<label>Urine AS</label>
								<input type="text" name="urine" id="urine" class="form-control input-xs" value="{{$urine}}">
							</div>
							<div class="col-sm-2">
								<label>HU</label>
								<input type="text" name="hu" id="hu" class="form-control input-xs" value="{{$hu}}">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<label class="text-center">Examen</label>
								<textarea name="examen" rows="3" class="form-control input-xs">
									{{$examen}}
								</textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<label class="text-center">Traitement</label>
								<textarea name="traitement" rows="3" class="form-control input-xs">
									{{$traitement}}
								</textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<label class="text-center">Observations</label>
								<textarea name="observation" rows="3" class="form-control input-xs">
									{{$observation}}
								</textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<label>Bilan demandé</label>
								<textarea name="bilan" rows="3" class="form-control input-xs">
									{{$bilan}}
								</textarea>
							</div>
						</div>

					</div>
				</div>
				<div class="modal-footer">
				    <center>
				    	<input type="hidden" name="id_cpn" value="{{$idCpn}}" id="id_cpn">
						<input type="hidden" name="id" value="{{$idSurveillance}}" id="id">
					      <input type="submit" name="save" value="Enregistrer" id="save" class="btn btn-sm btn-primary">
				    </center>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>


{{if $show}}
<div class="row">
	<div class="col-sm-12">
		<br><br>
		{{$affichage}}
	</div>
</div>
{{/if}}
