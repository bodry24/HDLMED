<div class="row">
	<div class="col-sm-12">
		<div class="container-fluid">
			<!-- <table class="table table-responsive table-bordered table-striped"> -->
		<!-- <table class="table table-responsive table-bordered tblInfosPatient" style="font-size:10px;line-height: 10px;"> -->
	<div class="well" style="padding: 0px;padding-top: 15px;">
		<table class="" style="font-size:10px;line-height: 5px;width: 100%;border-color: gray; padding-left: 5px;" border="1px">
			<tr>
				<td width="20%" style="" class="" >N° admission</td>
				<td class="" ><label>{{$AdmissionNr}}</label></td>

				<td class="" width="20%">Nom et Prénoms | Sexe</td>
				<td class="" ><label>{{$PatientName}} | {{$sexe}}</label></td>

				<td class="" rowspan="4" style="border-bottom:1px solid #ccc; text-align:center">
					
					<center>
						<img src="{{$srcImage}}" style="width: 50px;height: 60px; vertical-align: middle;" class="img-responsive img-circle">
					</center>
					
				</td>
			</tr>
			<tr>
				<td>PID</td>
				<td><label>{{$pid}}</label></td>
				<td>Nom d'épouse</td>
				<td><label>{{if $nameSpouse}} {{$nameSpouse}} {{/if}}</label></td>
			</tr>
			<tr>
				<td>Date d'admission</td>
				<td><label>{{$AdmissionDate}}</label></td>
				<td>Date de naissance</td>
				<td><label>{{$dateNaissance}}</label></td>
			</tr>
			{{if $AssuranceName}}
			<tr>
				<td colspan="4" class="text-center" style="border:1px solid gray">
					Assurance : <label>{{$AssuranceName}}</label> ::

					Taux : <label>{{$taux}}</label> ::

					Date expiration: <label>{{$dateExpiration}}</label>
				</td>
			</tr>
			{{/if}}
		</table>
		<br>
		</div>	

	</div>

	</div>
</div>