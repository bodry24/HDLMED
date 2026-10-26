<?php
require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter;

#Voir si un CPN est ouvert dans la base
$sqlCpn="SELECT * FROM care_medocs_cpn_declaration WHERE pid=".$pid." AND status='O' ORDER BY id DESC";

if($resultCpn=$db->Execute($sqlCpn)){
    $rsCpn=$resultCpn->FetchRow();
    $idCpn=$rsCpn['id'];
}

#Assignation de l' id du CPN
if(isset($idCpn)&& !empty($idCpn)){
    $smarty->assign('idCpn', $idCpn);
}
$sessionIdCpn=$_SESSION['idCPN'];
if(isset($sessionIdCpn)&&!empty($sessionIdCpn)){
    $idCpn=$sessionIdCpn;
}

#Verification si les paramètres avait été saisies pour ce CPN
function getParametresCpn($id_cpn=null){
    global $db;
    $sqlCpn="SELECT * FROM care_medocs_cpn_parametres WHERE id_cpn=".$id_cpn." AND valide_status='O'";
    if($resultCpn=$db->Execute($sqlCpn)){
        $rsCpn=$resultCpn->FetchRow();
        return $rsCpn;
    }else{
        return false;
    }
}

#Verification si un examen avait été saisies pour ce CPN
function getExamenCpn($id_cpn=null){
    global $db;
    $sqlCpn="SELECT * FROM care_medocs_cpn_examens WHERE id_cpn=".$id_cpn." AND valide_status='O'";
    if($resultCpn=$db->Execute($sqlCpn)){
        $rsCpn=$resultCpn->FetchRow();
        return $rsCpn;
    }else{
        return $sqlCpn;
    }
}


#Verification si un examen avait été saisies pour ce CPN
function getVisiteCpn($id_cpn=null){
    global $db;
    $sqlCpn="SELECT * FROM care_medocs_cpn_visite_poste_natale WHERE id_cpn=".$id_cpn." AND valide_status='O'";
    if($resultCpn=$db->Execute($sqlCpn)){
        $rsCpn=$resultCpn->FetchRow();
        return $rsCpn;
    }else{
        return $sqlCpn;
    }
}

#Verification si un examen avait été saisies pour ce CPN
function getHospitalisationCpn($id_cpn=null){
    global $db;
    $sqlCpn="SELECT * FROM care_medocs_cpn_hospitalisation WHERE id_cpn=".$id_cpn." AND valide_status='O'";
    if($resultCpn=$db->Execute($sqlCpn)){
        $rsCpn=$resultCpn->FetchRow();
        return $rsCpn;
    }else{
        return $sqlCpn;
    }
}

#Recupration d'une note 
function getNoteCpn($id_cpn=null){
    global $db;
    $sqlCpn="SELECT * FROM care_medocs_cpn_note WHERE id_cpn=".$id_cpn." AND valide_status='O'";
    if($resultCpn=$db->Execute($sqlCpn)){
        $rsCpn=$resultCpn->FetchRow();
        return $rsCpn;
    }else{
        return $sqlCpn;
    }
}

#Recupration d'une note 
function getSurveillanceCpn($id_cpn=null){
    global $db;
    $sqlCpn="SELECT * FROM care_medocs_cpn_surveillance WHERE id_cpn=".$id_cpn." AND valide_status='O'";
    //debug($sqlCpn);die();
    if($resultCpn=$db->Execute($sqlCpn)){
        $rsCpn=$resultCpn->FetchRow();
        return $rsCpn;
    }else{
        return $sqlCpn;
    }
}

#Recupration d'un compte rendu
function getCompteRenduCpn($id_cpn=null){
    global $db;
    $sqlCpn="SELECT * FROM care_medocs_cpn_compte_rendu WHERE id_cpn=".$id_cpn." AND valide_status='O'";
    if($resultCpn=$db->Execute($sqlCpn)){
        $rsCpn=$resultCpn->FetchRow();
        return $rsCpn;
    }else{
        return $sqlCpn;
    }
}

#function getObjectCpn
function getArrayCpn($matable, $id_cpn){
    global $db;
    $sqlCpn="SELECT * FROM ".$matable." WHERE id_cpn=".$id_cpn." AND valide_status='O'";
    if($resultCpn=$db->Execute($sqlCpn)){
        $rsCpn=$resultCpn->FetchRow();
        return $rsCpn;
    }else{
        return false;
    }
}

function maSerialize($champs, $valeur=""){
    $champs=unserialize($champs);
    if(is_string($champs)){
        $champs=array($champs);
    }
    array_push($champs, addslashes(strval($valeur)));
    $champs=addslashes(serialize($champs));
    return $champs;
}

function maSerializeSimple($valeur=""){
    $valeur=serialize($valeur);
    return addslashes($valeur);
}

$baseLink=$root_path."modules/medocs/medocs.php".URL_APPEND."&target=cpn&pid=".$pid;

#CSS et JS
ob_start();
?>

<link rel="stylesheet" href="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.css';?>"/>
<script type="text/javascript" src="<?php echo $root_path.'js/jquery/jquery-ui-1.9.1.custom.min.js';?>"></script> 
<script type="text/javascript" src="<?php echo $root_path.'js/gadgets/squeezebox.js'; ?>"></script>
<style type="text/css">
	.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
       /*  background-color: #00009C; */
        background-color: #02327E;
        color: #fff;
        border-color: #02327E;
        border-image: none;
        border-style: solid;
        border-width: 1px;
        cursor: default;
    }

</style> 
<script type="text/javascript" src='<?php echo $root_path."js/tinymce/tinymce.min.js";?>'></script>
<script type="text/javascript">
    //Pour le tinymce
    tinymce.init({
        selector: "textarea#myTiny",
        theme: "modern",
        width: 940,
        height: 400,
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

    $(function(){
       
        $("#date_debut").datetimepicker({
			format:'dd-mm-yyyy hh:ii',
			autoclose:true,
			todayBtn: true,
            minuteStep:1
		});

        $("#date_fin").datetimepicker({
			format:'dd-mm-yyyy hh:ii',
			autoclose:true,
			todayBtn: true,
            minuteStep:1
		});

        //Parcour des input de type date avec attribution de select
        $("input[name^='date_']").each(function(){
            var inp=$(this).attr('id');
            //alert(inp);
            $("#"+inp).datetimepicker({
                format:'dd/mm/yyyy',
                autoclose:true,
                minView:2
            }); 
        })

    }); 
</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript', $js);

#Breadcrumb
$breadcrumb=array("DOSSIER MEDICAL","CONSULTATION PRE-NATALE");
$smarty->assign("breadcrumbArray", $breadcrumb);

$tabMenus=array(
        'declaration_grossesse'=>'DECLARATION DE GROSSESSE',
        'parametre_cpn'=>'PARAMETRES', 
        'examens_cpn'=>'EXAMENS',
        'hospitalisation_cpn'=>'HOSPITALISATION',
        'surveillance_cpn'=>'SURVEILLANCE',
        'note_cpn'=>'NOTE',
        'nouveau_ne_cpn'=>'NOUVEAU NE',
        'visite_poste_natale_cpn'=>'VISITE POSTE NATALE',
        'compte_rendu_cpn'=>'COMPTE RENDU',
    );

ob_start();

?>

<ul class="nav nav-tabs">
    <?php
        if(!isset($cpn) || $cpn=='') $cpn = 'declaration_grossesse';
        foreach ($tabMenus as $k=>$v) {
      
            if($cpn==$k){
                $active = "active";
            }else{
                $active = " ";
            }
       
            echo '<li role="presentation" class="'.$active.'">';
            echo '<a class="item '.$active.'" href="' . $baseLink. '&cpn=' . $k . '" title="'.$v.'">'.$v.'</a>';
            echo '</li>';
        }
    ?>
</ul>

<?php

$sMenus=ob_get_contents();
ob_end_clean();

$smarty->assign('menus', $sMenus);

require_once($root_path.'modules/medocs/gui_bridge/default/gui_'.$cpn.'.php');

//var_dump('gui_'.$cpn.'.php');die();

$smarty->assign('sMainBlockIncludeFile','medocs/medocs_declaration_cpn.tpl');

?>