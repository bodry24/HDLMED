<?php
    $LDCreateNewForm = 'Choisir le type de lettre' ;
    $certif_type = array(
        1 => 'Consultation',
        2 => 'Reponse consultation',
        3 => 'Hospitalisation',
        4 => 'Chirurgie'
    );
    
    $nb_certif_type = count($certif_type);
?>

<style type="text/css">
    .link { cursor: pointer;}
    .right{
        text-align: right;
    }
    .left{
        text-align: left;
    }
    .center{
        text-align: center;
    }
</style>

<script language="JavaScript">
    
    function popPrintSickConfirm(nr, type) {
        urlholder='print_letter.php?sid=<?php echo $sid ?>'+'&lang=<?php echo $lang;?>'+'&get_nr=' + nr + '&certif_type=' + type;
        drgwin_<?php echo $sid ?>=window.open(urlholder,"drgwin_<?php echo $sid ?>","width=600,height=650,menubar=no,resizable=yes,scrollbars=yes");
    }
    function loadSickForm(value){

        if(isNaN(parseInt(value))||value==''){
            alert('value'+value);
            return false;
        }
        else{
            if(value<=parseInt('<?php echo $nb_certif_type;?>')&& value>=1){
                document.newform.submit();
            }
        }
        return;
    }

    $(function(){
        $("#exemple").DataTable({
            language: {
                processing:     "Traitement en cours...",
                search:         "Rechercher&nbsp;:",
                lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
                info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                infoPostFix:    "",
                loadingRecords: "Chargement en cours...",
                zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                emptyTable:     "Aucune donn&eacute;e disponible dans le tableau",
                paginate: {
                    first:      "Premier",
                    previous:   "Pr&eacute;c&eacute;dent",
                    next:       "Suivant",
                    last:       "Dernier"
                },
                aria: {
                    sortAscending:  ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            }
           
        });

    });
   
</script>

<div class="row">
    <div class="col-sm-12"> 
        <form  style="width:50%;" method="post" name="newform" action="<?php echo $baseLink.'&target=lettre'; ?>" class="">
            <div class="field">    
                <label for="type_certif"><?php echo $LDCreateNewForm; ?></label>
                <select id="type_certif" name="type_certif" onchange="return loadSickForm(this.value);">
                    <option value=""></option>
                    <?php
                        asort($certif_type);
                        foreach($certif_type as $key=>$value){
                        ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php                    
                        }
                ?></select>
            </div>
            <input type="hidden" name="sid" value="<?php echo $sid; ?>">
            <input type="hidden" name="lang" value="<?php echo $lang; ?>">
            <input type="hidden" name="encounter_nr" value="<?php echo $_SESSION['sess_en']; ?>">
            <!-- <input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>"> -->
            <input type="hidden" name="mode" value="new">
            <input type="hidden" name="target" value="<?php echo $target; ?>">
        </form>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
    <?php if($rows>0){   ?>   

        <center><h4><u>Affichage des lettres</u></h4></center>
       
        <table id="exemple" class="table table-responsive table-bordered table-striped-hover">
            <thead>
                <tr>
                    <th width="10%">Date</th>
                    <th width="20%">Objet</th>
                    <th width="15%">Enregistrer par</th>
                    <th width="20%">Destinataire</th>
                    <th width="15%">Type de lettre</th>
                    <th width="20%">Actions</th>
                </tr>
            </thead>
            <tbody>
        <?php
            $i=0; 
            while($ligne=$objLetter->FetchRow()){

                $i++;
                $date=date_create($ligne['date_letter']);
                $date_create=date_format($date, 'd-m-Y');
                //$date_create=formatDate2Local($ligne['date_letter'],$date_format);
                $nr=$ligne['nr']; 

                $object=$ligne['object_letter']; 
                $save_by = $ligne['create_id'];
                $receiver = $ligne['receiver'];
                $letter_type = $certif_type[$ligne['letter_type_nr']];
                

                $linkPrint=$root_path."modules/medocs/print_lettre_pdf.php?nr=".$nr."&letter_type_nr=".$ligne['letter_type_nr'];
                $linkEdit=$baseLink."&target=$target&mode=update&nr=$nr&type_certif=".$ligne['letter_type_nr'];
                $linkDelete=$baseLink."&target=$target&mode=delete&nr=$nr";

                $print_link ='<a href='.$linkPrint.' target="_blank" class="ui blue compact icon button" title="Imprimer"><i class="print icon"></i></a>';
                $edit_link='<a href='.$linkEdit.' class="ui green compact icon button" title="Editer"><i class="write edit icon"></i></a>';
                $delete_link='<a href='.$linkDelete.' class="ui red compact icon button" onclick="return <?php echo $mode;?>;" title="Supprimer"><i class="delete icon"></i></a>';
        ?>
                    <tr>
                        <td><?php echo $date_create;?></td>
                        <td><?php echo !empty($object)?$object:"&nbsp;";?></td>
                        <td><?php echo $save_by;?></td>
                        <td><?php echo $receiver;?></td>
                        <td><?php echo $letter_type;?></td>
                        <td>
                            <?php echo $edit_link;?>
                             <?php echo $print_link;?>
                            <?php echo $delete_link;?>                       
                        </td>
                      
                    </tr>          
    <?php   }   ?> 

            </tbody>
        </table>

<?php    }else echo "Pas de lettre pour le moment !"; ?>

    </div>
</div>
