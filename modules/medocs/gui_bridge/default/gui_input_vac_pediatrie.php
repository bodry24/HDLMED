<?php 
//ini_set("display_errors", true);
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//require('roots.php');
$root_path='../../../../';

require($root_path.'include/core/inc_environment_global.php');


global $db;


if(isset($_GET['monid']) && isset($_GET['vacid']) && isset($_GET['etatcheck'])){

    $id=(int) $_GET['monid'];
    $vacid=(int) $_GET['vacid'];
    $etatcheck=(int) $_GET['etatcheck'];

    if($etatcheck==1){

       /****RecupÃ©rer l'utilisateur****/
        $modify_id=$_SESSION['sess_user_name'];
        $create_id=$_SESSION['sess_user_name'];
        $create_time=NULL;
        //$modify_time=NULL;

        $history=" Created: ".date('Y-m-d H:i:s')." : ".$_SESSION['sess_user_name']."\n";
        /****fin de la recupÃ©ration****/

        $response=$db->Execute("update care_vaccination_patient set effectuee='O', date_effectuee='".date('Y-m-d')."', modify_id='".$modify_id."', history='".$history."', create_id='".$create_id."' where pid=".$id." and vid=".$vacid);

        //$patient=$response->fetch(); 

        /****Si ce vaccin s'effectue sur une pÃ©riode donnÃ©e,
        ---RecupÃ©rer le champ rappel----
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

        /****RecupÃ©rer l'utilisateur****/
        $modify_id=$_SESSION['sess_user_name'];
        $create_id=$_SESSION['sess_user_name'];
        $create_time=NULL;
        //$modify_time=NULL;

        $history="Created: ".date('Y-m-d H:i:s')." : ".$_SESSION['sess_user_name']."\n";
        /****fin de la recupÃ©ration****/

        $response=$db->Execute("update care_vaccination_patient set effectuee='N', modify_id='".$modify_id."', history='".$history."', create_id='".$create_id."' where pid=".$id." and vid=".$vacid." and vac_pid=".$vacpid);

        //$patient=$response->fetch(); 

        /****Si ce vaccin s'effectue sur une pÃ©riode donnÃ©e,
        ---RecupÃ©rer le champ rappel----
        ---Faites une suppression de cette vaccination******/
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

    if(isset($_GET['vacdate']) && isset($_GET['vacpid'])){

      $vacpid=$_GET['vacpid'];
      $vacdate=$_GET['vacdate'];
      if($vacdate!=null){
         $date=substr($vacdate,6,4).'-'.substr($vacdate,3,2).'-'.substr($vacdate,0,2);
      }
       

      $effectuee=($vacdate!=null?'O':'N');
      //on fait ici la mise Ã  jour dans la table care_vaccination_patient

      $sql="UPDATE care_vaccination_patient SET date_effectuee='".$date."', effectuee='".$effectuee."' WHERE vac_pid=$vacpid";

     // $sql="UPDATE care_vaccination_patient SET date_effectuee='".$date."' WHERE vac_pid=$vacpid";

      //var_dump($sql);
      //die();

      if($db->Execute($sql)){
          return true;
      }else{
          return $sql;
      }

      /* $filters = array();
      foreach (explode('&', $_POST['filters'] ) as $chunk) {
         $param = explode("=", $chunk);
         if ($param) {
            $filters[urldecode($param[0])][] = urldecode($param[1]);
          }
      }
      foreach($filters as $filter)
      {
          ?><pre><?php var_dump( $filter ); ?></pre><?php
      }*/

    }else{
      echo "Error";
    } 
   
}



?>
