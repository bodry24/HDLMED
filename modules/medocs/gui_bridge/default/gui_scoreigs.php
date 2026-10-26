<?php
ini_set("display_errors",true);
global $db;
$db->Execute("SET NAMES UTF8");

require_once($root_path.'include/core/inc_front_chain_lang.php');
$core = & new Core;

require_once($root_path.'include/care_api_classes/class_encounter.php');
$objEncounter=new Encounter();

require_once($root_path.'include/care_api_classes/class_person.php');
$objPerson = new Person();

require_once($root_path.'modules/caisse/utils.php');

$userPID=$objPerson->getUserPid($login);
$userName=addslashes($objPerson->getValue('name_last',$userPID).' '.$objPerson->getValue('name_first',$userPID));
$Time=date('Y-m-d H:i:s');

$encounter_nr=$objEncounter->isPIDCurrentlyAdmitted($pid);
#CSS et JS
ob_start();
?>
<style type="text/css">
    .text-white{
        font-color:white;
    }

    .background{
        color:white;
        background: #00009C;
        font-size: 18px;
    }
</style>
<script type="text/javascript">
    
    $(document).ready(function(){
         // Total du SCORE IGS II
       /* $("input[type=radio]").on("click",function(){
            //alert($(this).val());
            calcTotal();
        });*/
    });

    function calcTotal(){
        var total=0;
        $(".champ").each(function(i){
            total=total+parseInt($(this).val());
        });
        $("#igs2").val(total);

        // Pour le logit
        var logit=-7.7631+0.0737*total+0.9971*Math.log(total+1);
        $("#logit").val(logit);

        var mortalite=Math.ceil((Math.exp(logit)/(1+Math.exp(logit)))*100);
        $("#mortalite").val(mortalite);
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


        $("#date").datetimepicker({
            format:'dd/mm/yyyy',
            autoclose:true,
            minView:2
        });

        var totalSum=0;
        
        $(".age").click(function(){
            
            $("input[name='age']").val($(this).val());
            calcTotal();

        })
        $(".frequence").click(function(){
           
            $("input[name='frequence']").val($(this).val());
            calcTotal();
           
        })
        $(".pression").click(function(){
           
            $("input[name='pression']").val($(this).val());
            calcTotal();
        })
        $(".temperature").click(function(){
           
            $("input[name='temperature']").val($(this).val());
            calcTotal();
        })
        $(".rapport").click(function(){
           
            $("input[name='rapport']").val($(this).val());
            calcTotal();
        })   

        $(".diurese").click(function(){
           
            $("input[name='diurese']").val($(this).val());
            calcTotal();
        }) 
        $(".uree").click(function(){
           
            $("input[name='uree']").val($(this).val());
            calcTotal();
        })
        $(".leucocyte").click(function(){
           
            $("input[name='leucocyte']").val($(this).val());
            calcTotal();
        })
        $(".kaliemie").click(function(){
           
            $("input[name='kaliemie']").val($(this).val());
            calcTotal();
        })
        $(".natremie").click(function(){
           
            $("input[name='natremie']").val($(this).val());
            calcTotal();
        })  

        $(".hco3").click(function(){
           
            $("input[name='hco3']").val($(this).val());
            calcTotal();
        })
        $(".bilirubine").click(function(){
           
            $("input[name='bilirubine']").val($(this).val());
            calcTotal();
        })
        $(".glasgow").click(function(){
           
            $("input[name='glasgow']").val($(this).val());
            calcTotal();
        })
        $(".maladie").click(function(){
           
            $("input[name='maladie']").val($(this).val());
            calcTotal();


        })
        $(".type_admission").click(function(){
           
            $("input[name='type_admission']").val($(this).val());
            calcTotal();
        })

       

    });
   
</script>
<?php
$js=ob_get_contents();
ob_end_clean();

$smarty->append('JavaScript', $js);

#BreadCrumbs
$breadcrumb=array('DOSSIER MEDICAL', "SCORE IGS II");
$smarty->assign('breadcrumbArray', $breadcrumb);
ob_start();

$content=ob_get_contents();
ob_get_clean();
$smarty->assign('content', $content);

# Remise à zéro des champs
$smarty->assign('idVal',0);
$smarty->assign('age',0);
$smarty->assign('frequence',0);
$smarty->assign('pression',0);
$smarty->assign('temperature',0);
$smarty->assign('rapport',0);
$smarty->assign('diurese',0);
$smarty->assign('uree',0);
$smarty->assign('leucocyte',0);
$smarty->assign('kaliemie',0);
$smarty->assign('natremie',0);
$smarty->assign('hco3',0);
$smarty->assign('bilirubine',0);
$smarty->assign('glasgow',0);
$smarty->assign('maladie',0);
$smarty->assign('type_admission',0);
$smarty->assign('aujourdhui', date('d/m/Y'));
$smarty->assign('encounterNr', $encounter_nr);
$smarty->assign('igs2', 0);
$smarty->assign('mortalite', 0);
$smarty->assign('logit', 0);

# Affichage des messages d'information
if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
    $msgArray=array('type'=>$_SESSION['msgType'], 'msg'=>$_SESSION['msg']);
    $smarty->assign('msgArray', $msgArray);
    $_SESSION['msgType']="";
    $_SESSION['msg']="";
}else{
    $_SESSION['msgType']="";
    $_SESSION['msg']="";
}

# Modification du score
if(isset($_GET['id'])&&!empty($_GET['id'])){
    $id=$_GET['id'];
    switch ($option) {
        case 'editer':
            $sqlEdit="SELECT * FROM care_scoreigs WHERE valide_status='O' AND id=".$id;
            if($resulEdit=$db->Execute($sqlEdit)){
                $rs=$resulEdit->FetchRow();
                $smarty->assign('idVal', $rs['id']);
                $smarty->assign('age',$rs['age']);
                $smarty->assign('frequence',$rs['frequence']);
                $smarty->assign('pression',$rs['pression']);
                $smarty->assign('temperature',$rs['temperature']);
                $smarty->assign('rapport',$rs['rapport']);
                $smarty->assign('diurese',$rs['diurese']);
                $smarty->assign('uree',$rs['uree']);
                $smarty->assign('leucocyte',$rs['leucocyte']);
                $smarty->assign('kaliemie',$rs['kaliemie']);
                $smarty->assign('natremie',$rs['natremie']);
                $smarty->assign('hco3',$rs['hco3']);
                $smarty->assign('bilirubine',$rs['bilirubine']);
                $smarty->assign('glasgow',$rs['glasgow']);
                $smarty->assign('maladie',$rs['maladie']);
                $smarty->assign('type_admission',$rs['type_admission']);
                $smarty->assign('aujourdhui',parseDate($rs['date'], "Y-M-D","-", "", "D/M/Y","/"));
                $smarty->assign('encounterNr',$rs['encounter_nr']);
                $smarty->assign('igs2',$rs['igs2']);
                $smarty->assign('logit',$rs['logit']);
                $smarty->assign('mortalite',$rs['mortalite']);
            }
            break;
        case 'delete':
            $sqlDelete="UPDATE care_scoreigs SET valide_status='N' WHERE id=".$id;
            if($db->Execute($sqlDelete)){
                $msgType="success";
                $msg="La suppression a été effectuée avec succès !";
            }else{
                $msgType="danger";
                $msg="La suppression a échoué !";
            }
            $_SESSION['msgType']=$msgType;
            $_SESSION['msg']=$msg;

            header("Location:".$thisfile."&target=scoreigs");
            break;
        default:
            # code...
            break;
    }
}

# Enregistrement dans la base
if(isset($_POST) && !empty($_POST)){
    $tab=array(
        'age'=>$age,
        'frequence'=>$frequence,
        'pression'=>$pression,
        'temperature'=>$temperature,
        'rapport'=>$rapport,
        'diurese'=>$diurese,
        'uree'=>$uree,
        'leucocyte'=>$leucocyte,
        'kaliemie'=>$kaliemie,
        'natremie'=>$natremie,
        'hco3'=>$hco3,
        'bilirubine'=>$bilirubine,
        'glasgow'=>$glasgow,
        'maladie'=>$maladie,
        'type_admission'=>$type_admission,
        'date'=>parseDate($date, "D/M/Y","/",'',"Y-M-D","-"),
        'encounter_nr'=>$encounter_nr,
        'logit'=>$logit,
        'mortalite'=>$mortalite,
        'igs2'=>$igs2,
    );

    if(isset($_POST['id']) && !empty($_POST['id'])){
        $id=$_POST['id'];
        $tab['modify_id']=$userPID;
        $tab['modify_time']=$Time;
        $tab['history']="CONCAT(history, '; Modifier par ".$userName." le ".$Time."')";
    }else{
        $id="";
        $tab['create_id']=$userPID;
        $tab['create_time']=$Time;
        $tab['history']="Creer Par ".$userName." le ".$Time;
    }

    if($core->insertFromArray("care_scoreigs", $tab, 'id', $id)){
        $msg="SCORE IGS II enregistré avec succès !";
        $msgType="success";
       
    }else{
        $msg="L'enregistrement a échoué !";
        $msgType="danger";
    }

    $_SESSION['msgType']=$msgType;
    $_SESSION['msg']=$msg;
    header("Location:".$thisfile."&target=scoreigs");
}

# Liste des Scores IGS II
$sql="SELECT * FROM care_scoreigs WHERE valide_status='O' ORDER BY date DESC";
$result=$db->Execute($sql);
if(is_object($result)){
    ob_start();
    $i=0;
    while($ligne=$result->FetchRow()){
        $i++;
        $actions='<a href="'.$thisfile.'&target=scoreigs&option=editer&id='.$ligne['id'].'" class="btn btn-xs btn-success" style="color:white;" title="Editer"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;<a href="'.$thisfile.'&target=scoreigs&option=delete&id='.$ligne['id'].'" class="btn btn-xs btn-danger" style="color:white;" title="Annuler" onclick="return confirm(\'Etes vous sur?\')"><span class="glyphicon glyphicon-trash"></span></a>';
        echo '<tr>';
            echo '<td class="text-right">'.$i.'</td>';
            echo '<td class="text-center">'.parseDate($ligne['date'], "Y-M-D","-","","D/M/Y","/").'</td>';
            echo '<td class="text-right">'.$ligne['age'].'</td>';
            echo '<td class="text-right">'.$ligne['frequence'].'</td>';
            echo '<td class="text-center">...</td>';
            echo '<td class="text-center">'.$actions.'</td>';
        echo '</tr>';
    }
    $listing=ob_get_contents();
    ob_clean();
    $smarty->assign('listingScore', $listing);
}



$smarty->assign('sMainBlockIncludeFile', 'medocs/medocs_scoreigs.tpl');

?>