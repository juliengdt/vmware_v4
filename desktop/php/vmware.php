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
 <div class="col-lg-12 eqLogicThumbnailDisplay">
	<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
	<div class="eqLogicThumbnailContainer">
	    <div class="cursor eqLogicAction logoPrimary" data-action="add">
			<i class="fas fa-plus-circle"></i>
			<br>
			<span>{{Ajouter}}</span>
		</div>
		<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
			<i class="fas fa-wrench"></i>
			<br>
			<span>{{Configuration}}</span>
		</div>
		<div class="cursor eqLogicAction logoSecondary" id="bt_healthvmware"> <!-- l'action est traitée dans le vmware.js -->
			<i class="fas fa-medkit"></i>
			<br>
			<span>{{Santé}}</span>
		</div>
	</div>
	<legend><i class="fas fa-table"></i> {{Mes équipements VMWARE}}</legend>
		<input class="form-control" placeholder="{{Chercher parmis vos équipements}}" id="in_searchEqlogic" />
	<!--<div class="eqLogicThumbnailContainer">--> <!-- a commenter si besoin de tester l'alignement en cas de plusieurs ESXi présent -->
    <?php
		foreach ($eqLogics as $eqLogicEsxiHost) {
			if ($eqLogicEsxiHost->getConfiguration('type') == 'ESXi') {
            	console_log('ESXI trouvé ' . $eqLogicEsxiHost->getConfiguration('name') . '');
				echo '<legend>' . $eqLogicEsxiHost->getHumanName(true) . '</legend>';
				echo '<div class="eqLogicThumbnailContainer">'; 
				echo '<div class="cursor eqLogicAction synchronisation"  data-id="' . $eqLogicEsxiHost->getId() . '">'; // l'action est traitée dans le vmware.js le data-id permet de récupérer l'info dans le JS pour transmettre l'appel à la fonction refresh pour l'ESXi en question uniquement // on se base sur le terme synchronisation pour le retrouver dans le JS
				echo '<i class="fas fa-sync"></i>';
				echo '<br>';
				echo '<span>{{Synchroniser}}</span>';
				echo '</div>';
				$opacity = ($eqLogicEsxiHost->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogicEsxiHost->getId() . '">';
				// On affiche une image différente pour le serveur ESXi pour le répérer plus facilement
					echo '<img src="plugins/vmware/docs/assets/images/icone_esxi.png">';
					echo '<br>';
					echo '<span class="name">' . $eqLogicEsxiHost->getHumanName(true, true) . '</span>';
					echo '</div>';
				foreach ($eqLogics as $eqLogicVM) {
					if ($eqLogicVM->getConfiguration('type') == 'vm' && $eqLogicVM->getConfiguration('ESXiHostIpAddress') == $eqLogicEsxiHost->getConfiguration('ipAddress')) {
						//console_log('VM trouvée ' . $eqLogicVM->getConfiguration('name') . '');
						$opacity = ($eqLogicVM->getIsEnable()) ? '' : 'disableCard';
						echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogicVM->getId() . '">';
							echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
							echo "<br>";
							echo '<span class="name">' . $eqLogicVM->getHumanName(true, true) . '</span>';
						echo '</div>';
					}
				}
				echo '</div>';
			//  echo '</div>'; // Permet d'avoir le deuxième ESXi aligné à gauche, mais il se retrouve bien trop bas ( voir si c'est la présence de VM qui pose problème
			}else {
				console_log('VM trouvée, on boucle à la recherche de VM Orphelines ');	
				$doNothing = "";
			if ($eqLogicEsxiHost->getConfiguration('type') == 'vm') {
				//console_log('VM trouvéeeeeeeeeeeeeeeeeeeeeee ' . $eqLogicEsxiHost->getConfiguration('name') . '');
				foreach ($eqLogics as $eqLogicc) {
					//console_log('IP de la VM ' . $eqLogicEsxiHost->getConfiguration('ESXiHostIpAddress') . '');
					//console_log('IP de l\'ESXI ' . $eqLogicc->getConfiguration('ipAddress') . '');
					//console_log('TYPE de l\'eqLogicc en cours ' . $eqLogicc->getConfiguration('type') . '');
					if ($eqLogicc->getConfiguration('type') == 'ESXi' && $eqLogicEsxiHost->getConfiguration('ESXiHostIpAddress') == $eqLogicc->getConfiguration('ipAddress')) { // on cherche si l'ESXi existe par son IP
						console_log('On a trouvé l\'hote ESXI associé à la VM on ne fait donc rien et on sort du foreach');
						$doNothing = "Yes";
						//break;
					}else {
						console_log('On n\'a pas trouvé l\'hote ESXI associé à la VM, donc on l\'affiche');
					}
					if ($doNothing == "Yes") {
						console_log('Boucle If do nothing donc on break le foreach');
						break;
					}
				}
				if ($doNothing != "Yes") {
					console_log('Boucle If do nothing non égale à Yes donc on doit afficher l\'élément orphelin');
					break;
				}
				
			/*	echo '<legend>' . $eqLogicEsxiHost->getHumanName(true) . '</legend>';
				echo '<div class="eqLogicThumbnailContainer">'; 
				echo '<div class="cursor eqLogicAction synchronisation"  data-id="' . $eqLogicEsxiHost->getId() . '">'; // l'action est traitée dans le vmware.js le data-id permet de récupérer l'info dans le JS pour transmettre l'appel à la fonction refresh pour l'ESXi en question uniquement // on se base sur le terme synchronisation pour le retrouver dans le JS
				echo '<i class="fas fa-sync"></i>';
				echo '<br>';
				echo '<span>{{Synchroniser}}</span>';
				echo '</div>';
				$opacity = ($eqLogicEsxiHost->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogicEsxiHost->getId() . '">';
				// On affiche une image différente pour le serveur ESXi pour le répérer plus facilement
					echo '<img src="plugins/vmware/docs/assets/images/icone_esxi.png">';
					echo '<br>';
					echo '<span class="name">' . $eqLogicEsxiHost->getHumanName(true, true) . '</span>';
					echo '</div>';
				foreach ($eqLogics as $eqLogicVM) {
					if ($eqLogicVM->getConfiguration('type') == 'vm' && $eqLogicVM->getConfiguration('ESXiHostIpAddress') == $eqLogicEsxiHost->getConfiguration('ipAddress')) {
						console_log('VM trouvée ' . $eqLogicVM->getConfiguration('name') . '');
						$opacity = ($eqLogicVM->getIsEnable()) ? '' : 'disableCard';
						echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogicVM->getId() . '">';
							echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
							echo "<br>";
							echo '<span class="name">' . $eqLogicVM->getHumanName(true, true) . '</span>';
						echo '</div>';
					}
				}
				echo '</div>';*/
			//  echo '</div>'; // Permet d'avoir le deuxième ESXi aligné à gauche, mais il se retrouve bien trop bas ( voir si c'est la présence de VM qui pose problème
			}
			}
		}
	?>	
	
	
	
  </div>
  <div class="col-lg-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
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
						<label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
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

		 <div class="col-sm-6">
          <form class="form-horizontal">
            <fieldset>
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Type}}</label>
                <div class="col-sm-3">
                  <span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="type" id="typefield"></span>
                </div>
                <label class="col-sm-3 control-label" id="ESXIHostLabel">{{Hôte ESXi}}</label>
                <div class="col-sm-3">
                  <span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="esxiHost" id="esxiHostfield"></span> 
                </div>
              </div>            
			
              <div class="form-group">
                <label class="col-sm-3 control-label" id="ipAddressLabelRightPartOfPage">{{Adresse IP}}</label>
                <div class="col-sm-3">
                  <span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="vmIPAddress" id="ipAddressfield"></span>
                </div>
                <label class="col-sm-3 control-label" id="nbSnapLabel">{{Nb snap}}</label>
                <div class="col-sm-3">
                  <span class="eqLogicAttr label label-default" data-l1key="configuration" data-l2key="nbSnap" id="nbSnapfield"></span> 
                </div>
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

