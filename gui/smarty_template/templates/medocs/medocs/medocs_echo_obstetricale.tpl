{{if $listeEchographie}}

	<div class="row">
		<div class="col-sm-12">
			
				<div class="panel panel-primary">
					<div class="panel-heading">
						LISTE DES ECHOGRAPHIES OBSTETRICALES
					</div>
					<div class="panel-body">
						<table class="table table-bordered table-striped-hover" id="exemple">
							{{**<thead style="background-color: #00009C; color:white;">**}}
							<thead>
								<th class="text-center" width="5%">N°</th>
								<th class="text-center" width="15%">Date examen</th>
								<th class="text-center" width="25%">Prescripteur</th>
								<th class="text-center" width="25%">Réalisateur</th>
								<th class="text-center" width="10%">Actions</th>
							</thead>
							<tbody>
								{{$listing}}
							</tbody>
						</table>
					</div>
				</div>

		</div>
	</div>

{{else}}

<form method="POST" action="{{$urlAction}}" onsubmit="return controlForm();" enctype="multipart/form-data">
<center>
	<a href="{{$urlListe}}" class="btn-sm btn-success" style="color:white;text-decoration: none;">Liste des echographies obstétricales réalisées</a>
</center>
<br>
<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<label>PID</label>
			<input type="text" name="pid" value="{{$pid}}" id="pid" class="form-control input-sm" readonly="readonly">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Nom </label>
			<input type="text" name="name_last" value="{{$name_last}}" id="name_last" class="form-control input-sm" readonly="readonly">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Prénoms</label>
			<input type="text" name="name_first" value="{{$name_first}}" id="name_first" class="form-control input-sm" readonly="readonly">
		</div>
	</div>
	
	<div class="col-sm-3">
		<div class="form-group">
			<label>Age</label>
			<input type="text" name="age" value="{{$age}}" id="age" class="form-control input-sm" readonly="readonly">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<label>Date de l'examen</label>
			<input type="text" name="date_examen" value="{{$date_examen}}" id="date_examen" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Echographe</label>
			<input type="text" name="machine" value="{{$machine}}" id="machine" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Date de mise en service</label>
			<input type="text" name="date_service_machine" value="{{$date_service_machine}}" id="date_service_machine" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Voie examen</label>
			<input type="text" name="voie_examen" value="{{$voie_examen}}" class="form-control input-sm">
		</div>
	</div>
	
</div>
<!-- <hr> -->
<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<label>Condition de l'examen</label>
			<input type="text" name="condition_examen" value="{{$condition_examen}}" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Groupe sanguin</label>
			<select name="groupe_sanguin" id="groupe_sanguin" class="form-control input-sm">
				{{$optionsGroupeSanguin}}
			</select>
		</div>
	</div>
	
	<div class="col-sm-3">
		<div class="form-group">
			<label>Taille</label>
			<input type="text" name="taille" value="{{$taille}}" class="form-control input-sm" placeholder="en centimetre (cm)">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Poids</label>
			<input type="text" name="taille" value="{{$taille}}" class="form-control input-sm" placeholder="en kg">
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<label>IMC</label>
			<input type="text" name="imc" value="{{$imc}}" id="imc" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Prescripteur</label>
			<input type="text" name="prescripteur" value="{{$prescripteur}}" id="prescripteur" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Indication</label>
			<input type="text" name="indication" value="{{$indication}}"  id="indication" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>DDR</label>
			<input type="text" name="ddr" value="{{$ddr}}" id="ddr" class="form-control input-sm">
		</div>
	</div>
</div>

<div class="row">

	<div class="col-sm-2">
		<div class="form-group">
			<label>DDG théorique</label>
			<input type="text" name="ddg_theorique" value="{{$ddg_theorique}}" id="ddg_theorique" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>DDG echographique</label>
			<input type="text" name="ddg_echographique" value="{{$ddg_echographique}}" id="ddg_echographique" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Terme théorique</label>
			<input type="text" name="terme_theorique" value="{{$terme_theorique}}" id="terme_theorique" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Terme echographique</label>
			<input type="text" name="terme_echographique" value="{{$terme_echographique}}" id="terme_echographique" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Gestité</label>
			<input type="text" name="gestite" value="{{$gestite}}"  id="gestite" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Parité</label>
			<input type="text" name="parite" value="{{$parite}}" id="parite" class="form-control input-sm">
		</div>
	</div>
	
</div>


<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<label>Clarté nucale</label>
			<input type="text" name="clarte_nucale" value="{{$clarte_nucale}}" id="clarte_nucale" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Longueur cranio-caudale</label>
			<input type="text" name="cranio_caudale" value="{{$cranio_caudale}}" id="cranio_caudale" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Risque combiné du 1er trimestre.</label>
			<input type="text" name="risque_combine" value="{{$risque_combine}}" id="risque_combine" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>PAPP-A</label>
			<input type="text" name="pappa" value="{{$pappa}}"  id="pappa" class="form-control input-sm" placeholder="en MoM">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>FreeBeta</label>
			<input type="text" name="freebeta" value="{{$freebeta}}"  id="freebeta" class="form-control input-sm" placeholder="en MoM">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Amniocentèse</label>
			<input type="text" name="amniocentese" value="{{$amniocentese}}"  id="amniocentese" class="form-control input-sm">
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-3">
		<div class="form-group">
			<label>Présentation</label>
			<input type="text" name="presentation" value="{{$presentation}}" id="presentation" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Activité cardiaque</label>
			<input type="text" name="activite_cardiaque" value="{{$activite_cardiaque}}" id="activite_cardiaque" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Fréquence cardiaque</label>
			<input type="text" name="frequence_cardiaque" value="{{$frequence_cardiaque}}"  id="frequence_cardiaque" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Mobilité</label>
			<input type="text" name="mobilite" value="{{$mobilite}}"  id="mobilite" class="form-control input-sm">
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<label>Diamètre BIPariétal</label>
			<input type="text" name="diametre_bip" value="{{$diametre_bip}}" id="diametre_bip" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Périmètre Cranien</label>
			<input type="text" name="perimetre_cranien" value="{{$perimetre_cranien}}" id="perimetre_cranien" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Périmètre abdominal</label>
			<input type="text" name="frequence_cardiaque" value="{{$frequence_cardiaque}}"  id="frequence_cardiaque" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<label>Fémur</label>
			<input type="text" name="femur" value="{{$femur}}"  id="femur" class="form-control input-sm">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label>Poids estimé (Hadlock 5) +/- 15%</label>
			<input type="text" name="femur" value="{{$femur}}"  id="femur" class="form-control input-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Crâne</label>
			<textarea name="crane" rows="3"  id="crane" class="form-control input-sm">{{$crane}}</textarea> 
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Structures Cérébrales</label>
			<textarea name="structures_cerebrales" rows="3"  id="structures_cerebrales" class="form-control input-sm">{{$structures_cerebrales}}</textarea>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Face</label>
			<textarea name="face" rows="3"  id="face" class="form-control input-sm">{{$face}}</textarea> 
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Thorax</label>
			<textarea name="thorax" rows="3"  id="thorax" class="form-control input-sm">{{$thorax}}</textarea>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Coeur</label>
			<textarea name="coeur" rows="3"  id="coeur" class="form-control input-sm">{{$coeur}}</textarea> 
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Paroi Abdominal</label>
			<textarea name="paroi_abdominale" rows="3"  id="paroi_abdominale" class="form-control input-sm">{{$paroi_abdominale}}</textarea>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Appareil digestif</label>
			<textarea name="appareil_digestif" rows="3"  id="appareil_digestif" class="form-control input-sm">{{$appareil_digestif}}</textarea> 
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Appareil urinaire</label>
			<textarea name="appareil_urinaire" rows="3"  id="appareil_urinaire" class="form-control input-sm">{{$appareil_urinaire}}</textarea>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Rachis</label>
			<textarea name="rachis" rows="3"  id="rachis" class="form-control input-sm">{{$rachis}}</textarea> 
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Membres</label>
			<textarea name="membres" rows="3"  id="membres" class="form-control input-sm">{{$membres}}</textarea>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Organes génitaux externes</label>
			<textarea name="organes_genitaux" rows="3"  id="organes_genitaux" class="form-control input-sm">{{$organes_genitaux}}</textarea> 
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Doppler ombilical</label>
			<textarea name="doppler_ombilical" rows="3"  id="doppler_ombilical" class="form-control input-sm">{{$doppler_ombilical}}</textarea>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Artère Cérébrale Moy.</label>
			<textarea name="artere_cerebrale" rows="3"  id="artere_cerebrale" class="form-control input-sm">{{$artere_cerebrale}}</textarea> 
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Ductus Venosus</label>
			<textarea name="ductus_venosus" rows="3"  id="ductus_venosus" class="form-control input-sm">{{$ductus_venosus}}</textarea>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Annexes</label>
			<textarea name="annexes" rows="3"  id="annexes" class="form-control input-sm">{{$annexes}}</textarea> 
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Dopplers uterins</label>
			<textarea name="dopplers_uterins" rows="3"  id="dopplers_uterins" class="form-control input-sm">{{$dopplers_uterins}}</textarea>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<label>Col</label>
			<textarea name="col" rows="3"  id="col" class="form-control input-sm">{{$col}}</textarea> 
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<label>Commentaires</label>
			<textarea name="commentaires" rows="3"  id="commentaires" class="form-control input-sm">{{$commentaires}}</textarea>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<label>Conclusion</label>
			<textarea name="conclusion" rows="3"  id="conclusion" class="form-control input-sm">{{$conclusion}}</textarea> 
		</div>
	</div>
</div>


<div class="row">
    <div class="col-sm-2">
       <label class="control-label">Image n°1 (Optionnel):</label>
       {{if $image1}}
        <img src="{{$image1}}" id="image1" style="width: auto;height: 80px;">
        <button onclick="return deleteImage('image1')" class="btn btn-xs btn-danger">
            <span class="glyphicon glyphicon-trash"></span>
        </button>
       {{/if}}
       <input type="file" name="image1" class="form-control input-sm">  
    </div>
    <div class="col-sm-2">
        <label class="control-label">Image n°2 (Optionnel):</label>
        {{if $image2}}
            <img src="{{$image2}}" id="image2" style="width: auto;height: 80px;">
            <button onclick="return deleteImage('image2')" class="btn btn-xs btn-danger">
                <span class="glyphicon glyphicon-trash"></span>
            </button>
        {{/if}}
        <input type="file" name="image2" class="form-control input-sm"> 
    </div>
    <div class="col-sm-2">
        <label class="control-label">Image n°3 (Optionnel):</label>
        {{if $image3}}
            <img src="{{$image3}}" id="image3" style="width: auto;height: 80px;">
            <button onclick="return deleteImage('image3')" class="btn btn-xs btn-danger">
                <span class="glyphicon glyphicon-trash"></span>
            </button>
       {{/if}}
        <input type="file" name="image3" class="form-control input-sm">  
    </div>
    <div class="col-sm-2">
        <label class="control-label">Image n°4 (Optionnel):</label>
        {{if $image4}}
            <img src="{{$image4}}" id="image4" style="width: auto;height: 80px;">
            <button onclick="return deleteImage('image4')" class="btn btn-xs btn-danger">
                <span class="glyphicon glyphicon-trash"></span>
            </button>
       {{/if}}
        <input type="file" name="image4" class="form-control input-sm">  
     </div>
     <div class="col-sm-2">
        <label class="control-label">Image n°5 (Optionnel):</label>
        {{if $image5}}
            <img src="{{$image5}}" id="image5" style="width: auto;height: 80px;">
            <button onclick="return deleteImage('image5')" class="btn btn-xs btn-danger">
                <span class="glyphicon glyphicon-trash"></span>
            </button>
       {{/if}}
        <input type="file" name="image5" class="form-control input-sm">  
     </div>
</div>

<br>

<div class="row">
	<div class="col-sm-12 text-center">
		<input type="hidden" name="id" value="{{$id}}">
		<input type="submit" name="save" id="save" value="Enregistrer" class="btn btn-sm btn-primary">
	</div>
</div>

<br><br>
</form>

{{/if}}