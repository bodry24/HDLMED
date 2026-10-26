<div class="row">
	<div class="col-sm-12">

	<form name="frmHospitalisationCpn" method="POST" action="">

		<div class="form-group">
			<textarea name="description" id="myTiny" rows="20" cols="100%" height="600px" class="form-control">{{$description}}</textarea>
		</div>

		<center>
			<input type="hidden" name="id_cpn" value="{{$idCpn}}" id="id_cpn">
			<input type="hidden" name="id" value="{{$idHospitalisation}}" id="id">
			<input type="submit" name="save" value="Enregistrer" id="save" class="btn btn-sm btn-primary">
		</center>
	
		<br><br>

	</form>

	</div>
</div>

{{if $show}}
	{{$affichage}}
{{/if}}