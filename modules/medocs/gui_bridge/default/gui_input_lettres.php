<?php
   
    $LDCreateNewForm = 'Choisir le type de lettre' ;
    $certif_type = array(
        1 => 'Consultation',
        2 => 'Reponse  consultation',
        3 => 'Hospitalisation',
        4 => 'Chirurgie'
    );

    $nb_certif_type = count($certif_type);

?>
<script type="text/javascript" src='<?php echo $root_path."js/tinymce/tinymce.min.js";?>'></script>


<style type="text/css">
   /* #sender, #receiver, #place, #object_letter {width: 400px;}
    #audio_file {width: 200px;}*/
    /*span{color : #ff0000;}*/
    .important{color : #ff0000;}
    .link { cursor: pointer;}
    .right{
        text-align: right;
    }
    .left{
        text-align: left;
    }
</style>
<script language="javascript">
    // Pour le tinymce
    tinymce.init({
        selector: "textarea#body_letter",
        theme: "modern",
        width: 785,
        height: 300,
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
        width: 785,
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
        width: 785,
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
        width: 785,
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
        width: 785,
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
        width: 785,
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

    <!-- //Script Begin-->
    String.prototype.trim = function () {
        var str = this.replace(/^\s+/, '');
        for (var i = str.length - 1; i >= 0; i--) {
            if (/\S/.test(str.charAt(i))) {
                str = str.substring(0, i + 1);
                break;
            }
        }
        return str;
    };
    /**
    * Cette function test et vérifie si la chaîne en paramètre correspond 
    * à un nom de fichier audio
    */    
    function isAudioFile(filename){
        
        //les extensions de fichier audio dont on veut faire une comparaisons
        mimefilter = new Array('mp3','wma','ogg','au','waw','aac','ac3');
        
        //transformation de la chaine en un tableau afin de pouvoir récupérer l'extension'
        tab_filename = filename.split('.');
        
        //On teste l'extensio trouvée avac toutes les extensions autorises '
        for(i=0;i<mimefilter.length; i++){
            
            // Si une l'extension a été trouvé on envoie true'
            if(mimefilter[i]==tab_filename[tab_filename.length-1].toLowerCase()) return true;       
        }
        
        //Finalement on retourne false
        return false;
    }
    function selectModel(model){
        switch(model){
            case 'body' :
                urlholder='<?php echo $root_path."modules/biasa_pdf/popup/pop_up_letter.php?model="?>'+model+'&lang=<?php echo $lang;?>';
                drgwin_<?php echo $sid ?>=window.open(urlholder,"drgwin_<?php echo $sid ?>","width=400,height=400,menubar=no,resizable=yes,scrollbars=yes");
                break;
            
            case 'receiver' :
                urlholder='<?php echo $root_path."modules/biasa_pdf/popup/pop_up_letter.php?model="?>'+model+'&lang=<?php echo $lang;?>';
                drgwin_<?php echo $sid ?>=window.open(urlholder,"drgwin_<?php echo $sid ?>","width=400,height=400,menubar=no,resizable=yes,scrollbars=yes");
                break;
            case 'sender' :
                urlholder='<?php echo $root_path."modules/biasa_pdf/popup/pop_up_letter.php?model="?>'+model+'&lang=<?php echo $lang;?>';
                drgwin_<?php echo $sid ?>=window.open(urlholder,"drgwin_<?php echo $sid ?>","width=400,height=400,menubar=no,resizable=yes,scrollbars=yes");
                break;
            default :
                return false;
        }
           
    }
    function loadSickForm(value){

        if(isNaN(parseInt(value))||value==''){
            return false;
        }
        else{
            if(value<=parseInt('<?php echo $nb_certif_type;?>')&& value>=1){
               // alert(document.getElementById('type_certif').value);
                document.newform.submit();

            }
        }
        return;
    }
    function chkSickForm(f) {
   
        if(f.sender.value.trim()==''){
            alert('Veuillez saisir l\'expéditeur');
            f.sender.select();
            return false;
        }else if(f.receiver.value.trim()==''){
            alert('Veuillez saisir le destinataire');
            f.receiver.select();
            return false;
        }else if(f.location.value.trim()==''){
            alert('Veuillez saisir le lieu');
            f.location.select();
            return false;
        }else if(f.body_letter.value.trim()==''){
            alert('Veuillez saisir le corps de la lettre');
            f.body_letter.select();
            return false;
        }
        return true;        
    }
    
    //  Script End -->
</script>
<?php //var_dump($certif_nr.' - type -'.$type_certif);?>
<?php if(isset($certif_nr))  $type_certif = $certif_nr; ?>
<div class="ui grid" style="">
    <div class="one column row"> 
        <div class="column">        
            <form method="post" name="newform" action="" class="ui form left">
                <fieldset><legend>Enregistrer une nouvelle lettre</legend>
                    <div class="field">
                        <label for="type_certif"><?php echo $LDCreateNewForm; ?></label>
                        <select id="type_certif" name="type_certif" onchange="return loadSickForm(this.value);" class="ui fluid dropdown">
                            <option value=""></option>
                            <?php
                                asort($certif_type);
                                foreach($certif_type as $key=>$value){
                                ?><option value="<?php echo $key; ?>" <?php echo ($type_certif==$key)?'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                <?php                    
                                }
                        ?></select>
                    </div>
                    <input type="hidden" name="sid" value="<?php echo $sid; ?>">
                    <input type="hidden" name="lang" value="<?php echo $lang; ?>">
                   <!--  <input type="hidden" name="pid" value="<?php echo $_SESSION['sess_pid']; ?>"> -->
                    <input type="hidden" name="mode" value="new">
                    <input type="hidden" name="target" value="<?php echo $target; ?>">
                </fieldset>
            </form>
        </div>
    </div> 
    <div class="one column row">
        <div class="column">
<?php
    //var_dump('coucou');
    $tab_legend = array(
        'Lettre consultation',
        'Lettre r&eacute;ponse consultation',
        'Lettre Chirurgie',
        'Lettre Hospitalisation'
    );

    require_once($root_path.'include/core/inc_date_format_functions.php');
    require_once($root_path.'js/jscalendar/calendar.php');
    $calendar=new DHTML_Calendar($root_path.'js/jscalendar/', $lang, 'calendar-system',true);
    $calendar->load_files();
    
    if(isset($type_certif)){
      /* $body_letter=nl2br(utf8_decode('
        Je vous remercie de bien vouloir prendre en charge âgée de 41 ans pour un désir de grossesse depuis treize ans.

        Il s\' agit d\' une patiente ayant eu une fausse couche à trois mois et ayant bénéficié d\'une myomectomie en 2006. En 2008 elle a consulté chez nous pour une aménorrhée datant de trois mois. Les dosages hormonaux qui ont été faits à l\'époque ont montré :
        FSH 71, 33 mmi/ml 
        LH 24,88 mui/ml
        Oestradiol  37,43 pg/ml
        Prolactine 16,9pg/ml

        Compte tenu de ces dosages, il a été décidé de réaliser une fécondation in vitro par don d\'ovocytes. Trois tentatives ont été réalisées dont vous trouverez ci-joints les comptes rendus :
        -   Première tentative en Mars 2010 : quatre ovocytes matures, trois embryons obtenus et transférés ; grossesse clinique avec sac gestationnel à l\'échographie, mais malheureusement arrêt d\'évolutivité à huit semaines ;
        -   Deuxième tentative en Mars 2012 : deux ovocytes matures donnés, deux embryons obtenus et transférés avec un échec de nidation ;
        -   Troisième tentative en Mars 2013 : deux ovocytes  matures donnés, deux embryons obtenus et transférés, échec de nidation.
        Mme YAO souhaite aller poursuivre les soins aux Etats-Unis ; nous le confions à vos bons soins avec l\'espoir d\'une suite favorable.

        Vous remerciant pour ce que vous ferez pour elle, je vous prie de croire, Cher confrère, l\'assurance de mes sentiments les plus cordialement dévoués.
       ')
    );*/

if($_GET['nr']){
    $result=$letter_obj->getPhysicianLetter($_GET['nr']);
    $letter=$result->FetchRow();
    $object_letter=$letter['object_letter'];
    $sender=$letter['sender'];
    $receiver=$letter['receiver'];
    $code_letter=$letter['code_letter'];
    $location=$letter['location'];
    $body_letter=$letter['body_letter'];
}
    ?>
    
        <form id="sickform" class="ui form left" name="sickform" onSubmit="return chkSickForm(this)" method="post" enctype="multipart/form-data" class="ui form left" >
            <fieldset><legend><?php echo $tab_legend[$type_certif-1] ?></legend>
                <div class="field">
                    <div class="three fields">
                        <div class="field">
                            <label>Objet</label> 
                            <input type="text" name="object_letter" id="object_letter" value="<?php echo (isset($object_letter)? $object_letter:''); ?>"/>
                        </div>
                        <div class="field">
                            <label>Code de la lettre</label>                     
                            <input type="text" name="code_letter" id="code_letter" value="<?php echo (isset($code_letter)? $code_letter:''); ?>"/>
                        </div>
                        <div class="field">
                            <label>Lieu</label>                 
                            <input type="text" name="location" id="location" value="<?php echo (isset($location)? $location:'Lom&eacute;'); ?>"/>
                        </div>
                    </div>                
                </div>

                <div class="field">
                    <div class="three fields">
                        <div class="field">
                            <label>Exp&eacute;diteur</label>
                    
                            <textarea cols="50" rows="3" name="sender" id="sender" ><?php echo (isset($sender)? $sender:$doctor); ?></textarea>
                        </div>
                        <div class="field">
                            <label class="important">Destinataire<span>*</span></label>
                            
                            <textarea value cols="50" rows="3" name="receiver" id="receiver"><?php echo (isset($receiver)? $receiver:''); ?></textarea>
                        </div>
                        <div class="field">
                            <label>Date lettre</label>
                            <?php echo $calendar->show_calendar($calendar,$date_format,'date_letter'); ?>
                        </div>
                    </div>                
                </div>
                
                <?php if($_GET['nr']):?>           
                    <div class="field">
                        <label>Corps</label>
                        <textarea name="body_letter" id="body_letter" value cols="50" rows="20">
                            <?php echo (isset($body_letter)? $body_letter:' '); ?>
                        </textarea>
                    </div>
                <?php else: ?>
                    <?php switch ($type_certif) {
                        case 1:
                            echo   '<div class="field">
                                        <label>Hypoth&egrave;se</label>
                                        <textarea name="hypothese" id="hypothese" value cols="50" rows="20"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Examens Compl&eacute;mentaires</label>
                                        <textarea name="examen" id="examen" value cols="50" rows="20"></textarea>
                                    </div>';
                            break;
                        case 2:
                            echo  '<div class="field">
                                        <label>Diagnostic</label>
                                        <textarea name="diagnostic" id="diagnostic" value cols="50" rows="20">
                                        </textarea>
                                    </div>
                                    <div class="field">
                                        <label>Solution Pr&eacute;conis&eacute;e</label>
                                        <textarea name="preconise" id="preconise" value cols="50" rows="20"></textarea>
                                    </div>';
                            break;
                        case 3:
                            echo  '
                                    <div class="field">
                                        <div class="three fields">
                                            <div class="field">
                                                <label>Date sortie</label>'.
                                                $calendar->show_calendar($calendar,$date_format,'date_debut').'
                                            </div>
                                            <div class="field">
                                                <label>Date sortie</label>'.
                                                $calendar->show_calendar($calendar,$date_format,'date_fin').'
                                            </div>
                                            <div class="field">
                                                <label>Date sortie</label>'.
                                                $calendar->show_calendar($calendar,$date_format,'date_sortie').'
                                            </div>
                                        </div>                
                                    </div>
                                    <div class="field">
                                        <label>Motif</label>
                                        <textarea name="operatoire" id="operatoire" value cols="50" rows="20"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Diagnostic</label>
                                        <textarea name="diagnostic" id="diagnostic" value cols="50" rows="20">
                                        </textarea>
                                    </div>
                                    
                                    <div class="field">
                                        <label>Traitement</label>
                                        <textarea name="preconise" id="preconise" value cols="50" rows="20"></textarea>
                                    </div>';
                           
                            break;
                        case 4:
                            echo  '
                                    <div class="field">
                                        <div class="four fields">
                                            <div class="field">
                                                <label>Date sortie</label>'.
                                                $calendar->show_calendar($calendar,$date_format,'date_debut').'
                                            </div>
                                            <div class="field">
                                                <label>Date sortie</label>'.
                                                $calendar->show_calendar($calendar,$date_format,'date_fin').'
                                            </div>
                                            <div class="field">
                                                <label>Date chirurgie</label>'.
                                                $calendar->show_calendar($calendar,$date_format,'date_sortie').'
                                            </div>
                                            <div class="field">
                                                <label>Chirurgien</label>
                                                <input type="text" name="chirurgien">
                                            </div>
                                        </div>                
                                    </div>
                                    <div class="field">
                                        <label>Motif</label>
                                        <textarea name="operatoire" id="operatoire" value cols="50" rows="20"></textarea>
                                    </div>
                                    <div class="field">
                                        <label>Suites opératoires</label>
                                        <textarea name="diagnostic" id="diagnostic" value cols="50" rows="20">
                                        </textarea>
                                    </div>
                                    
                                    <div class="field">
                                        <label>Traitement</label>
                                        <textarea name="preconise" id="preconise" value cols="50" rows="20"></textarea>
                                    </div>';
                           
                            break;
                        default:
                            # code...
                            break;
                    }
                endif; 

                ?>
                <div class="field right">
                    <br>
                    <input type="submit" name="save" id="save" class="ui blue button" value="Sauvegarder">
                </div>
                
               
                <input type="hidden" name="sid" value="<?php echo $sid; ?>">
                <input type="hidden" name="lang" value="<?php echo $lang; ?>">
                <input type="hidden" name="certif_nr" value="<?php echo $type_certif; ?>">
                <input type="hidden" name="mode" value="<?php echo $_GET['nr']?'update':'create';?>">
                <input type="hidden" name="target" value="<?php echo $target; ?>">
            </fieldset>
        </form>
        
    <?php
    } 
    ?> 

        </div>
    </div>
</div>