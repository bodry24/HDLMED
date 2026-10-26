<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Liste des Spécialités</h4>
			</div>
			<div class="body">
				<table class="table table-striped table-bordered">
					<thead>
						<tr class="info">
							<th style="width:50px">#</th>
							<th>Désignation</th>
							<th style="width:80px">Action</th>
						</tr>
					</thead>
					<tbody>
						{{$listing}}
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<form class="form-horizontal" action="{{$formAction}}" method="post">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Formulaire d'enregistrement</h4>
				</div>
				<div class="panel-body">
					<label class="label-control col-sm-4">Désignation</label>
					<div class="col-sm-8">
						<input type="text" name="designation" id="designation" value="{{$designation}}" class="form-control input-xs">
					</div>
				</div>
				
				<div class="panel-footer">
				    <input type="hidden" name="id" id="id" value="{{$id}}">
					<input type="submit" name="save" id="save" class="btn btn-sm btn-primary" value="Enregistrer">
				</div>
			</div>
		</form>
	</div>
</div>