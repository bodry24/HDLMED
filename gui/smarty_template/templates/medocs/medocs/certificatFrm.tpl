<div class="well well-sm">
    <form class="form-horizontal" name="newform" action="{{$newFormAction}}" method="POST">
        <div class="form-group">
            <label class="control-label col-sm-4">{{$LDTypeCertificat}}</label>
            <div class="col-sm-8">
                <select id="type_certif" name="type_certif" class="form-control input-sm" onchange="return loadSickForm(this.value);">
                    <option value=""></option>
                    {{$selectTypeOptions}}
                </select>
            </div>
        </div>
        <input type="hidden" name="nb_certif_type" id="nb_certif_type" value="{{$nb_certif_type}}">
        <input type="hidden" name="sid" value="<?php echo $sid; ?>">
        <input type="hidden" name="lang" value="<?php echo $lang; ?>">
        <input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
        <input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>">
        <input type="hidden" name="mode" value="new">
        <input type="hidden" name="target" value="<?php echo $target; ?>">
    </form>
</div>

{{if $typeCertif}}
    <form name="sickform" onSubmit="return chkSickForm(this)" method="post">
        <fieldset><legend>{{$certifLegend}}</legend>
            <table>
                <tr>
                    <td><label for="med_name">Nom du m&eacute;decin</label></td>
                    <td>
                        <input type="text" name="med_name" id="med_name" value="{{$doctorId}}"/>
                    </td>
                </tr>
                <tr>
                    <td><label for="med_title">Titre du m&eacute;decin</label></td>
                    <td>
                        <input type="text" name="med_title" id="med_title" value="Docteur en m&eacute;decine"/>
                    </td>
                </tr>
                
                <tr>
                    <td><label for="sick_confirm_date">Date du certificat</label></td>
                    <td>{{$certifDate}}</td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="image" <?php echo createLDImgSrc($root_path,'savedisc.gif','0'); ?>>
                    </td>
                </tr>
            </table>   
            <input type="hidden" name="sid" value="<?php echo $sid; ?>">
            <input type="hidden" name="lang" value="<?php echo $lang; ?>">
            <input type="hidden" name="certif_nr" value="<?php echo $type_certif; ?>">
            <input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
            <input type="hidden" name="mode" value="create">
            <input type="hidden" name="target" value="<?php echo $target; ?>">
        </fieldset>
    </form>
{{/if}}
