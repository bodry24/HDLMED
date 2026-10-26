<?php
require_once($root_path.'include/care_api_classes/class_medicalActs.php'); //jboanarges
$objMedical = new MedicalActs();
$doctorInfo = $objMedical->getPatient($objMedical->getUserPid($_SESSION['sess_user_login_id']));
$doctor = $doctorInfo['title'].' '.$doctorInfo['name_last'].' '.$doctorInfo['name_first'];

# Load the letter class
require_once($root_path.'include/care_api_classes/class_letter.php');
$objLetter = new Letter();

# Load the encounter class
require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter($_SESSION['sess_full_en']);
#Load class Person
require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson=new Person();

$baseLink=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=lettre&pid=".$pid;
$Time=date("d/m/Y H:i:s");

$db->Execute("SET NAMES UTF-8");

#Tableau de lettres
$tabLettres = array(
    1 => 'Consultation',
    2 => 'Reponse consultation',
    3 => 'Hospitalisation',
    4 => 'Chirurgie'
);
$cptLettre = count($tabLettres);
$lettresArrayObject=new ArrayObject($tabLettres);
$lettresArrayObject->asort();

ob_start();
	echo '<option value="">Choisir le type de lettre</option>';
	foreach ($lettresArrayObject as $k => $v) {
		$selected=(isset($typeLettre) && $typeLettre==$k)?'selected=selected':"";
		echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
	}
$selectLettres=ob_get_contents();
ob_end_clean();
$smarty->assign("selectOptionsLettres", $selectLettres);

#CSS et JS
ob_start();
?>
<style type="text/css">
	/* div.dataTables_wrapper div.dataTables_filter input {
	    margin-left: 0.5em;
	    display: inline-block;
	    width: 120px;
	}*/
	
</style> 
<script type="text/javascript" src='<?php echo $root_path."js/tinymce/tinymce.min.js";?>'></script>
<script type="text/javascript">
	// Pour le tinymce
    tinymce.init({
        selector: "textarea#body_letter",
        theme: "modern",
        width: 940,
        height: 500,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality emoticons template paste textcolor"
       ],
       //content_css: "css/content.css",
       toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
       style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
     });    

     tinymce.init({
        selector: "textarea#hypothese",
        theme: "modern",
        width: 940,
        height: 150,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality emoticons template paste textcolor"
       ],
       //content_css: "css/content.css",
       toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
       style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
     });    
    tinymce.init({
        selector: "textarea#diagnostic",
        theme: "modern",
        width: 940,
        height: 150,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality emoticons template paste textcolor"
       ],
       //content_css: "css/content.css",
       toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
       style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
     });

     tinymce.init({
        selector: "textarea#preconise",
        theme: "modern",
        width: 940,
        height: 150,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality emoticons template paste textcolor"
       ],
       //content_css: "css/content.css",
       toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
       style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
    });

    tinymce.init({
        selector: "textarea#examen",
        theme: "modern",
        width: 940,
        height: 150,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality emoticons template paste textcolor"
       ],
       //content_css: "css/content.css",
       toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
       style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
     });   

    tinymce.init({
        selector: "textarea#operatoire",
        theme: "modern",
        width: 940,
        height: 150,
        plugins: [
             "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality emoticons template paste textcolor"
       ],
       //content_css: "css/content.css",
       toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
       style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
     });      

    function popPrintSickConfirm(nr, type) {
        urlholder='print_letter.php?sid=<?php echo $sid ?>'+'&lang=<?php echo $lang;?>'+'&get_nr=' + nr + '&tabLettre=' + type;
        drgwin_<?php echo $sid ?>=window.open(urlholder,"drgwin_<?php echo $sid ?>","width=600,height=650,menubar=no,resizable=yes,scrollbars=yes");
    }

    function loadTypeLettre(value){
    	
        if(isNaN(parseInt(value))||value==''){
            alert('Veuillez selectionner un type');
            return false;
        }
        else{
            if(value<=parseInt('<?php echo $cptLettre;?>') && value>=1){
            	$("#type_lettre").val(value);//attribution du type de lettre au formulaire
                document.newform.submit();
            }
        }
        return;
    }

    //Validation du formulaire
    function checkForm(){
    	if($("#type_lettre").val()==0){
    		alert("Veuillez sélectionner le Type de Lettre");
    		$("#type_lettre").focus();
    		return false;
    	}
    	return true;
    }

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

 		$("#date_letter").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

        $("#date_debut").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

		$("#date_fin").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});

		$("#date_sortie").datetimepicker({
			format:'dd/mm/yyyy',
			autoclose:true,
			minView:2
		});


    });
   
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);


#Breadcrumb

if(isset($typeLettre) && $typeLettre>=1){
    $breadcrumb=array("DOSSIER MEDICAL","LETTRES", mb_strtoupper($tabLettres[$typeLettre]));
}else{
    $breadcrumb=array("DOSSIER MEDICAL","LETTRES");
}
$smarty->assign("breadcrumbArray", $breadcrumb);


#Type des champs à afficher
if(isset($_GET['typeLettre']) && !empty($_GET['typeLettre'])){
	$typeLettre=$_GET['typeLettre'];
}

if(isset($typeLettre) && intval($typeLettre)>=1){
	$smarty->assign("typeLettre", $typeLettre);
	$smarty->assign("type_lettre", $typeLettre);
}

#Recuperation d'une lettre
if(isset($_GET['option']) && !empty($_GET['option'])){
	$option=$_GET['option'];
	$nr=$_GET['nr'];

	if(!empty($nr)){
		switch ($option) {
			case 'edit':
				$result=$objLetter->getPhysicianLetter($nr);
			    $letter=$result->FetchRow();

			    $date_letter=$letter['date_letter'];
			    $object_letter=$letter['object_letter'];
			    $sender=$letter['sender'];
			    $receiver=$letter['receiver'];
			    $code_letter=$letter['code_letter'];
			    $location=$letter['location'];
			    $body_letter=$letter['body_letter'];
			    $typeLettre=$letter['letter_type_nr'];
			    	
                
				$smarty->assign('nr', $nr);
				$smarty->assign('date_letter', parseDate($date_letter, "Y-M-D","-","","D/M/Y","/"));
				$smarty->assign('code_letter', $letter["code_letter"]);
				$smarty->assign('location', $letter["location"]);
				$smarty->assign('receiver', $receiver);
                $smarty->assign('body_letter', nl2br($body_letter));
				$smarty->assign('object_letter', $object_letter);

				$smarty->assign("edit", true);
				
			
				break;
			case 'delete':
				if($objLetter->deleteLetter($nr)){
					$msg="La lettre a été supprimée avec succès";
					$msgType="success";
				}else{
					$msg="La suppression n'a pas réussi";
					$msgType="danger";
				}
				break;
			default:
				# code...
				break;
		}
	}
	
}else{
	$smarty->assign('location', 'Lom&eacute;');
	$smarty->assign('date_letter', date('d/m/Y'));
}

//$smarty->assign('selectOptions', $selectOptions);
#Traitement des données du formulaire
if(isset($save)){
	if(isset($nr) && !empty($nr)){

		$history='CONCAT(history," ;","Modifier par $userName le $Time")';
		$msg="La lettre a bien été mise à jour";

	}else{

		$history="Créer par ".$userName." le ".$Time;
		$msg="La lettre a bien été enregistrer";

	}

	$civilite=($objPerson->getValue('sex',$pid)=='m'?'M':'Mme');
    $e=array();
    $e[0]=($objPerson->getValue('sex',$pid)=='m'?'':'e');
    $e[1]=($objPerson->getValue('sex',$pid)=='il'?'':'elle');
 
    $patient=$civilite." ".$objPerson->getValue("name_last",$pid)." ".$objPerson->getValue("name_first",$pid);
    $typeLettre=$_POST['type_lettre'];
    if(!isset($option)){
	    switch ($typeLettre) {
	    	case 1:
	    		$hypothese=$_POST['hypothese'];
		    	$examen=$_POST['examen'];
		    	$body_letter="Je vous remercie de m'avoir adress&eacute; ".$patient.".<br>
		    		Je l'ai examin&eacute; avec beaucoup d'interet. A l'issue de l'examen clinique j'ai &eacute;voqu&eacute; les hypoth&egrave;ses suivantes:".$hypothese."J'ai demand&eacute; les examens compl&eacute;mentaires pour &eacute;tayer le diagnostic: ".$examen."Vous remerciant une fois de plus pour votre confiance, je vous prie de recevoir ch&egrave;r confr&egrave;re en l'assurance de mes sentiments les plus distingu&eacute;s.";
	    		break;
	    	case 2:
	    		$diagnostic=$_POST['diagnostic'];
		    	$preconise=$_POST['preconise'];

		    	$body_letter="Je viens de recevoir les examens compl&eacute;mentaires de ".$patient."<br>A l'issue de ces examens le diagnostic est : ".$diagnostic."Je pr&eacute;conise : ".$preconise."Vous remerciant une fois de plus pour votre confiance, je vous prie de recevoir ch&egrave;r confr&egrave;re en l'assurance de mes sentiments les plus distingu&eacute;s.";
	    		break;
	    	case 3:
	    		$diagnostic=$_POST['diagnostic'];
	    		$operatoire=$_POST['operatoire'];//Motif
		    	$preconise=$_POST['preconise'];//traitement
		    	$date_debut=$_POST['date_debut'];//date entr&eacute;e
		    	$date_fin=$_POST['date_fin'];//date fin
		    	$date_sortie=$_POST['date_sortie'];//date sortie
		    	$body_letter="Votre patient".$e[0]." ".$patient.",a &eacute;t&eacute; hospitalis&eacute; &agrave; la clinique BIASA de Lom&eacute; du ".$date_debut." au ".$date_fin.", pour ".$operatoire."<br>Le diagnostic &agrave; l'entr&eacute;e &eacute;tait : ".$diagnostic."<br>Les explorations compl&eacute;mentaires nous ont permis d'&eacute;tayer le diagnostic et de mettre en route un traitement. L'&eacute;tat du patient a &eacute;t&eacute; jug&eacute; satisfaisant avec sortie le ".$date_sortie.", avec le traitement suivant :".$preconise."Restant &agrave; votre disposition pour d'autres informations compl&eacute;mentaires, nous vous prions de croire cher confr&egrave;re &agrave; l'assurance de nos sentiments les plus cordialement d&eacute;vou&eacute;s.";
	    		break;
	    	case 4:
	    		$diagnostic=$_POST['diagnostic'];
	    		$operatoire=$_POST['operatoire'];//Motif
		    	$preconise=$_POST['preconise'];//traitement
		    	$date_debut=$_POST['date_debut'];//date entr&eacute;e
		    	$date_fin=$_POST['date_fin'];//date fin
		    	$date_sortie=$_POST['date_sortie'];//date de la chirurgie
		    	$chirurgien=$_POST['chirurgien'];//chirurgien
		    	//var_dump($_POST);die();
		    	$body_letter="Votre patient".$e[0]." ".$patient.",a &eacute;t&eacute; hospitalis&eacute; &agrave; la clinique BIASA de Lom&eacute; du ".$date_debut." au ".$date_fin.", pour ".$operatoire."<br>L'intervention chirurgicale a eu lieu le : ".$date_sortie.", par le Dr ".$chirurgien.".<br>Les suites op&eacute;ratoires ont &eacute;t&eacute;: ".$diagnostic."<br>L'&eacute;tat du patient &agrave; ce jour est jug&eacute; satisfaisant et ".$e[1]." sort sous le traitement suivant :".$preconise."Restant &agrave; votre disposition pour d'autres informations compl&eacute;mentaires et vous remerciant pour votre confiance, nous vous prions d'agr&eacute;er cher confr&egrave;re, l'expression de mes sentiments les plus cordialement d&eacute;vou&eacute;s.";
	    		break;
	    	default:
	    		
	    		break;
	    }
	}

	$corps=(isset($option) && $option=='edit')?$objLetter->escapeVarToCreateSqlQuery($_POST['body_letter']):$objLetter->escapeVarToCreateSqlQuery($body_letter);
	//var_dump($corps);die();
	$date_letter=$_POST['date_letter'];

	$tab= array( 
	 		'letter_type_nr'=> $_POST['type_lettre'],
            'date_letter' =>parseDate($date_letter,"D/M/Y","/","","Y-M-D","-"),
            'patient_nr' => $pid,
            'history' =>$history,
            'modify_id' => $_SESSION['sess_user_name'],
            'create_id' => $_SESSION['sess_user_name'],
            'create_time' => date('YmdHis'),
            'code_letter' =>  $objLetter->escapeVarToCreateSqlQuery($_POST['code_letter']),
            'body_letter' => $corps,
            'object_letter' => $objLetter->escapeVarToCreateSqlQuery($_POST['object_letter']),
            'sender' =>  $objLetter->escapeVarToCreateSqlQuery($_POST['sender']),
            'receiver' =>  $objLetter->escapeVarToCreateSqlQuery($_POST['receiver']),
            'location' =>  $objLetter->escapeVarToCreateSqlQuery($_POST['location']),  
            'pid_create' => $doctorInfo['pid']
       	);
    // var_dump($tab);die();
	//var_dump($objLetter->insertFromArray('care_letter',$tab, 'nr',@$nr));die();
	if($objLetter->insertFromArray('care_letter',$tab, 'nr',@$nr)){
		$msgType="success";
	}else{
		$msg="La lettre n'a pas été enregistrer";
		$msgType="danger";
	}

	header("Location:".$baseLink);
}

#Affichage du message si il y en a 
if(isset($msg) && !empty($msg)){
	$smarty->assign("msg", $msg);
	$smarty->assign("msgType", $msgType);
}

#Affichage des lettres et nouvelle lettre
$urlAfficheLettre=$baseLink."&option=affichage";
$smarty->assign('urlAfficheLettre', $urlAfficheLettre);

$urlNouvelleLettre=$baseLink;
$smarty->assign('urlNouvelleLettre', $urlNouvelleLettre);

//if($option=="affichage"){
	ob_start();
	$listeLettres=$objLetter->allPhysicianLetter($pid, $doctorInfo['pid']);
	
	if(is_object($listeLettres)){

		$i=0;
		while ($ligne=$listeLettres->FetchRow()) {
			
		    $i++;
            $date=parseDate($ligne['date_letter'], "Y-M-D", "-","","D/M/Y","/");
            $nr=$ligne['nr']; 
            $object=$ligne['object_letter']; 
            $save_by = $ligne['create_id'];
            $receiver = $ligne['receiver'];
            $letter_type = $tabLettres[$ligne['letter_type_nr']];
            

            $linkPrint=$root_path."modules/medocs/print_lettre_pdf.php?nr=".$nr."&letter_type_nr=".$ligne['letter_type_nr'];
            $linkEdit=$baseLink."&option=edit&nr=$nr&typeLettre=".$ligne['letter_type_nr'];
            $linkDelete=$baseLink."&option=delete&nr=$nr";

            $print_link ='<a href='.$linkPrint.' target="_blank" class="btn btn-xs btn-primary" title="Imprimer" style="color:white"><i class="glyphicon glyphicon-print"></i></a>';
            $edit_link='<a href='.$linkEdit.' class="btn btn-xs btn-success" title="Editer"><i class="glyphicon glyphicon-edit" style="color:white"></i></a>';
            $delete_link='<a href='.$linkDelete.' class="btn btn-xs btn-danger" onclick="return confirm(\'Etes vous sur?\')" title="Supprimer"><i class="glyphicon glyphicon-trash" style="color:white"></i></a>';
      		
            echo '<tr>
                    <td class="text-right">'.$i.'</td>
                    <td class="text-center">'.$date.'</td>
                    <td>'.$object.'</td>
                    <td>'.$save_by.'</td>
                    <td>'.$receiver.'</td>
                    <td>'.$letter_type.'</td>
                    <td>
                        '.$edit_link.'
                        '.$print_link.'
                        '.$delete_link.'                       
                    </td>
                  
                </tr> ';
		}

	}
	
	$listingLettres=ob_get_contents();	
	ob_end_clean();
	
	$smarty->assign("listingLettres", $listingLettres);
//}

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_lettres.tpl');
?>

