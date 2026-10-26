<div class="row">
	<div class="col-sm-12">
		

		<div class="well">
			<form method="post" name="newform" action="" class="form-horizontal">
				<div class="form-group">
					<label class="label-control col-sm-4">Type de Lettre</label>
					<div class="col-sm-8">
						<select name="typeLettre" id="typeLettre" class="form-control input-xs" onchange="return loadTypeLettre(this.value)">
							{{$selectOptionsLettres}}
						</select>
					</div>
				</div>
			</form>
		</div>
		{{if $typeLettre}}
		<form class="form-horizontal" action="" method="POST" onsubmit="return checkForm();">
			<input type="hidden" name="type_lettre" id="type_lettre" value="{{$type_lettre}}">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">AJOUT & MISE A JOUR  
						<p class="pull-right">
							<a href="{{$urlNouvelleLettre}}" class="btn btn-success btn-xs text-strong" style="color:#fff; margin-right: 250px;">NOUVELLE LETTRE</a>
						    
							<a href="{{$urlAfficheLettre}}" class="btn btn-primary btn-xs text-strong" style="color:#fff">LISTE DES LETTRES</a>
						</p>
					</h4>
				</div>
				<div class="panel-body">
					<div class="col-sm-12">		
						<div class="form-group">
							<label class="label-control col-sm-4">Date</label>
							<div class="col-sm-8">
								<input type="text" name="date_letter" id="date_letter" value="{{$date_letter}}" class="form-control input-xs">
							</div>
						</div>
						<div class="form-group">
							<label class="label-control col-sm-4">Code Lettre</label>
							<div class="col-sm-8">
								<input type="text" name="code_letter" id="code_letter" value="{{$code_letter}}" class="form-control input-xs">
							</div>
						</div>
						<div class="form-group">
							<label class="label-control col-sm-4">Lieu</label>
							<div class="col-sm-8">
								<input type="text" name="location" id="location" value="{{$location}}" class="form-control input-xs">
							</div>
						</div>
						<div class="form-group">
							<label class="label-control col-sm-4">Objet</label>
							<div class="col-sm-8">
								<input type="text" name="object_letter" id="object_letter" value="{{$object_letter}}" class="form-control input-xs">
							</div>
						</div>
						<div class="form-group">
							<label class="label-control col-sm-4">Destinataire</label>
							<div class="col-sm-8">
								<input type="text" name="receiver" id="receiver" value="{{$receiver}}" class="form-control input-xs">
							</div>
						</div>
					</div>
					
					<div class="col-sm-12">
					{{if $edit}}
						<div class="form-group col-sm-12">
                            <label>Corps de la lettre</label>
                            <textarea name="body_letter" id="body_letter" cols="50" rows="100">{{$body_letter}}</textarea>
                        </div>
					{{else}}
						{{if $typeLettre==1}}
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label>Hypothèse</label>
	                                <textarea cols="5" class="col-sm-12 form-control input-sm" style="width: 100%;" rows="" cols="500" id="hypothese" name="hypothese" placeholder="Hypothèse"></textarea>
								</div>
								<div class="form-group">
									<label>Examens Complémentaires</label>
	                                <textarea cols="5" class="col-sm-12 form-control input-sm" style="width: 100%;" rows="" cols="500" id="examen" name="examen" placeholder="Examen complémentaires"></textarea>
								</div>
							</div>	
						</div>
							
						{{elseif $typeLettre==2}}
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
	                                <label>Diagnostic</label>
	                                <textarea name="diagnostic" id="diagnostic" value cols="50" rows="20">
	                                </textarea>
	                            </div>
	                            <div class="form-group">
	                                <label>Solution Préconisée</label>
	                                <textarea name="preconise" id="preconise" value cols="50" rows="20"></textarea>
	                            </div>	
	                        </div>
	                    </div>

					    {{elseif $typeLettre==3}}
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
		                                <label>Date début</label>
		                               	<input type="text" name="date_debut" id="date_debut" class="form-control input-sm">
		                            </div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
		                                <label>Date fin</label>
		                               	<input type="text" name="date_fin" id="date_fin" class="form-control input-sm">
		                            </div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
		                                <label>Date sortie</label>
		                                <input type="text" name="date_sortie" id="date_sortie" class="form-control input-sm">
		                            </div>
								</div>
							</div>
 
                            <div class="form-group col-sm-12">
                                <label>Motif</label>
                                <textarea name="operatoire" id="operatoire" value cols="50" rows="20"></textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>Diagnostic</label>
                                <textarea name="diagnostic" id="diagnostic" value cols="50" rows="20">
                                </textarea>
                            </div>
                            
                            <div class="form-group col-sm-12">
                                <label>Traitement</label>
                                <textarea name="preconise" id="preconise" value cols="50" rows="20"></textarea>
                            </div>

						{{elseif $typeLettre==4}}
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
		                                <label>Date début</label>
		                               	<input type="text" name="date_debut" id="date_debut" class="form-control input-sm">
		                            </div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
		                                <label>Date fin</label>
		                               	<input type="text" name="date_fin" id="date_fin" class="form-control input-sm">
		                            </div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
		                                <label>Date sortie</label>
		                                <input type="text" name="date_sortie" id="date_sortie" class="form-control input-sm">
		                            </div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chirurgien</label>
									<div class="col-sm-12">
										<input type="text" name="Chirurgien" id="Chirurgien" class="form-control input-sm">
									</div>
									
								</div>
							</div>
                         
                            <div class="form-group col-sm-12">
                                <label>Motif</label>
                                <textarea name="operatoire" id="operatoire" value cols="50" rows="20"></textarea>
                            </div>

                            <div class="form-group col-sm-12">
                                <label>Suites opératoires</label>
                                <textarea name="diagnostic" id="diagnostic" value cols="50" rows="20">
                                </textarea>
                            </div>
                            
                            <div class="form-group col-sm-12">
                                <label>Traitement</label>
                                <textarea name="preconise" id="preconise" value cols="50" rows="20"></textarea>
                            </div>
						{{/if}}
				
					{{/if}}
					</div>

				</div>
		
				<div class="panel-footer text-center">
				    <input type="hidden" name="nr" id="nr" value="{{$nr}}">
					<input type="submit" name="save" id="save" class="btn btn-sm btn-primary" value="Enregistrer">
				</div>
			</div>
		</form>
		{{/if}}

	</div>
</div>
{{if !$typeLettre}}
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Liste des Lettres</h4>
			</div>
			<div class="body">
				<br>
				<table class="table table-striped table-bordered" id="exemple" style="padding: 5px;">
					<thead>
		                <tr>
		                    <th width="5%">N°</th>
		                    <th width="10%">Date</th>
		                    <th width="20%">Objet</th>
		                    <th width="15%">Enregistrer par</th>
		                    <th width="20%">Destinataire</th>
		                    <th width="15%">Type de lettre</th>
		                    <th width="15%">Actions</th>
		                </tr>
		            </thead>
					<tbody>
						{{$listingLettres}}
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
{{/if}}

