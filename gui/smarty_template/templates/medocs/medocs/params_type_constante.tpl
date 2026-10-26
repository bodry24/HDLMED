<ol class="breadcrumb">
	<li>Parametrage</li>
	<li>Type de consatnte</li>
</ol>
{{if $msg}}
    <div class="alert alert-{{$msgType}}" style="font-size:12px;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <p class="check">{{$msg}}</p>
    </div>
{{/if}}
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">Liste des Types de Constante</h4>
			</div>
			<div class="body">
				<br>
				<table class="table table-striped table-bordered" id="datatable">
					<thead style="background-color: #D9EDF7">
						<tr>
							<th>#</th>
							<th>Désignation</th>
							<th>Description</th>
							<th>Action</th>
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
		<form class="form-horizontal" action="" method="post" onsubmit="return checkForm();">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">Formulaire d'enregistrement</h4>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="label-control col-sm-4">Désignation</label>
						<div class="col-sm-8">
							<input type="text" name="name" id="name" value="{{$name}}" class="form-control input-xs">
						</div>
					</div>
					
					<div class="form-group">
						<label class="label-control col-sm-4">LD_var</label>
						<div class="col-sm-8">
							<input type="text" name="LD_var" id="LD_var" value="{{$LD_var}}" class="form-control input-xs">
						</div>
					</div>

					<div class="form-group">
						<label class="label-control col-sm-4">LD Description </label>
						<div class="col-sm-8">
							<input type="text" name="LD_description" id="LD_description" value="{{$LD_description}}" class="form-control input-xs">
						</div>
					</div>

					<div class="form-group">
						<label class="label-control col-sm-4">Description </label>
						<div class="col-sm-8">
							<input type="text" name="description" id="description" value="{{$description}}" class="form-control input-xs">
						</div>
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