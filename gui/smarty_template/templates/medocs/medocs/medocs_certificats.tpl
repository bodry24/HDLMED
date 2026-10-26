<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<form method="post" name="newform" action="" class="form-horizontal">
				<div class="form-group">
					<label class="label-control col-sm-4">Type de Certificat</label>
					<div class="col-sm-8">
						<select name="typeCertificat" id="typeCertificat" class="form-control input-xs" onchange="return loadTypeCertificat(this.value)">
							{{$selectOptionsCertificats}}
						</select>
					</div>
				</div>
			</form>
		</div>
		{{if $typeCertificat}}
		<form class="form-horizontal" action="" method="POST" onsubmit="return checkForm(this);">
			<input type="hidden" name="type_certificat" id="type_certificat" value="{{$type_certificat}}">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">AJOUT & MISE A JOUR  
						<p class="pull-right">
							<a href="{{$urlNouvelleLettre}}" class="btn btn-success btn-xs text-strong" style="color:#fff; margin-right: 250px;">NOUVEAU CERTIFICAT</a>
						    
							<a href="{{$urlAfficheLettre}}" class="btn btn-primary btn-xs text-strong" style="color:#fff">LISTE DES CERTIFICATS</a>
						</p>
					</h4>
				</div>
				<div class="panel-body">
					<div class="col-sm-12">		
						<div class="form-group">
							<label class="label-control col-sm-4">Nom du médécin</label>
							<div class="col-sm-8">
								<input type="text" name="med_name" id="med_name" value="{{$doctor}}" class="form-control input-xs">
							</div>
						</div>
						<div class="form-group">
							<label class="label-control col-sm-4">Titre du médécin</label>
							<div class="col-sm-8">
								 <input type="text" name="med_title" id="med_title" value="Docteur en médecine" class="form-control input-xs">
							</div>
						</div>
					</div>
					
					<div class="col-sm-12">
					{{if $edit}}
						<div class="form-group">
                            <label>Corps de la lettre</label>
                            <textarea name="body_letter" id="body_letter" cols="50" rows="100">{{$body_letter}}</textarea>
                        </div>
					{{else}}

						{{if $typeCertificat==1}}

						<div class="form-group">
							<label class="label-control col-sm-4">Durée du repos</label>
                            <div class="col-sm-4">
                            	 <input type="text" name="rest_time_nr" id="rest_time_nr" size="10" class="form-control input-sm">
                            </div>
                            <div class="col-sm-4">
                            	<select name="rest_time" id="rest_time" class="form-control input-sm">
                                    <option value="1">Jours</option>
                                    <option value="2">Semaines</option>
                                    <option value="3">Mois</option>
                                    <option value="4">Années</option>
                                </select>
                            </div>	
						</div>

						<div class="form-group">
							<label class="col-sm-4">Date début du repos</label>
							<div class="col-sm-8">
								<input type="text" name="date_start" id="date_start" class="form-control input-sm">
							</div>
						</div>
						
						{{elseif $typeCertificat==2}}
						
                        <div class="form-group">
							<label class="label-control col-sm-4">Date de début d'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="admission_start_date" id="admission_start_date" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="form-group">
							<label class="label-control col-sm-4">Date de fin d'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="admission_end_date" id="admission_end_date" class="form-control input-sm" value="{{$admission_end_date}}">
                            </div>
                        </div>
                       	<div class="form-group">
							<label class="label-control col-sm-4">Durée du repos</label>
                            <div class="col-sm-4">
                            	 <input type="text" name="rest_time_nr" id="rest_time_nr" size="10" class="form-control input-sm">
                            </div>
                            <div class="col-sm-4">
                            	<select name="rest_time" id="rest_time" class="form-control input-sm">
                                    <option value="1">Jours</option>
                                    <option value="2">Semaines</option>
                                    <option value="3">Mois</option>
                                    <option value="4">Années</option>
                                </select>
                            </div>	
						</div>

					    {{elseif $typeCertificat==3}}

						<div class="form-group">
							<label class="label-control col-sm-4">Durée du repos</label>
                            <div class="col-sm-4">
                            	 <input type="text" name="rest_time_nr" id="rest_time_nr" size="10" class="form-control input-sm">
                            </div>
                            <div class="col-sm-4">
                            	<select name="rest_time" id="rest_time" class="form-control input-sm">
                                    <option value="1">Jours</option>
                                    <option value="2">Semaines</option>
                                    <option value="3">Mois</option>
                                    <option value="4">Années</option>
                                </select>
                            </div>	
						</div>
						<div class="form-group">
							<label class="col-sm-4">Date début du repos</label>
							<div class="col-sm-8">
								<input type="text" name="date_start" id="date_start" class="form-control input-sm">
							</div>
						</div>

						{{elseif $typeCertificat==4}}
						
						<div class="form-group">
							<label class="label-control col-sm-4">Nombre de mois</label>
							<div class="col-sm-8">
								 <input type="text" name="nb_month" id="nb_month" value="{{$month}}" class="form-control input-xs">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4">Date début de la grossesse </label>
							<div class="col-sm-8">
								<input type="text" name="pregnant_start_date" id="pregnant_start_date" class="form-control input-sm">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4">Date prévue pour l'accouchement </label>
							<div class="col-sm-8">
								<input type="text" name="birth_start_date" id="birth_start_date" class="form-control input-sm">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4">Date début du congé prénatal </label>
							<div class="col-sm-8">
								<input type="text" name="pg_rest_start" id="pg_rest_start" class="form-control input-sm" value="{{$pg_rest_start}}">
							</div>
						</div>
                        
						{{elseif $typeCertificat==5}}

						<div class="form-group">
							<label class="col-sm-4">Date de l'accouchement </label>
							<div class="col-sm-8">
								<input type="text" name="birth_start_date" id="birth_start_date" class="form-control input-sm" value="{{$birth_start_date}}">
							</div>
						</div>
						<div class="form-group">
							<label class="label-control col-sm-4">Sexe de l'enfant</label>
                            <div class="col-sm-8">
                            	<select name="infant_sex" id="infant_sex" class="form-control input-sm">
                                    <option value="">Choisir le sexe</option>
                                    <option value="1">Masculin</option>
                                    <option value="2">Féminin</option>
                                </select>
                            </div>	
						</div>

						{{elseif $typeCertificat==6}}
						<div class="form-group">
							<label class="label-control col-sm-4">Date de début d'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="admission_start_date" id="admission_start_date" class="form-control input-sm" value="{{$admission_start_date}}">
                            </div>
                        </div>
						{{elseif $typeCertificat==7}}


						{{elseif $typeCertificat==8}}

						{{elseif $typeCertificat==9}}

						{{elseif $typeCertificat==10}}
						<div class="form-group">
							<label class="label-control col-sm-4">Nombre de mois</label>
							<div class="col-sm-8">
								 <input type="text" name="nb_month" id="nb_month" value="{{$month}}" class="form-control input-xs">
							</div>
						</div>
						<div class="form-group">
							<label class="label-control col-sm-4">Période de voyage</label>
                            <div class="col-sm-4">
                            	 <input type="text" name="rest_time_nr" id="rest_time_nr" size="10" class="form-control input-sm" value="{{$rest_time_nr}}">
                            </div>
                            <div class="col-sm-4">
                            	<select name="rest_time" id="rest_time" value="{{$rest_time}}" class="form-control input-sm">
                                    <option value="1">Jours</option>
                                    <option value="2">Semaines</option>
                                    <option value="3">Mois</option>
                                    <option value="4">Années</option>
                                </select>
                            </div>	
						</div>
						{{elseif $typeCertificat==11}}

						{{elseif $typeCertificat==12}}

						<div class="form-group">
							<label class="label-control col-sm-4">Date de début d'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="date_start" id="date_start" class="form-control input-sm" value="{{$date_start}}">
                            </div>
                        </div>
						<div class="form-group">
							<label class="label-control col-sm-4">Durée probable d'hospitalisation</label>
                            <div class="col-sm-4">
                            	 <input type="text" name="rest_time_nr" id="rest_time_nr" size="10" class="form-control input-sm" value="{{$rest_time_nr}}">
                            </div>
                            <div class="col-sm-4">
                            	<select name="rest_time" id="rest_time" class="form-control input-sm">
                                    <option value="1">Jours</option>
                                    <option value="2">Semaines</option>
                                    <option value="3">Mois</option>
                                    <option value="4">Années</option>
                                </select>
                            </div>	
						</div>
                       
						{{elseif $typeCertificat==13}}
						<div class="form-group">
							<label class="label-control col-sm-4">Date prévue pour l'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="date_start" id="date_start" value="{{$date_start}}" class="form-control input-sm">
                            </div>
                        </div>
						{{elseif $typeCertificat==14}}
						
                        <div class="form-group">
							<label class="label-control col-sm-4">Date de début d'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="admission_start_date" id="admission_start_date" class="form-control input-sm" value="{{$admission_start_date}}">
                            </div>
                        </div>
                        <div class="form-group">
							<label class="label-control col-sm-4">Date de fin d'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="admission_end_date" id="admission_end_date" class="form-control input-sm" value="{{$admission_end_date}}">
                            </div>
                        </div>
                        
						{{elseif $typeCertificat==15}}

						{{elseif $typeCertificat==16}}
						<div class="form-group">
							<label class="label-control col-sm-4">Date de début d'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="admission_start_date" id="admission_start_date" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="form-group">
							<label class="label-control col-sm-4">Nom et prénoms de la maman</label>
							<div class="col-sm-8">
								 <input type="text" name="mother_name" id="mother_name" value="{{$mother_name}}" class="form-control input-xs">
							</div>
						</div>

						{{elseif $typeCertificat==17}}
						<div class="form-group">
							<label class="label-control col-sm-4">Date prévue pour l'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="admission_start_date" id="admission_start_date" class="form-control input-sm" value="{{$admission_start_date}}">
                            </div>
                        </div>
                        {{elseif $typecertificat==18}}
						<div class="form-group">
							<label class="label-control col-sm-4">Date d'hospitalisation</label>
                            <div class="col-sm-8">
                            	 <input type="text" name="admission_start_date" id="admission_start_date" class="form-control input-sm" value="{{$admission_start_date}}">
                            </div>
                        </div>

                        {{else}}
                           

						{{/if}}
				
					{{/if}}
					</div>

					<div class="col-sm-12">
						<div class="form-group">
							<label class="label-control col-sm-4">Date du certificat</label>
							<div class="col-sm-8">
								<input type="text" name="sick_confirm_date" id="sick_confirm_date" value="{{$sick_confirm_date}}" class="form-control input-xs" class="form-control input-xs">
							</div>
						</div>
					</div>

				</div>
		
				<div class="panel-footer text-center">
	                <input type="hidden" name="certif_nr" value="{{$certif_nr}}">
	                <input type="hidden" name="encounter_nr" value="{{$encounter_nr}}">
	                <input type="hidden" name="mode" value="{{$mode}}">
	              
					<input type="submit" name="save" id="save" class="btn btn-sm btn-primary" value="Enregistrer">
				</div>
			</div>
		</form>
		{{/if}}

	</div>
</div>
{{if !$typeCertificat}}
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Liste des Certificats</h4>
			</div>
			<div class="body">
				<br>
				<table class="table table-striped table-bordered" id="exemple" style="padding: 5px;">
					<thead>
		                <tr>
		                    <th width="5%">N°</th>
		                    <th width="15%">Date</th>
		                    <th width="25%">Type de Certificat</th>
		                    <th width="15%">Fait le</th>
		                    <th width="25%">Enregistrer par</th>
		                    <th width="15%">Actions</th>
		                </tr>
		            </thead>
					<tbody>
						{{$listingCertificats}}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
{{/if}}

