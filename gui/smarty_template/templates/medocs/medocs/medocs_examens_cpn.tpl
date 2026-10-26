<div class="row">
	<div class="col-sm-12 text-center">
		<input type="button" name="nouveau" value="Nouveau Examen" class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal">
	</div>
	<form method="POST" action="">
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">EXAMENS COMPLEMENTAIRES</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-2">
								<label>Date</label>
							</div>
							<div class="col-sm-4">
								<input type="text" name="date_examen" id="date_examen"date_examen class="form-control input-xs" value="{{$date_examen}}">
							</div>
							<div class="col-sm-2">
								<label>Age de la grossesse</label>
							</div>
							<div class="col-sm-4">
								<input type="text" name="age" id="age" class="form-control input-xs" value="{{$age}}">
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-sm-2">
								<label>Echo</label>
								<input type="text" name="echo" id="echo" class="form-control input-xs" value="{{$echo}}">
							</div>
							<div class="col-sm-2">
								<label>Bip</label>
								<input type="text" name="bip" id="bip" class="form-control input-xs" value="{{$bip}}">
							</div>
							<div class="col-sm-2">
								<label>Dat</label>
								<input type="text" name="dat" id="dat" class="form-control input-xs" value="{{$dat}}">
							</div>
							<div class="col-sm-2">
								<label>Fémur</label>
								<input type="text" name="femur" id="femur" class="form-control input-xs" value="{{$femur}}">
							</div>
							<div class="col-sm-2">
								<label>Placen</label>
								<input type="text" name="placen" id="placen" class="form-control input-xs" value="{{$placen}}">
							</div>
							<div class="col-sm-2">
								<label>Liq. Am</label>
								<input type="text" name="liq" id="liq" class="form-control input-xs" value="{{$liq}}">
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-sm-4">
								<label>NFS GE</label>
								<input type="text" name="nfs" id="nfs" class="form-control input-xs" value="{{$nfs}}">
							</div>
							<div class="col-sm-4">
								<label>HB</label>
								<input type="text" name="hb" id="hb" class="form-control input-xs" value="{{$hb}}">
							</div>
							<div class="col-sm-4">
								<label>HTE</label>
								<input type="text" name="hte" id="hte" class="form-control input-xs" value="{{$hte}}">
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-sm-3">
								<label>UREE</label>
								<input type="text" name="uree" id="uree" class="form-control input-xs" value="{{$uree}}">
							</div>
							<div class="col-sm-3">
								<label>URICEMIE</label>
								<input type="text" name="uricemie" id="uricemie" class="form-control input-xs" value="{{$uricemie}}">
							</div>
							<div class="col-sm-3">
								<label>Glycémie</label>
								<input type="text" name="glycemie" id="glycemie" class="form-control input-xs" value="{{$glycemie}}">
							</div>
							<div class="col-sm-3">
								<label>Créatinine</label>
								<input type="text" name="creatinine" id="creatinine" class="form-control input-xs" value="{{$creatinine}}">
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-sm-3">
								<label>TP</label>
								<input type="text" name="tp" id="tp" class="form-control input-xs" value="{{$tp}}">
							</div>
							<div class="col-sm-3">
								<label>TCK</label>
								<input type="text" name="tck" id="tck" class="form-control input-xs" value="{{$tck}}">
							</div>
							<div class="col-sm-3">
								<label>Plaquettes</label>
								<input type="text" name="plaquette" id="plaquette" class="form-control input-xs" value="{{$plaquette}}">
							</div>
							<div class="col-sm-3">
								<label>Fibrinogène</label>
								<input type="text" name="fibrinogene" id="fibrinogene" class="form-control input-xs" value="{{$fibrinogene}}">
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-sm-3">
								<label>ECBU</label>
								<input type="text" name="ecbu" id="ecbu" class="form-control input-xs" value="{{$ecbu}}">
							</div>
							<div class="col-sm-3">
								<label>KOP</label>
								<input type="text" name="kop" id="kop" class="form-control input-xs" value="{{$kop}}">
							</div>
							<div class="col-sm-3">
								<label>Monitorage</label>
								<input type="text" name="monitorage" id="monitorage" class="form-control input-xs" value="{{$monitorage}}">
							</div>
							<div class="col-sm-3">
								<label>Ammioscopie</label>
								<input type="text" name="ammioscopie" id="ammioscopie" class="form-control input-xs" value="{{$ammioscopie}}">
							</div>
						</div>

					</div>
				</div>
				<div class="modal-footer">
				    <center>
				    	<input type="hidden" name="id_cpn" value="{{$idCpn}}" id="id_cpn">
						<input type="hidden" name="id" value="{{$idExamen}}" id="id">
					      <input type="submit" name="save" value="Enregistrer" id="save" class="btn btn-sm btn-primary">
				    </center>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>

{{if $show}}
<br>
<div class="row">
	<div class="col-sm-12">
{{**
	<form name="frmDeclarationGrossesse" method="POST" action="">

		<div class="panel panel-primary">
			<div class="panel-heading text-center">
				EXAMENS COMPLEMENTAIRES
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12">
						
						<table class="table table-bordered">
							<tr>
								<td><label><sub>Titres </sub>\ <sup>Dates</sup></label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
							</tr>
							<tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
							<tr>
								<td><label>Age de la grossesse</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
							<tr>
								<td><label>ECHO</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>Bip</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>Dat</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
							
							</tr>
							<tr>
								<td><label>Fémur</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>Placen.</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>Liq. Am</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
							<tr>
								<td><label>NFS GE</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>HB</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>HTE</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
							<tr>
								<td><label>UREE</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
							<tr>
								<td><label>URICEMIE</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
							<tr>
								<td><label>Glycémie</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
							<tr>
								<td><label>Créatinine</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
							<tr>
								<td><label>TP</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>TCK</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>Plaquettes</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>Fibrinogène</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr style="background-color: #0d5da3;"><td colspan="7"></td></tr>
							<tr>
								<td><label>ECBU</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>KOP</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>Monitorage Score de fisher</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>
							<tr>
								<td><label>Ammioscope</label></td>
								<td><input type="text" name="" class="form-control input-xs"></td>
								
							</tr>

						</table>
					</div>
				</div>
			</div>
			<div class="panel-footer text-center">
				<input type="button" name="modifier" value="Modifier" id="modifier" class="btn btn-sm btn-primary">
			</div>
		</div>
		<br>
		
		<br><br>

	</form>
	**}}

	{{$affichage}}

	</div>
</div>
{{/if}}