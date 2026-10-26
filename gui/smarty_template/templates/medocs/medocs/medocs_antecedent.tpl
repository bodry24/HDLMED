
{{if $show}}
	<div class="row">
		<div class="col-sm-12 text-center">
			<a href="{{$urlUpdate}}" class="btn btn-sm btn-success" style="color:#fff;" title="Modifier les antécédents">
				<span class="glyphicon glyphicon-edit"></span> Mettre à Jour
			</a>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading" style="background-color: #02327E">
					<h4 class="panel-title">Détail des Antécédents</h4>
				</div>
				<div class="body">
					<br>
					<table id="" class="table table-bordered table-striped-hover" style="padding: 5px;">
						<thead>
			                <tr>
			                    <th class="text-center" width="20%">Libellé</th>
						        <th class="text-center" width="80%">Contenu</th>
			                </tr>
			            </thead>

						<tbody>
							<tr> <td><label>Père</label></td> <td>{{$pere}}</td> </tr>
							<tr> <td><label>Mère</label></td> <td>{{$mere}}</td> </tr>	
							<tr> <td><label>Fraterie</label></td> <td>{{$fraterie}}</td> </tr>	
							<tr> <td><label>Gynécologiques</label></td> <td>{{$gyneco}}</td> </tr>	
							{{if $feminin}}
							<tr> <td><label>obstetrique</label></td> <td>{{$obstetrique}}</td> </tr>
							{{/if}}	
							<tr> <td><label>Autres</label></td> <td>{{$autres}}</td> </tr>	
							
							
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
{{else}}
<div class="row">
	<div class="col-sm-12">
		<form method="post" action="" onsubmit="return chechSubmit();">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>FAMILIAUX</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="form-group">
							<div class="col-sm-4">
								<label class="control-label">Père</label>
								<textarea name="pere" rows="10" placeholder="Père" class="form-control input-sm">{{$pere}}</textarea>
									
								
							</div>
							<div class="col-sm-4">
								<label class="control-label">Mère</label>
								<textarea name="mere" rows="10" placeholder="Mère" class="form-control input-sm">{{$mere}}</textarea>
									
								
							</div>
							<div class="col-sm-4">
								<label class="control-label">Fraterie</label>
								<textarea name="fraterie" rows="10" placeholder="fraterie" class="form-control input-sm">{{$fraterie}}</textarea>
										
								
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>PERSONNEL</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="form-group">
							<label class="control-label">Médicaux</label>
							<textarea name="medicaux" rows="5" placeholder="Médicaux" class="form-control input-sm">{{$medicaux}}</textarea>
								
							
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label class="control-label">Gynecologiques</label>
						
							<textarea name="gyneco" rows="5" placeholder="gyneco" class="form-control input-sm">{{$gyneco}}</textarea>
								
							
						</div>
					</div>
					{{if $feminin}}
					<div class="row">
			            <div class="form-group">
			                <label class="control-label">Obstétriques</label>
		                
		                    <textarea name="obstetrique" rows="5" placeholder="obstetrique" class="form-control input-sm">{{$obstetrique}}</textarea>
		                       
		                   
			                
			            </div>
	    			</div>
	    			{{/if}}
					
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>AUTRES</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="form-group">
							<textarea name="autres" rows="5" placeholder="Autres" class="form-control input-sm">{{$autres}}</textarea>
								
							
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 text-center">
					<input type="hidden" name="code_ante" value="{{$code_ante}}">
					<input type="submit" name="save" class="btn btn-sm btn-primary" value="sauvegarder">
				</div>
			</div>
	   		<br>
		</form>
	</div>
</div>
{{/if}}

