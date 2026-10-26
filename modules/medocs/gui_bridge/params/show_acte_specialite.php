<?php 
$breadcrumb=array("Parametrage","Acte - Spécialité") ;
ob_start();
/****JAVASCRIPT****/
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#datatable").DataTable();
	})
</script>
<?php
$JavaScript=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$JavaScript);
//Liste des actes de consultation susceptible de Rendez-vous;
//Recuperation de la liste des actes de type consultation
$sqlActeConsultation="SELECT a.*,t.item_type_description,ifnull(s.designation,'Selectionner Specialite') FROM care_billing_item a LEFT JOIN care_medocs_specialites s ON (s.id=a.id_specialite), care_billing_item_type t WHERE t.item_type_code=a.item_type AND a.valide_status='O' AND a.item_type IN ('CS', 'C') ORDER BY a.item_description";
$specialiteOptions=$objMedocsParams->specialiteOptions();
if($listeActe=$db->Execute($sqlActeConsultation)){
	ob_start();
	echo '<table id="datatable" class="table table-bordered table-striped">';
	?>
	<thead style="background-color:#00009c; color:white">
		<tr>
			<th>#</th>
			<th>Designation Acte</th>
			<th>Type Acte</th>
			<th>Spécialité Associé</th>
			<th>Option</th>
		</tr>
	</thead>
	<?php
	$pos=1;
	while($item=$listeActe->FetchRow()){
		echo '<tr>';
		echo '<td>'.$pos.'</td>';
		echo '<td>'.$item['item_description'].'</td>';
		echo '<td>'.$item['item_type_description'].'</td>';
		echo '<td><select class="form-control" name=""';
		if(isset($item['id_specialite']) && !empty($item['id_specialite'])) echo 'disabled="disabled"';
		echo'><option value="'.$item['id_specialite'].'">'.$item['designation'].'</option>'.$specialiteOptions.'</select></td>';
		echo '<td>';
		if(empty($item['id_specialite']) || !isset($item['id_specialite']))
		echo '<button onclick="return saveSpecialite(this,'.$item['item_code'].')" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i></button></a>';
		else echo '<a href=""><button class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i></button></a>';
		echo '</td>';
		echo '<tr>';
		$pos++;
	}
	echo '</table>';
	$listeTampon=ob_get_clean();
}
$smarty->assign("sMainBlockData",$listeTampon);

?>