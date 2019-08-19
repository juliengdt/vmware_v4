<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('vmware');
sendVarToJS('eqType', $plugin->getId()); // Permet de rendre cliquable les éléments de la page Mes équipements (Mes Serveurs ESXi)
$eqLogics = eqLogic::byType($plugin->getId()); // Permet de récupérer la liste des équipements de type vmware dans la table eqLogic

// pour le débug -> permet d'afficher sur la console du navigateur en appelant la fonction console_log
function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
?>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity .'"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
}
		    ?>
           </ul>
       </div>
   </div>

   <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
   <!-- <legend>{{Mes équipements}}</legend>-->
  <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
  <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction" data-action="add" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
        <i class="fa fa-plus-circle" style="font-size : 6em;color:#00A9EC;"></i>
        <br>
        <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#00A9EC">{{Ajouter}}</span>
    </div>
  <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
      <i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
    <br>
    <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Configuration}}</span>
  </div>
  <div class="cursor" id="bt_healthvmware" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
        <center>
          <i class="fa fa-medkit" style="font-size : 6em;color:#767676;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Santé}}</center></span>
      </div>
  </div>
	<legend><i class="fa fa-table"></i> {{Mes serveurs ESXi}}</legend>
  <!--<div class="eqLogicThumbnailContainer">-->
    <?php
		foreach ($eqLogics as $eqLogicEsxiHost) {
			if ($eqLogicEsxiHost->getConfiguration('type') == 'ESXi') {
            	echo '<legend>' . $eqLogicEsxiHost->getHumanName(true) . '</legend>';
				echo '<div class="eqLogicThumbnailContainer">';
				$opacity = ($eqLogicEsxiHost->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
				echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogicEsxiHost->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
				// On affiche une image différente pour le serveur eSXi pour le répérer plus facilement
				echo '<img src="plugins/vmware/docs/assets/images/icone_esxi.png" height="105" width="95">';
				echo '<br>';
				echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogicEsxiHost->getHumanName(true, true) . '</span>';
				echo '</div>';
				foreach ($eqLogics as $eqLogicVM) {
					if ($eqLogicVM->getConfiguration('type') == 'vm' && $eqLogicVM->getConfiguration('ESXiHostIpAddress') == $eqLogicEsxiHost->getConfiguration('ipAddress')) {
						$opacity = ($eqLogicVM->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
						echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogicVM->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
						echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="95" />';
						echo "<br>";
						echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogicVM->getHumanName(true, true) . '</span>';
						echo '</div>';
					}
				}
				echo '</div>';
			}
		}
	?>	
</div>

<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
	<a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
  <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
  <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
    <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
  </ul>
  <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
    <div role="tabpanel" class="tab-pane active" id="eqlogictab">
      <br/>
	  <div class="row">
        <div class="col-sm-6">
    <form class="form-horizontal">
        <fieldset>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Nom de l'équipement }}</label>
                <div class="col-sm-6">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement ESXi}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                <div class="col-sm-6">
                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                        <option value="">{{Aucun}}</option>
                        <?php
foreach (jeeObject::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
                   </select>
               </div>
           </div>
	   <div class="form-group">
                <label class="col-sm-3 control-label">{{Catégorie}}</label>
                <div class="col-sm-8">
                 <?php
                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                    echo '<label class="checkbox-inline">';
                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                    echo '</label>';
                    }
                  ?>
               </div>
           </div>
	<div class="form-group">
		<label class="col-sm-3 control-label"></label>
		<div class="col-sm-6">
			<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
			<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
		</div>
	</div>
    <div class="form-group" id="ESXiIpAddress">
		<label class="col-sm-3 control-label">{{Adresse IP de votre ESXi}}</label>
		<div class="col-sm-6">
			<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ipAddress" placeholder="Au format XXX.XXX.XXX.XXX"/>
		</div>
    </div>	
	<div id="loginPassword">				  
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Login}}</label>
			<div class="col-sm-6">
				<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="login" />
			</div>
		</div>
		<div class="form-group" id="passwordESXi">
			<label class="col-sm-3 control-label">{{Mot de passe}}</label>
			<div class="col-sm-6">
				<input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="passwordSSH" />
			</div>
		</div>
	</div>
</fieldset>
</form>
</div>

<div class="col-sm-4">
          <form class="form-horizontal">
            <fieldset>
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Type}}</label>
                <div class="col-sm-2">
                  <span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="type" id="typefield"></span>
                </div>
                <label class="col-sm-3 control-label" id="ESXIHostLabel">{{Hôte ESXi}}</label>
                <div class="col-sm-2">
                  <span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="esxiHost" id="esxiHostfield"></span> 
                </div>
              </div>
            
			
              <div class="form-group">
                <label class="col-sm-3 control-label" id="ipAddressLabelRightPartOfPage">{{Adresse IP}}</label>
                <div class="col-sm-2">
                  <span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="vmIPAddress" id="ipAddressfield"></span>
                </div>
                <!--<label class="col-sm-3 control-label" id="nbSnapLabel">{{Nb snap}}</label>
                <div class="col-sm-3">
                  <span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="nbSnap" id="nbSnapfield"></span> 
                </div>-->
              </div>
            </fieldset>
          </form>
        </div>
</div>
</div>
      <div role="tabpanel" class="tab-pane" id="commandtab">
<a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fa fa-plus-circle"></i> {{Commandes}}</a><br/><br/>
<table id="table_cmd" class="table table-bordered table-condensed">
    <thead>
        <tr>
            <th>{{Nom}}</th><th>{{Type}}</th><th>{{Configuration}}</th><th>{{Action}}</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>
</div>

</div>
</div>

<?php include_file('desktop', 'vmware', 'js', 'vmware');?>
<?php include_file('core', 'plugin.template', 'js');?>
