<div class="row">
  <div class="col-sm-12">
      <form name="frmVaccination" method="POST" action="" onsubmit="return validationForm();">
      <div class="panel panel-primary">
        <div class="panel-heading">
          NOUVELLE VACCINATION
        </div>
        <div class="panel-body">
         
            <div class="row">

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-form col-sm-4">Vaccin</label>
                  <div class="col-sm-8">
                    <select name="vaccin" id="vaccin" class="form-control input-sm">
                      {{$options}}
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-form col-sm-4">Date effectuée</label>
                  <div class="col-sm-8">
                    <input type="text" name="date_effectuee" value="{{$date_effectuee}}" id="date_effectuee" class="form-control input-sm">
                  </div>
                </div>
              </div>

            </div>
          
        </div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-12 text-center">
              <input type="submit" name="enregistrer" value="Enregistrer" class="btn btn-sm btn-primary">
            </div>
          </div>
        </div>

      </div>
      </form>
  </div>
</div>

<div class="row">
	<div class="col-sm-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
          LISTE DES VACCINATIONS EFFECTUEES
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped-hover" id="exemple">
            <thead>
              <tr>
                <th class="text-center" width="10%">N°</th>
                <th class="text-center" width="55%">Vaccin</th>
                <th class="text-center" width="20%">Date effectuée</th>
                <th class="text-center" width="15%">Actions</th>
              </tr>
            </thead>
            <tbody>
              {{$listing}}
            </tbody>
          </table>
    		  
        </div>
    </div>
	</div>
</div>

