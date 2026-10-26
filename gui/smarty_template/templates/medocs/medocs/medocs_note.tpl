<div class="row">
  <div class="col-sm-12">
      <form name="frmNote²" method="POST" action="" onsubmit="return validationForm();">
      <div class="panel panel-primary">
        <div class="panel-heading">
          NOUVELLE NOTE D'HOSPITALISATION
        </div>
        <div class="panel-body">
         
            <div class="row">
              <div class="col-sm-5">
                <div class="form-group">
                  <label class="control-form col-sm-4">Type</label>
                  <div class="col-sm-8">
                    <select name="type_nr" id="type_nr" class="form-control input-sm">
                      {{$options}}
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-sm-5">
                <div class="form-group">
                  <label class="control-form col-sm-4">Date & Heure</label>
                  <div class="col-sm-8">
                    <input type="text" name="date" value="{{$date}}" id="date" class="form-control input-sm">
                  </div>
                </div>
              </div>
            {{**
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-form col-sm-4">Heure</label>
                  <div class="col-sm-8">
                    <input type="text" name="time" value="{{$time}}" id="time" class="form-control input-sm">
                  </div>
                </div>
              </div>**}}
              <div class="col-sm-2">
                <button type="button" class="mic-btn" data-target="nom">🎤 Dictée</button>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="col-sm-12">
                    <br>
                    <center><label>Prescriptions</label></center>
                    <textarea name="notes" id="notes" class="form-control input-sm" rows="10">{{$notes}}</textarea>
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
          LISTE DES NOTES 
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped-hover" id="">
            <thead>
              <tr>
                <th class="text-center" width="10%">N°</th>
                <th class="text-center" width="15%">Type</th>
                <th class="text-center" width="40%">Notes</th>
                <th class="text-center" width="20%">Date & Heure</th>
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

