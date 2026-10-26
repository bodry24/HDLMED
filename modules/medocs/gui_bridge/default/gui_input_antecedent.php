<style type="text/css">

    .important{color : #ff0000;}
    .link { cursor: pointer;}
    .right{
        text-align: right;
    }
    .left{
        text-align: left;
    }
    .center{
        text-align: center;
    }
    .cblue{
      color:#1678C2;
      text-align: center;
    }

</style>
<?php
  $pere=$mere=$fraterie=$gyneco=$medicaux=$chirurgicaux=$autre=' ';
  $mode="create";
  if($ante_gen){
    $mode="update";
    $pere=$ligne['pere'];
    $mere=$ligne['mere'];
    $fraterie=$ligne['fraterie'];
    $gyneco=$ligne['gyneco'];
    $medicaux=$ligne['medicaux'];
    $chirurgicaux=$ligne['chirurgicaux'];
    $obstetrique=$ligne['obstetrique'];
    $autres=$ligne['autres'];

  }
?>
<form class="ui form left" method="post" action="" >
    <h4 class="ui dividing header cblue" style="color:#1678C2;">FAMILIAUX</h4>
 
      <div class="field">
      
        <div class="three fields">
          <div class="field">
            <label>P&egrave;re</label>
            <textarea name="pere" cols="20" rows="10" placeholder="P&egrave;re"><?php echo $pere;?></textarea>
          </div>
          <div class="field">
            <label>M&egrave;re</label>
            <textarea name="mere" cols="20" rows="10" placeholder="M&egrave;re"><?php echo $mere;?></textarea>
          </div>
          <div class="field">
            <label>Fratrie</label>
            <textarea name="fraterie" cols="20" rows="10" placeholder="fratrie"><?php echo $fraterie;?></textarea>
          </div>
        </div>
      </div>
      <h4 class="ui dividing header cblue" style="color:#1678C2;">PERSONNEL</h4>
      <div class="field">
          <label>Medicaux</label>
          <textarea name="medicaux" cols="20" rows="3" placeholder="Medicaux"><?php echo $medicaux;?></textarea>
      </div>
      <div class="field">
          <label>Gynecologiques</label>
          <textarea name="gyneco" cols="20" rows="3" placeholder="Gynecologiques"><?php echo $gyneco;?></textarea>
      </div>
      <?php
          require_once($root_path.'include/care_api_classes/class_person.php');
          //Instanciation de la classe person
          $person=new Person();
          if($person->getValue('sex',$pid)=='f'){
      ?>
      <div class="field">
          <label>ObstÃ©triques</label>
          <textarea name="obstetrique" cols="20" rows="3" placeholder="obstÃ©triques"><?php echo $obstetrique;?></textarea>
      </div>
      <?php } ?>
      <div class="field">
          <label>Autres</label>
          <textarea name="autres" cols="20" rows="3" placeholder="autres"><?php echo $autres;?></textarea>
      </div>
      <div class="field cblue">
          <button name="save" class="ui compact icon blue button" value="sauvegarder"><i class="save icon"></i> Sauvegarder</button>
      </div>
      <input type="hidden" name="mode" value="<?php echo $mode;?>">

</form>

