<div class="row">
	<div class="col-sm-6">
		<form class="form-horizontal" action="" method="POST">
			<div class="panel panel-default">
				<div class="panel-heading"><h4 class="panel-title">ANTECEDENTS</h4></div>
				<div class="panel-body" style="height: 500px; overflow-y: scroll">
					<div class="well well-sm">ANTECEDENT FAMILIALE</div>
					<div class="form-group">
						<label class="col-sm-3">Père :</label>
						<div class="col-sm-9"><textarea name="pere" rows="1" id="pere" class="form-control input-xs">{{$pere}}</textarea></div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Mère :</label>
						<div class="col-sm-9"><textarea name="mere" rows="1" id="mere" class="form-control input-xs">{{$mere}}</textarea></div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Fraterie :</label>
						<div class="col-sm-9"><textarea name="fraterie" rows="1" id="fraterie" class="form-control input-xs">{{$fraterie}}</textarea></div>
					</div>
					<div class="well well-sm">ANTECEDENT PERSONNEL</div>
					<div class="form-group">
						<label class="col-sm-3">Médicaux :</label>
						<div class="col-sm-9"><textarea name="medicaux" rows="1" id="medicaux" class="form-control input-xs">{{$medicaux}}</textarea></div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Histoire/ Evolution de la grossesse :</label>
						<div class="col-sm-9"><textarea name="histoire" rows="1" id="histoire" class="form-control input-xs">{{$histoire}}</textarea></div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Chirurgicaux:</label>
						<div class="col-sm-9"><textarea name="chirurgicaux" rows="1" id="chirurgicaux" class="form-control input-xs">{{$chirurgicaux}}</textarea></div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Gynécologie :</label>
						<div class="col-sm-9"><textarea name="gyneco" rows="1" id="gyneco" class="form-control input-xs">{{$gyneco}}</textarea></div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Obstétrique :</label>
						<div class="col-sm-9"><textarea name="obstetrique" rows="1" id="obstetrique" class="form-control input-xs">{{$obstetrique}}</textarea></div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Age de sevrage :</label>
						<div class="col-sm-9 input-group"><input type="number" min=1 class="form-control input-sm" size="3" name="ageSevrage" value="{{$ageSevrage}}" id="ageSevrage">
						<div class="input-group-addon">Mois</div></div>
					</div>
					<!-- <div class="form-group">
						<label class="col-sm-3">Vaccin à jour :</label>
						<div class="col-sm-9">
							<label for="ouiV"> Oui <input type="radio" value="O" id="ouiV" name="vaccin"></label>
							<label for="ouiN"> Non <input type="radio" value="N" id="ouiN" name="vaccin"></label>
						</div>
					</div> -->
					<div class="form-group">
						<label class="col-sm-3">Habitude Alimentaire :</label>
						<div class="col-sm-9">
							<label for="alcool"> Alcool <input type="checkbox" id="alcool" value="alcool" name="habitude[]"></label>
							<label for="tabac"> Tabac <input type="checkbox" id="tabac" value="tabac" name="habitude[]"></label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3" style="color:red">Allergies :</label>
						<div class="col-sm-9"><textarea rows="1" class="form-control input-xs" name="allergies" id="allergies">{{$allergies}}</textarea></div>
					</div>
					<div class="form-group">
						<label class="col-sm-3">Autres :</label>
						<div class="col-sm-9">
							{{$autresAntecedentPersonnel}}
						</div>
					</div>
				</div>
				<div class="panel-footer"><input type="submit" name="save" id="save" value="Enregistrer" class="btn btn-sm btn-primary"></div>
			</form>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">HISTORIQUE DES ADMISSIONS</h4>
			</div>
			<div class="panel-body" id="" style="height: 550px; overflow-y: scroll">
				<div class="list-group panel" style="margin-bottom:25px">
				
					{{$printGlobal}}
					{{$historiques}}
					
				</div>
			</div>
		</div>
	</div>
</div>



<div id="myModal" class="modal" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<center><h4>AFFICHAGE DE TOUTES LES CONSULTATIONS</h4></center>
			</div>
			<div class="modal-content">
				{{$modalContent}}
			</div>
		</div>
	</div>
</div>
