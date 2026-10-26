<?php 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//require('../../roots.php');
$root_path='../../../../../';
require($root_path.'include/core/inc_environment_global.php');


global $db;

switch ($action) {

  case 'insert_vaccin':
      //global $db;
      $Time=date('Y-m-d H:i:s');
      $user_pid=$_SESSION['sess_user_login_id'];
      $nom_vaccin=addslashes($nom_vaccin);
      $periode_vaccin=intval($periode_vaccin);
      $type_periode=intval($type_periode);
      $rappel_vaccin=intval($rappel_vaccin);
      //var_dump($_SESSION);die();
      //Je fais l'insertion du vaccin dans care_type_immunization
      $sqlVaccin="INSERT INTO care_type_immunization(type,name,periode,duree,rappel,create_id,create_time) VALUES('VACCIN','".$nom_vaccin."',$periode_vaccin, '".$type_periode."', $rappel_vaccin,'$user_pid', '".$Time."')";

     // var_dump($db->Execute($sqlVaccin));die();
      if($resultVaccin=$db->Execute($sqlVaccin)){
          $nr=$db->insert_id();
          //Je recupère tous les patients dont on a générer la vaccination puis j'ajoute le nouveau vaccin dans la liste
          $sqlPatient="SELECT distinct(p.pid) as pid, p.date_birth as date_birth FROM care_vaccination_patient v, care_person p WHERE p.pid=v.pid";
          if($resultPatient=$db->Execute($sqlPatient)){

            while($ligne=$resultPatient->FetchRow()){
            
                $pid=$ligne['pid'];// le pid du patient
                $date_birth=$ligne['date_birth'];//la date de naissance 

                $periode=intval($periode); //en nombre
                $type=$type_peride;//la durée genre annees, mois, semaines
                
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
                          
                              $date_exigee=date('Y-m-d', strtotime('+'.$periode.' year', $dateDepartTimestamp));                                 
                            break;
                        
                        default:
                            
                            break;
                    }

                }

             
                $dateDepartTimestamp=strtotime($date_exigee);
                $date_estimee=date('Y-m-d', strtotime('+1 week', $dateDepartTimestamp));
               
               //Insertion de chaque vaccination dans la base de donnÃ©es
                $sql3="insert into care_vaccination_patient (pid, vid, date_exigee, date_estimee, type) values(".$pid.",". $nr.",'".$date_exigee."','".$date_estimee."', 'pediatrie')";
                //var_dump($sql3);die();
                $db->Execute($sql3);

              }

          }else echo $sqlPatient;

          echo "Le vaccin a bien ete ajoute";

      }else echo $sqlVaccin;

     
    break;
  case 'update':
        $updateDate=date('Y-m-d', strtotime($updateDate));//Je fais la conversion au format mysql
        //Je procède comme suit à la mise à jour dans la base
        $sqlUpdate="UPDATE care_vaccination_patient SET date_effectuee='".$updateDate."' WHERE vac_pid=".$updateVacPid;
        if($db->Execute($sqlUpdate)){
            echo "Vaccin mise à jour !");
        }else echo $sqlUpdate;

        break;
  
  default:
       if(isset($_GET['monid']) && isset($_GET['vacid']) && isset($_GET['etatcheck'])){

            $id=(int) $_GET['monid'];
            $vacid=(int) $_GET['vacid'];
            $etatcheck=(int) $_GET['etatcheck'];

            if($etatcheck==1){

               //Recupérer l'utilisateur
                $modify_id=$_SESSION['sess_user_name'];
                $create_id=$_SESSION['sess_user_name'];
                $create_time=NULL;
                //$modify_time=NULL;

                $history="Created: ".date('Y-m-d H:i:s')." : ".$_SESSION['sess_user_name']."\n";
                //fin de la recupération


                 $response=$db->Execute("update care_vaccination_patient set effectuee='O', date_effectuee='".date('Y-m-d')."', modify_id='".$modify_id."', history='".$history."', create_id='".$create_id."' where pid=".$id." and vid=".$vacid);

                //$patient=$response->fetch(); 

                /****Si ce vaccin s'effectue sur une période donnée,
                ---Recupérer le champ rappel----
                ---Faites une nouvelle insertion dans la base******/
                $sql2="select rappel from care_type_immunization where nr=".$vacid;    
                $req1=$db->Execute($sql2); 

                $v1=$req1->FetchRow();
                $rappel=intval($v1['rappel']);


                if($rappel>0){

                    //$req2=$db->Execute("select id,MAX(date_exige) from care_vaccination_patient where pid=".$id." and vid=".$vacid);
                    //$v2=$req2->GetArray();
                    
                    $req2=$db->Execute("select pid, vid, date_exigee from care_vaccination_patient where pid=".$id." and vid=".$vacid);
                    $v3=$req2->GetArray();
                   
                    $v2=array();

                    foreach ($v3 as $k => $v) {

                       $v2=$v;

                    }

                    $date=$v2['date_exigee'];
                    $dateDepartTimestamp="";
                    $dateDepartTimestamp=strtotime($date);

                    $date_exigee="";
                    $date_exigee=date('Y-m-d', strtotime('+'.$rappel.' year', $dateDepartTimestamp));

                    $date_estimee="";
                    $dateDepartTimestamp=strtotime($date_exigee);
                    $date_estimee=date('Y-m-d', strtotime('+1 week', $dateDepartTimestamp));
                    
                   
                    $req=$db->Execute("insert into care_vaccination_patient (pid, vid, date_exigee, date_estimee,
                      modify_id,create_id,create_time,history) 
                     values(".$id.",".$vacid.",'".$date_exigee."','".$date_estimee."','".$modify_id."','".$create_id."','".$create_time."','".$history."')");
               

                }      

            }else{

                $vacpid=(int) $_GET['vacpid'];

                // var_dump($vacpid);
                // die();

                /****Recupérer l'utilisateur****/
                $modify_id=$_SESSION['sess_user_name'];
                $create_id=$_SESSION['sess_user_name'];
                $create_time=NULL;
                //$modify_time=NULL;

                $history="Created: ".date('Y-m-d H:i:s')." : ".$_SESSION['sess_user_name']."\n";
                /****fin de la recupération****/

                $response=$db->Execute("update care_vaccination_patient set effectuee='N', date_effectuee='0000-00-00', modify_id='".$modify_id."', history='".$history."', create_id='".$create_id."' where pid=".$id." and vid=".$vacid." and vac_pid=".$vacpid);

                //$patient=$response->fetch(); 

                /****Si ce vaccin s'effectue sur une période donnée,
                ---Recupérer le champ rappel----
                ---Faites une suppression de cette vaccination****/
                $sql2="select rappel from care_type_immunization where nr=".$vacid;    
                $req1=$db->Execute($sql2); 

                $v1=$req1->FetchRow();
                $rappel=intval($v1['rappel']);

                if($rappel>0){

                      //$req2=$db->Execute("select id,MAX(date_exige) from care_vaccination_patient where pid=".$id." and vid=".$vacid);
                      //$v2=$req2->GetArray();
                      
                      $req2=$db->Execute("select vac_pid, pid, vid, date_exigee from care_vaccination_patient where pid=".$id." and vid=".$vacid." and vac_pid > ".$vacpid);
                      $v3=$req2->GetArray();

                      // var_dump($v3);
                      // die();
                     
                      $v2=array();
                      //$i=1;
                      foreach ($v3 as $k => $v) {
                       // if($i>2){
                          $v2=$v;
                          $db->Execute('delete from care_vaccination_patient where vac_pid='.$v2['vac_pid']);
                        //}

                       // $i++;
                      }

                } 


            }

        }else{
          
          echo "Error";
           
        }
    break;
}
   



?>
