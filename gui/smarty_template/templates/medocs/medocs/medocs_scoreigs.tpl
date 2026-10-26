
<div class="row">
	<div class="col-sm-12">
		{{$content}}
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="panel-group">
		    <div class="panel panel-primary">
			    <div class="panel-heading text-center">
					<h4 class="panel-title">
						<a data-toggle="collapse" href="#collapse1" style="color:white;font-weight: bold;text-decoration: none;">LISTE DES SCORES IGS II</a>
					</h4>
			    </div>
			    <div id="collapse1" class="panel-collapse collapse">
			        <div class="panel-body">

				      	<table class="table table-bordered table-striped table-striped-hover">
				      		<thead>
				      			<tr>
				      				<th class="text-center">N°</th>
				      				<th class="text-center">Date</th>
				      				<th class="text-center">Age</th>
				      				<th class="text-center">Fréquence</th>
				      				<th class="text-center">...</th>
				      				<th class="text-center">Actions</th>
				      			</tr>
				      		</thead>
				      		<tbody>
				      			{{$listingScore}}
				      		</tbody>
				      	</table>
			      	
			        </div>
			        <!-- <div class="panel-footer">Panel Footer</div> -->
				</div>
			</div>
		</div>
	</div>
</div> 

<form action="" method="POST">

<div class="row">
	<div class="col-sm-12">
		<table class="table table-bordered table-striped table-hover table-responsive">
			<tbody>
				<tr>
					<td>
						<b>Age</b>
						<div class="radio">
							<label><input type="radio" value="0" name="ageVal" {{$checkedAge1}} class="age">< 40</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="7" name="ageVal" {{$checkedAge2}} class="age"> 40 à 59</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="12" name="ageVal" {{$checkedAge3}} class="age">60 à 69</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="15" name="ageVal" {{$checkedAge4}} class="age">70 à 74</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="16" name="ageVal" {{$checkedAge5}} class="age"> 75 à 79</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" value="18" name="ageVal" {{$checkedAge6}} class="age"> > ou = à 80
							</label>
						</div>
						<div class="form-group">
							<input type="text" name="age" value="{{$age}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Fréquence cardiaque (b/min)</b>
						<div class="radio">
							<label><input type="radio" value="11" name="frequenceVal" class="frequence">< 40</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="2" name="frequenceVal" {{$checkedAge2}} class="frequence"> 40 - 69</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="0" name="frequenceVal" {{$checkedAge3}} class="frequence">70 - 119</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="4" name="frequenceVal" {{$checkedAge4}} class="frequence">120 - 159</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="7" name="frequenceVal" {{$checkedAge5}} class="frequence"> > ou = à 160</label>
						</div>
						
						<div class="form-group">
							<input type="text" name="frequence" value="{{$frequence}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Pression artérielle systolique</b>
						<div class="radio">
							<label><input type="radio" value="13" name="pressionVal" class="pression">< 70 mmHg</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="5" name="pressionVal" class="pression"> 70 - 99 mmHg</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="0" name="pressionVal" class="pression"> 100 - 199 mmHg</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="2" name="pressionVal" class="pression"> > ou = à 200 mmHg</label>
						</div>
						
						<div class="form-group">
							<input type="text" name="pression" value="{{$pression}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Température centrale</b>
						<div class="radio">
							<label><input type="radio" value="0" name="temperatureVal" class="temperature">< 39 °C</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="3" name="temperatureVal" class="temperature"> 40 à 59</label>
						</div>
						
						<div class="form-group">
							<input type="text" name="temperature" value="{{$temperature}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Rapport PaO<sub>2</sub>/FIO<sub>2</sub> (mmHg)</b>
						<div class="radio">
							<label><input type="radio" value="11" name="rapportVal" class="rapport">< 40</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="9" name="rapportVal" class="rapport"> 40 à 59</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="6" name="rapportVal" class="rapport"> > ou = à 200</label>
						</div>
						
						
						<div class="form-group">
							<input type="text" name="rapport" value="{{$rapport}}" class="form-control input-sm champ">
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<b>Diurèse (L/24 h)</b>
						<div class="radio">
							<label><input type="radio" value="11" name="diureseVal" class="diurese">< 0,5 L</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="4" name="diureseVal" class="diurese"> 0,5 à 0,999 L</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="0" name="diureseVal" class="diurese">> ou = à 1 L</label>
						</div>
						
						<div class="form-group">
							<input type="text" name="diurese" value="{{$diurese}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Urée sanguine</b>
						<div class="radio">
							<label><input type="radio" value="0" name="ureeVal" class="uree">< 10 mmol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="6" name="ureeVal" class="uree"> 10 à 29,9 mmol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="10" name="ureeVal" class="uree">> ou = à 30 mmol/l</label>
						</div>
						ou
						<div class="radio">
							<label><input type="radio" value="0" name="ureeVal" class="uree">< à 0,6 g/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="6" name="ureeVal" class="uree"> 0,6 à 1,79 g/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="10" name="ureeVal" class="uree">> ou = à 1,8 g/l</label>
						</div>
						
						<div class="form-group">
							<input type="text" name="uree" value="{{$uree}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Leucocytes</b>
						<div class="radio">
							<label><input type="radio" value="12" name="leucocyteVal" class="leucocyte">< 1 000 / mm<sup>3</sup></label>
						</div>
						<div class="radio">
							<label><input type="radio" value="0" name="leucocyteVal" class="leucocyte"> 1 000 à 19 000 / mm<sup>3</sup></label>
						</div>
						<div class="radio">
							<label><input type="radio" value="3" name="leucocyteVal" class="leucocyte"> > ou = à 20 00 / mm<sup>3</sup></label>
						</div>
						
						<div class="form-group">
							<input type="text" name="leucocyte" value="{{$leucocyte}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Kaliémie</b>
						<div class="radio">
							<label><input type="radio" value="3" name="kaliemieVal" class="kaliemie">< 3 mmol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="0" name="kaliemieVal" class="kaliemie"> 3 à 4,9 mmol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="3" name="kaliemieVal" class="kaliemie"> > ou = à 5 mmol/l</label>
						</div>
						
						<div class="form-group">
							<input type="text" name="kaliemie" value="{{$kaliemie}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Natrémie</b>
						<div class="radio">
							<label><input type="radio" value="5" name="natremieVal" class="natremie">< 125 mmol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="0" name="natremieVal" class="natremie"> 125 à 144 mmol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="1" name="natremieVal" class="natremie"> > ou = à 145 mmol/l</label>
						</div>
						
						
						<div class="form-group">
							<input type="text" name="natremie" value="{{$natremie}}" class="form-control input-sm champ">
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<b>HCO3<sup>-</sup></b>
						<div class="radio">
							<label><input type="radio" value="6" name="hco3Val" class="hco3">< 15 mmol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="3" name="hco3Val" class="hco3"> 15 à 19mmol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="0" name="hco3Val" class="hco3"> > ou = à 145 mmol/l</label>
						</div>
						
						
						<div class="form-group">
							<input type="text" name="hco3" value="{{$hco3}}" class="form-control input-sm champ">
						</div>
					</td>
					
					<td>
						<b>Bilirubine (si ictère)</b>
						<div class="radio">
							<label><input type="radio" value="0" name="bilirubineVal" class="bilirubine">< 68,4 micromol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="4" name="bilirubineVal" class="bilirubine"> 68,4 à 102,5 micromol/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="9" name="bilirubineVal" class="bilirubine">> 102,6 micromol/l</label>
						</div>
						ou
						<div class="radio">
							<label><input type="radio" value="0" name="bilirubineVal" class="bilirubine">< à 40 mg/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="4" name="bilirubineVal" class="bilirubine"> 40 à 59,9 mg/l</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="9" name="bilirubineVal" class="bilirubine">> ou = à 60 mg/l</label>
						</div>
						
						<div class="form-group">
							<input type="text" name="bilirubine" value="{{$bilirubine}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Glasgow (points)</b>
						<div class="radio">
							<label><input type="radio" value="26" name="glasgowVal" class="glasgow">< 6</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="13" name="glasgowVal" class="glasgow"> 6 - 8</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="7" name="glasgowVal" class="glasgow"> 9 - 10</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="5" name="glasgowVal" class="glasgow"> 11 - 13</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="0" name="glasgowVal" class="glasgow"> 14 - 15</label>
						</div>
						
						<div class="form-group">
							<input type="text" name="glasgow" value="{{$glasgow}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Maladies chroniques</b>
						<div class="radio">
							<label><input type="radio" value="0" name="maladieVal" class="maladie"> Aucune</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="17" name="maladieVal" class="maladie"> SIDA</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="10" name="maladieVal" class="maladie"> Hématopathies malignes</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="9" name="maladieVal" class="maladie"> Cancers métastasés</label>
						</div>
						
						
						<div class="form-group">
							<input type="text" name="maladie" value="{{$maladie}}" class="form-control input-sm champ">
						</div>
					</td>
					<td>
						<b>Type d'admission</b>
						<div class="radio">
							<label><input type="radio" value="0" name="type_admissionVal" class="type_admission"> Aucune</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="6" name="type_admissionVal" class="type_admission"> Médecine</label>
						</div>
						<div class="radio">
							<label><input type="radio" value="8" name="type_admissionVal" class="type_admission"> Chirurgie urgente</label>
						</div>
						
						<div class="form-group">
							<input type="text" name="type_admission" value="{{$type_admission}}" class="form-control input-sm champ"  onchange="alert(this.value)">
						</div>
					</td>

				</tr>
			
			</tbody>
		</table>
		<table class="table table-bordered">
			<tr>
				<td class="text-center">
					<label>IGS II</label>
					<div class="form-group">
						<input type="text" name="igs2" id="igs2" value="{{$igs2}}" class="form-control input-sm background">
					</div>
				</td>
				<td class="text-center">
					<label>Mortalité Prédite (%)</label>
					<div class="form-group">
						<input type="text" name="mortalite" id="mortalite" value="{{$mortalite}}" class="form-control input-sm background">
					</div>
				</td>
				<td class="text-center">
					<label>Logit</label>
					<div class="form-group">
						<input type="text" name="logit" id="logit" value="{{$logit}}" class="form-control input-sm background">
					</div>
				</td>
			</tr>
		</table>
		<table class="table table-bordered">
			<tr>
				<td><label>N° Admssion</label></td>
				<td>
					<div class="form-group">
						<input type="text" name="encounter_nr" value="{{$encounterNr}}" class="form-control input-sm" readonly="readonly">
					</div>
				</td>
				<td><label>Date</label></td>
				<td>
					<div class="form-group">
						<input type="text" name="date" value="{{$aujourdhui}}" class="form-control input-sm" id="date">
					</div>
				</td>
			</tr>
		</table>
		
		<center>
			<input type="hidden" name="id" value="{{$idVal}}">
			<input type="submit" name="save" class="btn btn-sm btn-primary" value="Enrégistrer">
		</center>

	</div>
</div>
<br>

</form>