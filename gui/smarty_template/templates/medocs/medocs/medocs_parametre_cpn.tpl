<div class="row">
	<div class="col-sm-12">

	<form name="frmDeclarationGrossesse" method="POST" action="">

		<div class="panel panel-primary">
			<div class="panel-heading text-center">
				<span class="badge">1</span> GROUPAGE
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-2">
						<label>Groupe</label>
					</div>
					<div class="col-sm-4">
						<input type="text" name="groupe" id="groupe" class="form-control input-xs" value="{{$groupe}}">
					</div>
					<div class="col-sm-2">
						<label>Rhésus</label>
					</div>
					<div class="col-sm-4">
						<input type="text" name="rhesus" id="rhesus" class="form-control input-xs" value="{{$rhesus}}">
					</div>
				</div>
			</div>
			
		</div>

		<div class="panel panel-primary">
			<div class="panel-heading text-center">
				<span class="badge">2</span> AGGLUTININES IRREGULIERES ANT. D.
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12">
						<table class="table table-bordered">

							<tr>
								<td><label>Dates</label></td>
								<td><input type="text" id="date_agglutinine" name="date_agglutinine" class="form-control input-xs"></td>
								<td><label>Titres</label></td>
								<td><input type="text" name="titre_agglutinine" class="form-control input-xs"></td>
							</tr>
							
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="input-group btn">
					<span class="input-group-addon label-primary" style="color: #fff;">
						3) ELECTROPHORESE DE L'HEMOGBLOBINE
					</span>
					<input type="text" name="electrophorese" value="{{$electrophorese}}" class="form-control input-sm">
				</div>
			</div>
		</div>
		<br>
		<div class="panel panel-primary">
			<div class="panel-heading text-center">
				<span class="badge">4</span> IMMUNISATIONS ACQUISES
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12">
						<table class="table table-bordered">
							<tr>
								<!-- <td><label><sub>Titres </sub>\ <sup>Dates</sup></label></td> -->
								<td><label>Dates</label></td>
								<td><label>Toxoplasmose</label></td>
								<td><label>Rubéole</label></td>
								<td><label>syphilis</label></td>
								<td><label>VIH</label></td>
								<td><label>Autre</label></td>
								
							</tr>
							<tr>
								
								<td><input type="text" id="date_immunisation" name="date_immunisation" class="form-control input-xs"></td>
								<td><input type="text" name="toxoplasme" class="form-control input-xs"></td>
								<td><input type="text" name="rubeole" class="form-control input-xs"></td>
								<td><input type="text" name="syphilis" class="form-control input-xs"></td>
								<td><input type="text" name="vih" class="form-control input-xs"></td>
								<td><input type="text" name="autre" class="form-control input-xs"></td>
							</tr>
							
							
						</table>

						<center><span class="badge" style="background-color:#337AB7;"> VACCINATIONS</span></center>
						<br>
						<table class="table table-bordered">
							<tr>
								<td><label>Dates</label></td>
								<td><label>Antitétanique</label></td>
								<td><label>BCG</label></td>
								<td><label>Hepatite</label></td>	
							</tr>
							<tr>
								<td><input type="text" id="date_vaccination" name="date_vaccination" class="form-control input-xs"></td>
								<td><input type="text" name="antitetanique" class="form-control input-xs"></td>
								<td><input type="text" name="bcg" class="form-control input-xs"></td>
								<td><input type="text" name="hepatite" class="form-control input-xs"></td>
							</tr>
							
							
						</table>
						
					</div>
				</div>
			</div>
		</div>
		
		<center>
			<input type="hidden" name="id_cpn" value="{{$idCpn}}" id="id_cpn">
			<input type="hidden" name="id" value="{{$idParametre}}" id="id">
			<input type="submit" name="save" value="Enregistrer" id="save" class="btn btn-sm btn-primary">
		</center>
	
		<br><br>

	</form>

	</div>
</div>

{{if $show}}
	{{$affichage}}
{{/if}}