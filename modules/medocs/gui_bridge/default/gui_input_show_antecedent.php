
<div class="ui grid" style="padding:15px; width:100%;">
<?php

	if(is_object($obj)){
		//Verifie s'il a des antecedents
		if($obj->get_ante_gen($pid)){
			if($obj->type_ante($pid,'saisie')){
				//Si le dossier n'est pas modifier
			?>
            	<table class="ui blue table" width="100%">
                    <tr valign="top">
                    <td bgcolor="#f6f6f6" rowspan="3" valign="middle" width="30%">
                        <font size="-1" color="#000066">Familiaux</font>
                    </td>
                    <td>
                        <font size="-2" color="#000066">P&egrave;re :</font>
                    </td>
                    <Td>
                        <?php if(isset($ligne['pere'])) echo stripslashes($ligne['pere']); ?>
                    </Td>
                    </tr>
                    <tr>
                    <td>
                        <font size="-2" color="#000066">M&egrave;re :</font>
                    </td>
                    <Td>
                        <?php if(isset($ligne['mere'])) echo stripslashes($ligne['mere']); ?>
                    </Td>
                    </tr>
                    <tr>
                    <td>
                        <font size="-2" color="#000066">Fratrie:</font>
                    </td>
                    <Td>
                        <?php if(isset($ligne['fraterie'])) echo stripslashes($ligne['fraterie']); ?>
                    </Td>
                    </tr>
                    
                    <tr>
                    <td bgcolor="#f6f6f6" valign="middle" width="30%" colspan="2">
                        <font size="-1" color="#000066">M&eacute;dicaux</font>
                    </td>
                    <td>
                        <?php if(isset($ligne['medicaux'])) echo stripslashes($ligne['medicaux']); ?>
                    </td>
                    </tr>
                    <tr>
                    <td bgcolor="#f6f6f6" valign="middle" width="30%" colspan="2">
                        <font size="-1" color="#000066">Gyn&eacute;cologiques</font>
                    </td>
                    <td>
                        <?php if(isset($ligne['gyneco'])) echo stripslashes($ligne['gyneco']); ?>
                    </td>
                    </tr>
                    <?php
                    require_once($root_path.'include/care_api_classes/class_person.php');
                    //Instanciation de la classe person
                    $person=new Person();
                    if($person->getValue('sex',$pid)=='f'){
                    ?>
                    <tr>
                        <td bgcolor="#f6f6f6" valign="middle" width="30%" colspan="2">
                        <font size="-1" color="#000066">ObstÃ©triques</font>
                    </td>
                    <td colspan="2">
                        <?php if(isset($ligne['obstetrique'])) echo stripslashes($ligne['obstetrique']); ?> 
                    </td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                    <td bgcolor="#f6f6f6" valign="middle" width="30%" colspan="2">
                        <font size="-1" color="#000066">Autres</font>
                    </td>
                    <td>
                        <?php if(isset($ligne['autres'])) echo stripslashes($ligne['autres']); ?>
                    </td>

                    </tr>
                    <tr>
                        <td colspan="3">
                            <center>
                                <?php $link=$baseLink."&target=$target&mode=update";?>
                                <a href="<?php echo $link; ?>">
                                   <button name="save" class="ui compact icon green button" value="modifier"><i class="edit icon"></i> Modifier</button>
                                </a> 
                            </center>
                        </td>
                    </tr>
                </table>

             <br>
             
            <?php
			}
		}
	}
?>



</div>