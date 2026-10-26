<div class="row">
	{{if $frmAction}}
	<form class="form-horizontal" name="saveEncounter" id="saveEncounter" action="{{$formAction}}" method="POST" onsubmit="return checkClassNr('{{$encounter_nr}}')">
	{{/if}}
		<div class="col-sm-12" id='MainMenu'>
			<div class="list-group panel" style="margin-bottom:25px">
				<a href="#constantePanel" class="list-group-item" style="background-color: #02327E; color:white; font-weight: bold" data-toggle="collapse" data-parent="#MainMenu">CONTANTES</a>
				<div class="collapse in" id="constantePanel">{{$listingConstante}}</div>

				<a href="#admissionPanel" class="list-group-item" style="background-color: #02327E; color:white; font-weight: bold" data-toggle="collapse" data-parent="#MainMenu">FORMULAIRE D'ADMISSION</a>
				<div class="collapse in" id="admissionPanel">{{$parametresSpecialites}}</div>
			</div>
		</div>

		{{if $frmAction}}
		<div class="row" style='padding-top:20px'>
			<div class="col-sm-12">
				<div class="form-group text-center">
					{{if $register}}
						<input type="submit" name="register" id="register" value="Enregistrer" class="btn btn-sm btn-primary">
					{{/if}}
				</div>
			</div>
		</div>
		{{/if}}

		{{if $modifyLink}}
		<div class="row" style='padding-top:20px'>
			<div class="col-sm-12">
				<div class="form-group text-center">
					{{if $modify}} 
						<a href="{{$modifyLink}}"><button class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Modifier</button></a>
						<a href="{{$printLink}}" target="_blank()"><button class="btn btn-success btn-sm"><span class="glyphicon glyphicon-print"></span> Imprimer</button></a>
						<input type="hidden" name="idEncounterDischarge" value="{{$idEncounterDischarge}}" id="idEncounterDischarge">
						{{if !$readonly}}
						<a href="{{$dischargeHref}}" class="btn btn-warning btn-sm"  onclick="return confirm('Etes vous sur?')" style="color:#fff;"><span class="glyphicon glyphicon-remove"></span> Cloturer</a>
						{{/if}}
						<!-- <button class="btn btn-warning btn-sm" id="idCloturer"><span class="glyphicon glyphicon-remove"></span> Cloturer</button> -->
					{{/if}}
				</div>
			</div>
		</div>
		{{/if}}
	{{if $frmAction}}
		<input type="hidden" name="create_id" id="create_id" value="{{$create_id}}">
	</form>
	{{/if}}
</div>