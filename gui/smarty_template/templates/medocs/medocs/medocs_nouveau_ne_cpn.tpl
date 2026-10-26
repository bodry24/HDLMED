<div class="row">
	<div class="col-sm-12">

	<form name="frmDeclarationGrossesse" method="POST" action="">

		<div class="panel panel-primary">
			<div class="panel-heading text-center">
				NOUVEAU NE
			</div>
			<div class="panel-body">
				<center>
					<span class="badge" style="background-color:#337AB7;"> EXAMEN A LA NAISSANCE</span>
				</center>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Date et heure de naissance</label>
					</div>
					<div class="col-sm-3">
						<input type="text" name="datenouveau_ne" id="dateNouveauNe" value="{{$date_nouveau_ne}}" class="form-control input-xs">
					</div>
					<div class="col-sm-3">
						<label>Sexe</label>
					</div>
					<div class="col-sm-3">
						
						<label class="radio-inline">
							<input type="radio" name="sexe" value="M" {{if $sexe=='M'}} checked="checked" {{/if}}>Masculin</label>
						<label class="radio-inline">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" name="sexe" value="F" {{if $sexe=='F'}} checked="checked" {{/if}}>Féminin
						</label>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Age gestationnel</label>
					</div>
					<div class="col-sm-3">
						<input type="text" name="age" id="age" value="{{$age}}"class="form-control input-xs">
					</div>
					<div class="col-sm-3">
						<label>Score de maturité</label>
					</div>
					<div class="col-sm-3">
						<input type="text" name="score_maturite" id="score_maturite" value="{{$score_maturite}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Né vivant</label>
					</div>
					<div class="col-sm-3">
						<input type="radio" name="situation_naissance" id="situation_naissance" value="1"{{if $situation_naissance==1}} checked="checked" {{/if}}>
					</div>
					<div class="col-sm-3">
						<label>Mort né</label>
					</div>
					<div class="col-sm-3">
						<input type="radio" name="situation_naissance" id="situation_naissance" value="0"
						{{if $situation_naissance==0}} checked="checked" {{/if}}>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Poids</label>
					</div>
					<div class="col-sm-3">
						<input type="text" name="poids" value="{{$poids}}" id="poids" class="form-control input-xs">
					</div>
					<div class="col-sm-3">
						<label>Taille</label>
					</div>
					<div class="col-sm-3">
						<input type="text" name="taille" value="{{$taille}}" id="taille" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Périmètre Cranien</label>
					</div>
					<div class="col-sm-3">
						<input type="text" name="perimetre_cranien" id="perimetre_cranien" value="{{$perimetre_cranien}}" class="form-control input-xs">
					</div>
					<div class="col-sm-3">
						<label>Périmètre Thoracique</label>
					</div>
					<div class="col-sm-3">
						<input type="text" name="perimetre_thoracique" id="perimetre_thoracique" value="{{$perimetre_thoracique}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Score d'Apgar</label>
					</div>
					<div class="col-sm-3">

						<label>1 mn</label> <input type="radio" name="score_apgar" value="1"
						{{if $score_apgar==1}} checked="checked" {{/if}}>
					</div>
					<div class="col-sm-3">
						<label>5 mn</label> <input type="radio" name="score_apgar" value="5"
						{{if $score_apgar==5}} checked="checked" {{/if}}>
					</div>
					<div class="col-sm-3">
						<label>10 mn</label><input type="radio" name="score_apgar" value="10"
						{{if $score_apgar==10}} checked="checked" {{/if}}>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-12">
						<table class="table table-bordered">
							
							<tr>
								<td width="25%" rowspan="2"><label>Réanimation</label></td>
								<td width="75%" colspan="3"><label>Non</label> <input type="radio" name="reanimation" value="0" {{if $reanimation==0}} checked="checked" {{/if}}></td>
							</tr>
							<tr>
								
								<td width="10%"><label>Oui</label> <input type="radio" name="reanimation" {{if $reanimation==1}} checked="checked" {{/if}} value="1"></td>
								<td width="25%"><label>Modalités</label></td>
								<td width="50%"><input type="text" name="modalite" id="modalite" value="{{$modalite}}"class="form-control input-xs"></td>
							</tr>
							
						</table>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Malformation évidente</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="malformation" value="{{$malformation}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Infection</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="infection" value="{{$infection}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Autre</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="autre" value="{{$autre}}" id="autre" class="form-control input-xs">
					</div>
				</div>
				<div class="row">
					<br>
					<div class="col-sm-12 text-center">
						<span class="badge" style="background-color:#337AB7;"> EXAMEN A LA SORTIE</span>
					</div>
					<br>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Date de sortie</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="date_examen" value="{{$date_examen}}" id="date_examen" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Poids de sortie</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="poids_examen" value="{{$poids_examen}}" id="poids_examen" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Etat à la sortie</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="etat_examen" id="etat_examen" value="{{$etat_examen}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Alimentation</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="alimentation_examen" id="alimentation_examen" value="{{$alimentation_examen}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Consignes particulières</label>
					</div>
					<div class="col-sm-9">
						<input type="text" name="consigne_examen" id="consigne_examen" value="{{$consigne_examen}}" class="form-control input-xs">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-3">
						<label>Groupe</label>
					</div>
					<div class="col-sm-3">
						<input type="text" name="groupe" id="groupe" value="{{$groupe}}" class="form-control input-xs">
					</div>
					<div class="col-sm-3">
						<label>Rhesus</label>
					</div>
					<div class="col-sm-3">
						<input type="text" name="rhesus" id="rhesus" value="{{$rhesus}}" class="form-control input-xs">
					</div>
				</div>
				


			</div>
			<div class="panel-footer text-center">
				<input type="hidden" id="id_cpn" name="id_cpn" value="{{$idCpn}}">
				<input type="hidden" id="id" name="id" value="{{$idNouveauNe}}">
				<input type="submit" name="save" value="Enregistrer" id="save" class="btn btn-sm btn-primary">
			</div>
		</div>
		<br>
		
		<br><br>

	</form>

	</div>
</div>
