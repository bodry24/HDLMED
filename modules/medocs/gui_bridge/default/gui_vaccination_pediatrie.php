<?php  
ob_start();
?>

<link rel="stylesheet" href="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.css';?>"/>
<script type="text/javascript" src="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.js';?>"></script> 
<script type="text/javascript" src="<?php echo $root_path.'js/gadgets/squeezebox.js'; ?>"></script>
<style type="text/css">
	
</style> 
<script type="text/javascript">

    var etatCheckbox; //RecupÃ©rer l'Ã©tat du checkbox
       
    $(document).ready(function(){
        $('[id*=check_]').each(function(i){
            var patientId=$("[name=monid]").val();
    

            $(this).click(function(){
                //l'id de la vaccination
                var idVac=$(this).attr('idvac');
                //l'id dans care_vaccination_patient
                var vacPid=$(this).attr('vacpid');

                //Donner la valeur 0 ou 1 Ã  etatcheckbox
                if($(this).is(':checked')){
                    etatCheckbox=1;
                }else{
                    etatCheckbox=0;
                }
                //alert(vacPid);
                /* Faire le traitement en passant les variables(
                l'id du patient,l'état du checkbox et l'id du vaccin)*/
                //alert(etatCheckbox);die();
                $.get("<?php echo $root_path.'modules/registration_admission/insert_patient_vacciner.php';?>",
                    {'monid':patientId,'vacid':idVac,'etatcheck':etatCheckbox,'vacpid':vacPid},
                    function(data){        
                        //alert('ok');
                        //die();
                       window.location.reload();

                        if(data){
                            //alert(data);
                            //$(this).attr("disabled","disabled");
                        } 
                    });
            });               
        });

        //Pour sauvegarder un nouveau vaccin
        $("#nouveau_vaccin").click(function(){
            var nom_vaccin=$("#nom_vaccin").val();
            var periode_vaccin=$("#periode_vaccin").val();
            var type_periode=$("#type_periode").val();
            var rappel_vaccin=$("#rappel_vaccin").val();
            if(nom_vaccin==''){
                alert('Veuillez saisir le nom du vaccin');
                $("#nom_vaccin").focus();
            }else if(periode_vaccin==''){
                alert('Veuillez saisir la période du vaccin');
                $("#periode_vaccin").focus();
            }else{
                //je fais l'enregistrement du vaccin par post
                $.post("<?php echo $root_path.'modules/registration_admission/insert_patient_vacciner.php';?>",{'nom_vaccin':nom_vaccin,'periode_vaccin':periode_vaccin,'type_periode':type_periode,'rappel_vaccin':rappel_vaccin ,'action':'insert_vaccin'}, 
                function(data){
                    if(data){
                        alert(data);
                        window.location.reload();                           
                    }
                });
            }
            
        });

        //Pour l'édition du vaccin
        //$(".modal_trigger").leanModal({top : 200, overlay : 0.6, closeButton: ".modal_close" });
        $(".modal_trigger").click(function(){
        	$("#modalForm").show();
        });


        $("#dateEffectuee").datetimepicker({
			format:'dd-mm-yyyy',
			autoclose:true,
			minView:2
		});

        //Au click sur le bouton Editer je fais ce qui suis
        $('[id*=update_]').each(function(i){
            var patientId=$("[name=monid]").val();
    

            $(this).click(function(){
                //l'id de la vaccination
                var idVac=$(this).attr('idvac');
                //l'id dans care_vaccination_patient
                var vacPid=$(this).attr('vacpid');
                //Le pid du patient
                var Pid=$(this).attr('pid');
                //La date effectué
                var vacDate=$(this).attr('vacdate');
                
                //J'affecte les valeurs au formulaire
                $("#updateVid").val(idVac);
                $("#updateVacPid").val(vacPid);
                $("#updatePid").val(Pid);
                $("#dateEffectuee").val(vacDate);
                //Au click sur le bouton de mise à jour recupère la nouvelle date puis je fais la mise à jour dans la base
                $("#update").click(function(){
                    var updateDate=$("#dateEffectuee").val();
                    //alert(updateDate);die();
                    $.post("<?php echo $root_path.'modules/registration_admission/insert_patient_vacciner.php';?>",
                    {'updateDate':updateDate,'updatePid':Pid,'updateVacPid':vacPid,'updateVid':idVac, 'action':'update'},
                    function(data){        
                        if(data){
                            alert(data);
                            window.location.reload();    
                        } 
                    });

                });
                
               
            });               
        });

        //Pour cacher ou afficher le div d'autre vaccin
        $("#btnAutreVaccin").click(function(){
           
            $("#divAutreVaccin").show();
            $("#nom_vaccin").focus();
           
        });

    });


    $(function(){

		$("#exemple").DataTable({
            language: {
                processing:     "Traitement en cours...",
                search:         "Rechercher&nbsp;:",
                lengthMenu:    "Afficher _MENU_ éléments",
                info:           "Affichage de l'élement _START_ &agrave; _END_ sur _TOTAL_ éléments",
                infoEmpty:      "Affichage de l'élement 0 &agrave; 0 sur 0 éléments",
                infoFiltered:   "(filtré de _MAX_ éléments au total)",
                infoPostFix:    "",
                loadingRecords: "Chargement en cours...",
                zeroRecords:    "Aucun élément &agrave; afficher",
                emptyTable:     "Aucune donnée disponible dans le tableau",
                paginate: {
                    first:      "Premier",
                    previous:   "Précédent",
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
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","VACCINATION PEDIATRIE");
$smarty->assign("breadcrumbArray", $breadcrumb);



#Affichage de la liste des vaccination
$sql='select care_vaccination_patient.vac_pid, care_vaccination_patient.pid, care_vaccination_patient.vid as vid, care_type_immunization.name as vac_name, care_type_immunization.periode as periode, duree, effectuee, date_exigee, date_estimee, date_effectuee 
	        from care_type_immunization, care_vaccination_patient  
	        where care_type_immunization.nr=care_vaccination_patient.vid and pid='.$pid.' and care_vaccination_patient.type="pediatrie"';

$listes=$db->Execute($sql);

if(is_object($listes) && $listes->recordCount()>0){
	ob_start();
?>	
<center>
    <a href="<?php echo $root_path.'modules/biasa_pdf/print_vaccination_pediatrie.php?pid='.$pid;?>" target="_blank" class="btn btn-sm btn-primary" style="color:#fff;">
       <span class="glyphicon glyphicon-print"></span> Imprimer le calendrier
    </a> 
    <button data-toggle="modal" data-target="#modalForm2" class="btn btn-sm btn-success"> 
    	<span class="glyphicon glyphicon-plus"></span> Nouveau Vaccin
    </button>
</center>
<br>
<table class="table table-responsive table-bordered table-striped-hover" cellpadding="0" cellspacing="0">

    <thead>
    	<tr>
	        <th class="text-center">Vaccination</th>
	        <th class="text-center">Date vaccination</td>
	        <th class="text-center">Date estimée</th>
	        <th class="text-center">Date effectuée</th>
	        <th class="text-center">Etat</th>
	        <th class="text-center">-</th>
	    </tr>
    </thead>

    <tbody>
    <form name="vaccination_patient" method="post" action="view_patient.php">
        <?php $i=0; foreach ($listes as $v) : $i++; ?>

             <tr>
                <td class="text-left">
                    <input type="hidden" name="monid" value="<?php echo $v['pid']; ?>">

                    <input type="hidden" name="vacid" value="<?php echo $v['vid']; ?>">

                    <input type="hidden" name="vacpid" value="<?php echo $v['vac_pid'];?>">

                    <?php echo $v['vac_name'];?>
                </td>
                <td class="text-center">
                    <?php echo date('d-m-Y', strtotime($v['date_exigee']));?>
                </td>
                <td class="text-center">
                    <?php echo date('d-m-Y', strtotime($v['date_estimee']));?>
                </td>
                <td class="text-center">
                    <?php 
                        echo ($v['date_effectuee']==NULL || $v['date_effectuee']=='0000-00-00')?" &nbsp;&nbsp;": date('d-m-Y', strtotime($v['date_effectuee']));
                    ?>
                </td>
              
                <td class="text-center">
                   <?php if($v['effectuee']=='N') :?>
                        
                        <input id="<?php echo "check_".$i;?>" idvac="<?php echo $v['vid']; ?>" vacpid="<?php echo $v['vac_pid']; ?>" name="effectuee" type="checkbox" >
                    
                    <?php else: ?>

                        <input id="<?php echo "check_".$i;?>" idvac="<?php echo $v['vid']; ?>" vacpid="<?php echo $v['vac_pid']; ?>" name="effectuee" type="checkbox" checked="checked" >
                       
                    <?php endif; ?>  
                </td>

                <td class="text-center">
                    <?php $dateEff=($v['date_effectuee']==NULL || $v['date_effectuee']=='0000-00-00')?" ": date('d-m-Y', strtotime($v['date_effectuee']));?>
						
                  	<?php if($v['effectuee']!='N') :?>
                        <input type="button" id="<?php echo 'update_'.$i;?>" idvac="<?php echo $v['vid']; ?>" vacpid="<?php echo $v['vac_pid']; ?>" pid="<?php echo $v['pid']; ?>" vacdate="<?php echo $dateEff;?>" value="Editer" title="Modifier la date effectuée" style="cursor: pointer" class="btn btn-xs btn-success"  data-toggle="modal" data-target="#modalForm">
                    <?php endif;?>
                   

                </td>

            </tr>

        <?php endforeach;?>
    </form>
	</tbody>
</table> 

<?php

	}else{

        //recupérer l'id du patient
        $sql1='select pid, date_birth from care_person where pid='.$pid;
        //recupérer toutes les vaccinations dans la table care_type_vaccination
        $sql2='select nr, name, periode, duree from care_type_immunization';
        $req1=$db->Execute($sql1);
        $req2=$db->Execute($sql2);
        
        $v1=$req1->FetchRow();
        $data2=$req2->GetArray();

        $date_birth=$v1['date_birth'];

      
        $cpt=0; $date_3="";
        //Parcours des vaccinations
        foreach ($data2 as $k2=>$v2) {
            $cpt++;
            
            $periode=intval($v2['periode']); $type=$v2['duree'];
                
            $dateDepartTimestamp=strtotime($date_birth);
           

            if($periode==0){
                $date_exigee=$date_birth;
            }else{

                switch ($type) {
                    case 'semaines':
                        
                        $date_exigee=date('Y-m-d', strtotime('+'.$periode.' week', $dateDepartTimestamp));
                        break;
                    case 'mois':
                        $date_exigee=date('Y-m-d', strtotime('+'.$periode.' month', $dateDepartTimestamp));
                        break;
                    case 'annees':
                        if($cpt==7){
                            //Si je suis dans le cas du rappel
                            //var_dump($date_3);var_dump("je suis ici");die();
                            $dateDepartTimestamp=strtotime($date_3);
                            $date_exigee=date('Y-m-d', strtotime('+'.$periode.' year', $dateDepartTimestamp));
                        }else{
                            $date_exigee=date('Y-m-d', strtotime('+'.$periode.' year', $dateDepartTimestamp));
                        }
                        
                        break;
                    
                    default:
                        
                        break;
                }
               //recupÃ©rer la date du troisiÃ¨me vaccin pour le 7e vaccin
                if($cpt==3){
                    $date_3=$date_exigee;
                }


            }

         
            $dateDepartTimestamp=strtotime($date_exigee);
            $date_estimee=date('Y-m-d', strtotime('+1 week', $dateDepartTimestamp));
           
           //Insertion de chaque vaccination dans la base de donnÃ©es
            $sql3="insert into care_vaccination_patient (pid, vid, date_exigee, date_estimee, type) values(".$v1['pid'].",". $v2['nr'].",'".$date_exigee."','".$date_estimee."', 'pediatrie')";
            $db->Execute($sql3);
          
        
        }  
            
    ?> 

    <br><br>

    <!-- Ce bouton est prévu juste pour actualiser la page -->
	<center>
	    <a href="" onclick="<script>window.location.reload();</script>" class="btn btn-sm btn-success" style="color:#fff;">
	        Génération du calendrier de vaccination <!-- (Mettre le titre et le nom de la personne ici) -->
	    </a> 
	</center>

<?php

	}

	$listing=ob_get_contents();
	ob_end_clean();

	$smarty->assign('listingVaccination', $listing);

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_vaccination_pediatrie.tpl');
?>