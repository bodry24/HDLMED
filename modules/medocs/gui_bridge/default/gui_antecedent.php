<?php  
ob_start();
require_once($root_path.'include/care_api_classes/class_antecedent.php');
$objAntecedent=new Antecedent();

$baseLink=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=antecedent&pid=".$pid;
?>

<link rel="stylesheet" href="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.css';?>"/>
<script type="text/javascript" src="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.js';?>"></script> 
<script type="text/javascript" src="<?php echo $root_path.'js/gadgets/squeezebox.js'; ?>"></script>
<style type="text/css">
	
</style> 
<script type="text/javascript">

    $("#dateEffectuee").datetimepicker({
		format:'dd-mm-yyyy',
		autoclose:true,
		minView:2
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
$breadcrumb=array("DOSSIER MEDICAL","ANTECEDENT");
$smarty->assign("breadcrumbArray", $breadcrumb);


if($objPerson->getValue('sex',$pid)=='f'){
    $smarty->assign('feminin', true);
}

#Traitement des données du formulaire
if(isset($save)){

    $_POST = array_map('addslashes',$_POST);
    extract($_POST);

    if(!isset($obstetrique)) $obstetrique="";
    if(!isset($saisie)) $saisie='1';

    $tab= array( 
        'pid'  => $pid,
        'pere' => $pere,
        'mere' => $mere,
        'fraterie' => $fraterie,
        'medicaux' => $medicaux,
        'gyneco' => $gyneco,
        'obstetrique' => $obstetrique,
        'autres' => $autres,
        'saisie' => $saisie,
        );
    if(isset($code_ante) && !empty($code_ante)){
      
        $history='CONCAT(history," ;","Modifier par '.$userName.' le '.$Time.'")';
        $msg="L'antécédent a été enregistrer avec succès";
        
        $tab['modify_id']=$userPID;
        $tab['modify_time']=date('Y-m-d H:i:s');
         
    }else{
     
        $history="Creer par ".$userName." le ".$Time;
        $msg="L'antécédent a été enregistrer avec succès";

        $tab['create_id']=$userPID;
        $tab['create_time']=date('Y-m-d H:i:s');
    }

    $tab['history']=$history;
  
    //var_dump($tab);die();
    if($objAntecedent->insertFromArray('care_ante_gen',$tab, 'code_ante',@$code_ante)){
        $msgType="success";
    }else{
        $msg="L'antécédent n'a pas été enregistrer";
        $msgType="danger";
    }

    #Affichage du message de confirmation
    $msgArray=array('type'=>$msgType, 'msg'=>$msg);
    $smarty->assign('msgArray',$msgArray);

    header("Location:".$baseLink);

}else{

    //Recuperation de l'antecedent si cela a été saisie
    $antecedent=$objAntecedent->get_ante_gen($pid);
    if(is_object($antecedent) && $antecedent->RecordCount()>0){
        $ligne=$antecedent->FetchRow();
        $code_ante=$ligne['code_ante'];
        if(!isset($option)) {
            $smarty->assign('show', true);
            $smarty->assign('urlUpdate', $baseLink.'&option=edit');

        }
        $smarty->assign('code_ante', $code_ante);
        $smarty->assign('pere', $ligne['pere']);
        $smarty->assign('mere', $ligne['mere']);
        $smarty->assign('fraterie', $ligne['fraterie']);
        $smarty->assign('medicaux', $ligne['medicaux']);
        $smarty->assign('gyneco', $ligne['gyneco']);
        $smarty->assign('obstetrique', $ligne['obstetrique']);
        $smarty->assign('autres', $ligne['autres']);
        $smarty->assign('saisie', $ligne['saisie']);
   
    }
  
}

#Affichage des antécédents
ob_start();

$listing=ob_get_contents();
ob_end_clean();

$smarty->assign('listingAntecedent', $listing);

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_antecedent.tpl');
?>