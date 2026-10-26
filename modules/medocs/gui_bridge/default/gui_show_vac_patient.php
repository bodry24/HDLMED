
<div style="text-align:left">
	
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
                <font size="-2" color="#000066">M&egrave;re :</font>
            </td>
            <td>
                <?php if(isset($ligne['mere'])) echo stripslashes($ligne['mere']); ?>
            </td>
            </tr>
            <tr>
            <td>
                <font size="-2" color="#000066">Fratrie:</font>
            </td>
            <td>
                <?php if(isset($ligne['fraterie'])) echo stripslashes($ligne['fraterie']); ?>
            </td>
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
                                     
        </table>


</div>
