<div class="row">
	{{**
	{{$locationInformation}}
	**}}
	<div class="col-sm-4">
	    <div class="panel panel-default">
	    	<div class="panel-heading">
	    		<h4 class="panel-title">Liste des Demandes</h4>
	    	</div>
	    	<div class="panel-body">

	    		<table class="table table-responsive table-striped">
	    			<tr style='background-color: #00009c; color:white; font-weight: bold'>
	    				<th>N°</th>
	    				<th>Date Demande</th>
	    				<th>Operateur</th>
	    				<th>Option</th>
	    			</tr>
	    			{{foreach from=$demandes item=demande}}
	    				{{$demande}}
	    			{{/foreach}} 

	    		</table>
	    	</div>
	    </div>
	</div>
	<div class="col-sm-8">
		<table class="table">	
	    	<tr>
				<td style="width:30%;"><label class="control-label">Analyse</label></td>
				
	    		<td style="width:70%">
	    			<select class="form-control input-sm" size="10" name="acte" id="acte" multiple="multiple">
			    		<!-- <option value="">Veuillez selectionner Acte</option> -->
			    		{{$selectActe}}
			    	</select>
			    	
	    		</td>
	    	</tr>
	    </table>
    	
   		<form class="form-horizontal" action="" method="POST" onsubmit="return chkForm()">

			<table class="table table-responsive table-striped table-bordered">
				<thead>
					<tr style="background-color:#00009c; color:white; font-weight:bold">
						<td></td>
						<td>Analyses</td>
						<td>PU</td>
						<td>Date</td>
						<td>Option</td>
					</tr>
				</thead>
				<tbody id="tableBody">
				
				{{if $items }}
					{{foreach from=$items item=row}}
                        <tr>
                        	<td>{{$row.rowId}}</td>
                        	<td>{{$row.analysis}}</td>
                        	<td>{{$row.pu}}</td>
                        	<td>{{$row.date}}</td>
                        	<td align="center">                            		                            		
                        		<a href="{{$baseUrl}}&laboratory_id={{$laboratory_id}}&id_analysis={{$row.id_analysis}}&mode=delete" onclick="return confirmSuppression()">
                        			<span class="btn btn-sm btn-danger">
                        				<i class="glyphicon glyphicon-trash"></i>
                        			</span>
                        		</a>
                        	</td>
                        </tr>
					{{/foreach}}
				{{/if}}

				</tbody>
			</table>

			<table class="table table-responsive table-bordered">
				<tr>
					<td style="width:30%;"><label class="control-label">Medécin</label></td>
					
    	    		<td style="width:70%">
    	    			<select class="form-control input-sm" name="med_pid" id="med_pid">
				    		<!-- <option value="">Veuillez selectionner Acte</option> -->
				    		{{$selectMedecin}}
				    	</select>
				    	
    	    		</td>
    	    	</tr>
			</table>
			
		    <input type="hidden" name="laboratory_id" id="laboratory_id" value="{{$laboratory_id}}">
		    <center>
		    	<input type="submit" class="btn btn-primary btn-sm" name="save" id="save" value="Enregistrer">
		    </center>
		</form>
	</div>
</div>


<div id="modalDetail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">DETAILS DE LA DEMANDE</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12">
                      
                        <table class="table">
                            <thead>
                            	<tr>
	                            	<th width="10%">N°</th>
	                            	<th width="50%">Désignation</th>
	                            	<th width="20%">Prix </th>
	                            	<th width="10%">Option</th>
	                            </tr>
                            </thead>
                            <tbody id="detailBody">
                            	
                            </tbody>
                        </table>
                        
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>