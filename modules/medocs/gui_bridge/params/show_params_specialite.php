<?php  

/*****JAVASCRIPT*****/
ob_start();
?>
<script type="text/javascript">
	$(document).ready(function(){
		//alert("bonjour");
	    $("#params_specialite" ).sortable({
	    	stop:function(){
	    		var pos=1;
	    		$("#params_specialite").find("span").each(function(i){
	    			//alert(i);
	    			$(this).html(pos);
	    			pos++;
	    		});
	    	}
	    });
	    $("#params_specialite" ).disableSelection();

	    $("#specialite").on("change",function(){
	    	$.post("../../modules/ajax/ajaxMedocsParams.php<?php echo URL_APPEND.'&target=loadDetailSpecialiteParams'; ?>",{id:$(this).val()},function(data){
	    		$("#params_specialite").html("");
	    		$("#params_specialite").html(data);
	    	})
	    });
	});

	function dropRow(element){
		if(confirm("Voulez-vous supprimer la ligne")) {
			$(element).parents('li:first').remove();
			var pos=1;
    		$("#params_specialite").find("span").each(function(i){
    			//alert(i);
    			$(this).html(pos);
    			pos++;
    		});
		}
	}

	function dropParametre(idParametre,element){
		if(confirm("Voulez-vous supprimer la ligne")){
			$.post("../../modules/ajax/ajaxMedocsParams.php<?php echo URL_APPEND.'&target=dropSpecialiteDetail'; ?>",{parametreID:idParametre},function(data){
				if(data=="ok") {
					$(element).parent('li:first').remove();
					var pos=1;
		    		$("#params_specialite").find("span").each(function(i){
		    			//alert(i);
		    			$(this).html(pos);
		    			pos++;
		    		});
				}
				else {
					alert("Erreur; Veuillez contacter l'administrateur");
				}
			});
		}
			
	}

	function addToBasket(element){
		//alert($(element).attr('data'));

		if($("#specialite").val()=="") {
			alert("Veuillez selectionner une spécialité");
			return false;
		}
		var designation=$(element).attr('data');
		var id=$(element).attr('id');
		var position=$("#params_specialite").find('li').length;
		position++;
		if($("#params_specialite").find(".list-group-item[id="+id+"]").length>0){
			alert("Cet parametre existe deja");
			return false;
		}
		var textToAppend='<li class="list-group-item" id="'+id+'"" rowid=""><button class="btn btn-xs btn-danger" onclick="dropRow(this)"><i class="glyphicon glyphicon-trash"></i></button> '+designation+'<span class="badge" style="background-color:green">'+position+'</span></li>';
		$("#params_specialite").append(textToAppend);
	}

	function saveDetail(){
		if($("#params_specialite").find('li.list-group-item').length==0){
			alert('Veuillez selectionner un element');
			return false;
		}
		var jsonData='{"idSpecialite":"'+$("#specialite").val()+'","rows":[';
		$("#params_specialite").find('li.list-group-item').each(function(i){
			//alert(i);
			if(i==0) jsonData+='{"idElement":"'+$(this).attr('id')+'", "rowId":"'+$(this).attr('rowid')+'", "position":"'+$(this).find('.badge').html()+'"}';
			else jsonData+=',{"idElement":"'+$(this).attr('id')+'", "position":"'+$(this).find('.badge').html()+'"}';
		})
		jsonData+=']}';
		//alert(jsonData);
		jsonData=JSON.parse(jsonData);
		$.post("../../modules/ajax/ajaxMedocsParams.php<?php echo URL_APPEND; ?>&target=saveDetailSpecialite",jsonData,function(data){
			alert(data);
			});
		//alert(jsonData);
		return false;
		//initForm();
	}

	function loadSpecialite(){
		
		var idSpecialite=$("#specialite").val();
		alert(idSpecialite);
		$.post("../../modules/ajax/ajaxMedocsParams.php<?php echo URL_APPEND.'&target=loadDetailSpecialiteParams'; ?>",{idSpecialite:idSpecialite},function(data){
			$("#params_specialite").html("");
			$("#params_specialite").html(data);
		});
	}

	function initForm(){
		$("#specialite").val("");
		$("#params_specialite").html("");
	}

</script>
<?php
$js=ob_get_contents();
ob_end_clean();
$smarty->append('JavaScript',$js);

$breadcrumb=array("Parametrage","Parametres-Spécialités");

/****LISTE DES PARAMETRE****/
$listParametre=$objMedocsParams->listeParametre();
ob_start();
while($param=$listParametre->FetchRow()){
	echo '<li ondblclick="addToBasket(this)" class="list-group-item" id="'.$param['id'].'" data="'.$param['designation'].'">';
	echo ucfirst($param['designation']);
	echo '</li>';
}

$listParams=ob_get_contents();
ob_end_clean();
$smarty->assign("listParams",$listParams);

/***SPECIALITE**/
$smarty->assign("specialiteOptions",$objMedocsParams->specialiteOptions());

$smarty->assign("sMainBlockIncludeFile","medocs/params_specialite.tpl");

?>