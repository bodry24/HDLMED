
<?php ob_start();

    echo '<script type="text/javascript" src="'.$root_path.'gui/semantic/moment.min.js"></script>';
    echo '<script type="text/javascript" src="'.$root_path.'gui/semantic/daterangepicker.min.js"></script>';
 
    echo '<link rel="stylesheet" type="text/css" href="'.$root_path.'gui/semantic/daterangepicker.min.css">';

?>

<script type="text/javascript">
   // alert('ok');
    $(function(){

        //affichage du form modal
       /* $("#btnmodal").click(function(){
            //alert('cool');
            var madate=$('#date_effectuee').val();
            $('#dateEff').val()=madate;

            $('.ui.modal').modal('show');
        });*/

        $("#frmSubmit").click(function(){
            //alert('cool');
            $('.ui.modal').modal('hide');
           
        });

    }); 
         
</script>

<?php
    $js=ob_get_contents();
    ob_end_clean();
    $smarty->append('JavaScript', $js);
?>


<div class="ui modal" style="width:50%;padding:0px 10px 0px 10px;text-align:center;">
    <i class="close icon"></i>
    <div class="header">
        <center>MISE A JOUR VACCINATION</center>
    </div>  
    <form id="frmMaj" name="frmMaj" method="POST" class="ui form">
        
        <div class="content">
            <div class="two fields">

                <div class="field">
                    <label>Date</label> 

                    <input type="hidden" name="updateVac" id="updateVac">                  
                 
                    <div class="ui left icon input">
                          <input type="text" name="date_effectuee" id="updateDate">
                          <i class="calendar icon"></i>
                    </div>
                  
                </div>
                <div class="field">              
                    <label>&nbsp;</label>
                    <input id="frmSubmit" type="submit" class="ui blue button" value="Valider">                  
                </div>
                
            </div>      
        </div>
        
    </form>
</div>


<div style="text-align:left;">

<?php   
    if($rows==1):
?>

<?php 
    $donnees=$result;  
?>

<br>

<table class="ui blue table" cellpadding=4 cellspacing=1 width=100%>

    <tr style="background:#99CCFF;">
        <td style="padding:10px 0px 5px 5px;">Vaccination</td>
        <td  style="padding:10px 0px 5px 5px;">Date vaccination</td>
        <td  style="padding:10px 0px 5px 5px;"><?php echo utf8_decode('Date estimÃ©e');?></td>
        <td  style="padding:10px 0px 5px 5px;"><?php echo utf8_decode('Date effectuÃ©e');?></td>
        <td  style="padding:10px 0px 5px 5px;">Etat</td>
    </tr>
    <?php// $donnees=$row;?>
    <form name="vaccination_patient" method="post" action="view_patient.php">
    <?php $i=0;?>
        <?php foreach ($donnees as $v) : ?>
            <?php $i++; ?>
            <tr>
                <td style="padding:5px 0px 5px 5px;">
                    <input type="hidden" name="monid" value="<?php echo $v['pid']; ?>">

                    <input type="hidden" name="vacid" value="<?php echo $v['vid']; ?>">

                    <input type="hidden" name="vacpid" value="<?php echo $v['vac_pid'];?>">

                    <?php echo $v['vac_name'];?>
                </td>
                <td style="padding:5px 0px 5px 5px;">
                    <?php echo date('d-m-Y', strtotime($v['date_exigee']));?>
                </td>
                <td style="padding:5px 0px 5px 5px;">
                    <?php echo date('d-m-Y', strtotime($v['date_estimee']));?>
                </td>
                <td style="padding:5px 0px 5px 5px;">

                    <?php 
                        if($v['date_effectuee']!='0000-00-00'){
                            echo date('d-m-Y', strtotime($v['date_effectuee']));
                        }
                    ?>
                  
                    
                </td>         
                <td style="padding:5px 0px 5px 5px;">

                   <?php if($v['effectuee']=='N') :?>
                        
                        <input id="<?php echo "check_".$i;?>" idvac="<?php echo $v['vid']; ?>" vacpid="<?php echo $v['vac_pid']; ?>" vacdate="<?php echo date('d/m/Y', strtotime($v['date_effectuee']));?>" name="effectuee" type="checkbox" >
                     <!-- onclick="return confirm('A t il bien recu ce vaccin?')" -->
                    <?php else: ?>

                        <input id="<?php echo "check_".$i;?>" idvac="<?php echo $v['vid']; ?>" vacpid="<?php echo $v['vac_pid']; ?>" vacdate="<?php echo date('d/m/Y', strtotime($v['date_effectuee']));?>" name="effectuee" type="checkbox" checked="checked" >
                       <!--  RecupÃ©rer aussi le primary key de la vaccination effectuÃ©e du patient -->
                        

                    <?php endif; ?> 

                  
                </td>
            </tr>

       <?php endforeach;?>

      
        
    </form>

</table> 

<script>

        var etatCheckbox; //RecupÃ©rer l'Ã©tat du checkbox
       
        $(document).ready(function(){
            $('[id*=check_]').each(function(i){
                var patientId=$("[name=monid]").val();
        

                $(this).click(function(){
                    //l'id de la vaccination
                    var idVac=$(this).attr('idvac');
                    //l'id dans care_vaccination_patient
                    var vacPid=$(this).attr('vacpid');
                    //Date vaccination
                    var vacDate=$(this).attr('vacdate');
                    //Donner la valeur 0 ou 1 Ã  etatcheckbox
                    if($(this).is(':checked')){
                       // alert('ok');  
                        etatCheckbox=1; 
                        //Pour élever 
                        $.get("<?php echo $root_path.'/modules/medocs/gui_bridge/default/gui_input_vac_pediatrie.php';?>",
                            {monid:patientId,vacid:idVac,etatcheck:etatCheckbox,vacpid:vacPid},
                            function(data){        
                            //alert('ok');

                            window.location.reload();

                            if(data){
                                alert(data);
                                //$(this).attr("disabled","disabled");
                            } 
                        });

                    }else{

                        etatCheckbox=0;
                        //alert(vacDate);
                        $('#updateVac').val(vacPid);
                        $('#updateDate').val(vacDate);
                        //Appel du date picker
                        $('#updateDate').daterangepicker({
                            singleDatePicker:true,
                            format:'DD/MM/YYYY'
                        });
                        //appel du form modal
                        $('.ui.modal').modal('show');
                        //on commence Ã  faire le traitement
                       /* $.get("<?php echo $root_path.'/modules/medocs/gui_bridge/default/gui_input_vac_pediatrie.php';?>",
                            {dateVac:datevac,vacpid:vacPid},
                            function(data){        
                            
                            window.location.reload();

                            if(data){
                                alert(data);
                                //$(this).attr("disabled","disabled");
                            } 
                        });*/

                    }

                    //alert(vacPid);
                    /* Faire le traitement en passant les variables(
                    l'id du patient,l'Ã©tat du checkbox et l'id du vaccin)*/
            
                });  
                //Au clique sur valider on fait ce traitement

                $("#frmSubmit").click(function(){
                    //var data=$("#frmMaj").serialize();
                    var vacpid=$("#updateVac").val();
                    var vacdate=$("#updateDate").val();
                   
                    //alert("<?php echo $root_path.'/modules/medocs/gui_bridge/default/gui_input_vac_pediatrie.php';?>");

                   alert(vacdate);
                }); 

            });
        });

</script>

<?php else: ?>
    <!-- on gÃ©nÃ¨re le calendrier des vaccinations -->
    <?php
        //recupÃ©rer l'id du patient
        $sql1='select pid, date_birth from care_person where pid='.$pid;
        //recupÃ©rer toutes les vaccinations dans la table care_type_vaccination
        $sql2='select nr, name, periode, duree from care_type_immunization where type_vac="pediatrie"';
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
                            $dateDepartTimestamp=strtotime($date_3);
                            $date_exigee=date('Y-m-d', strtotime('+'.$periode.' year', $dateDepartTimestamp));
                        }else{
                            $date_exigee=date('Y-m-d', strtotime('+'.$periode.' year', $dateDepartTimestamp));
                        }
                        
                        break;
                    
                    default:
                        
                        break;
                }
               //recupÃ©rer la date du troisiÃ¨me vaccin pour le 7e
                if($cpt==3){
                    $date_3=$date_exigee;
                }else{
                    $date_3="";
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

    <!-- Ce bouton est prÃ©vu juste pour actualiser la page -->
    <img <?php echo createComIcon($root_path,'bul_arrowgrnlrg.gif','0','absmiddle'); ?>>
    <a href="" onclick="<script>window.location.reload();</script>">
        <?php   echo utf8_decode('GÃ©nÃ©rer le calendrier des vaccinations de : '.$title.' '.$name_last); 
        ?>
    </a> 


<?php endif;?>

</div>
