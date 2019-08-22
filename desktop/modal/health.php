<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
	throw new Exception('401 - Accès non autorisé');
}
//$plugin = plugin::byId('vmware');
//$eqLogics = vmware::byType('vmware');

$plugin = plugin::byId('vmware');
sendVarToJS('eqType', $plugin->getId()); // Permet de rendre cliquable les éléments de la page Mes équipements (Mes Serveurs ESXi)
$eqLogics = eqLogic::byType($plugin->getId()); // Permet de récupérer la liste des équipements de type vmware dans la table eqLogic
?>
<table class="table table-condensed tablesorter" id="table_healthvmware">
	<thead>
		<tr>
			<th>{{Image}}</th>
			<th>{{Nom}}</th>
			<th>{{Adresse IP}}</th>
			<th>{{Allumé(e) ?}}</th>
			<th>{{Vmware Tools ?}}</th>
			<th>{{OS}}</th>
			<th>{{Nb CPU}}</th>
			<th>{{Nb Coeur/CPU}}</th>
			<th>{{RAM (Go)}}</th>
			<th>{{Snapshot ?}}</th>
		</tr>
	</thead>
	
	<tbody>	
<?php
$cpt = 1; // Initialisation de la variable cpt pour changer le look une ligne sur deux dans le tableau qui sera affiché
foreach ($eqLogics as $eqLogic) {
			/*switch($cpt) { // Définition du style pour les lignes, On change de style une ligne sur deux
					case 1:
						echo "<tr style='padding: 5px; border-width:1px;  border-style:solid;  border-color:black; border-collapse: collapse; background-color:#b8d4e0';>";
						$cpt=2;
						break;
					case 2:
						echo "<tr style='padding: 5px; border-width:1px;  border-style:solid;  border-color:black; border-collapse: collapse; background-color:#D3DFEE';>";
						$cpt=1;
						break;
			}*/
			
			//$styleTD = "style='padding: 5px; border-width:1px;  border-style:solid;  border-color:black; border-collapse: collapse; color: #000000;'"; // Définition du style pour les colonnes
			// exemple de code pour avoir la couleur en paramètre  :  //$listing .= "<td style='padding: 5px; border-width:1px;  border-style:solid;  border-color:black; border-collapse: collapse; background-color:$color';>";
			$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
			if ($eqLogic->getConfiguration('type') == 'ESXi') {
				$img = '<img class="lazy" src="plugins/vmware/docs/assets/images/icone_esxi.png" height="55" width="55" style="' . $opacity . '"/>';
			}else{
				$img = '<img class="lazy" src="' . $plugin->getPathImgIcon() . '" height="55" width="55" style="' . $opacity . '"/>';	
			}
			$styleTD = "style='font-size : 1em; cursor : default;'";
		echo '<tr><td>' . $img .'</td>';
			echo "<td $styleTD>";
			echo '<a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">'.$eqLogic->getHumanName(true).'';
			echo "</span></td>";
			
			echo "<td $styleTD>";
			if ($eqLogic->getConfiguration('type') == 'ESXi') {
				echo $eqLogic->getConfiguration('ipAddress');
			}else {
				echo $eqLogic->getConfiguration('vmIPAddress');
			}
			echo "</span></td>";
			
			$online = $null;
			echo "<td $styleTD>";
			if ($eqLogic->getConfiguration('type') == 'ESXi') {
				echo '   N/A   ';
			}else {
				$onlinecmd = $eqLogic->getCmd('info','online');
				if (is_object($onlinecmd)) {
					$online = $onlinecmd->execCmd();
					if ($online == 'Oui'){
						$online = '<span class="label label-success" style="font-size : 1em;" title="{{Oui}}"><i class="fas fa-check"></i></span>';
					} else {
						$online = '<span class="label label-danger" style="font-size : 1em;" title="{{Non}}"><i class="fas fa-times"></i></span>';	
					}
				}
				echo $online;
			}				
			echo "</td>";
			/////echo "</span></td>";
			//echo "<td $styleTD>";
			//echo str_replace(array("notRunning","running"), array("NON","OUI"), $eqLogic->getConfiguration('Started'));
			//echo "</span></td>";			  
			
			$vmwareToolsStatus = $null;
			echo "<td $styleTD>";
			if ($eqLogic->getConfiguration('type') == 'ESXi') {
				echo '   N/A   ';
			}else {
				$onlinecmd = $eqLogic->getCmd('info','vmwareTools');
				if (is_object($onlinecmd)) {
					$vmwareToolsStatus = $onlinecmd->execCmd();
					if ($vmwareToolsStatus == 'Pas à jour'){
						$vmwareToolsStatus = '<span class="label label-warning" style="font-size : 1em;" title="{{Pas à jour}}"><i class="fas fa-cog"></i></span>';
					}else if ($vmwareToolsStatus == 'Pas installé'){
						$vmwareToolsStatus = '<span class="label label-danger" style="font-size : 1em;" title="{{Pas installé}}"><i class="fas fa-times"></i></span>';	
					}else if ($vmwareToolsStatus == 'Démarré'){
						$vmwareToolsStatus = '<span class="label label-success" style="font-size : 1em;" title="{{Démarré}}"><i class="fas fa-check"></i></span>';	
					}else if ($vmwareToolsStatus == 'Pas démarré'){
						$vmwareToolsStatus = '<span class="label label-warning" style="font-size : 1em;" title="{{Pas démarré}}"><i class="fas fa-check"></i></span>';	
					}else {
						$vmwareToolsStatus = $vmwareToolsStatus;
					}
					//Pas à jour","Pas installé","Démarré","Pas démarré
				}
				echo $vmwareToolsStatus;			
			}				
			echo "</td>";
			//echo "</span></td>";
			
			
			$guestType = $null;
			echo "<td $styleTD>";
			$guestTypecmd = $eqLogic->getCmd('info','osType');
			if (is_object($guestTypecmd)) {
				$guestType = $guestTypecmd->execCmd();
			}
			echo $guestType;			
			echo "</span></td>";
			
			$numCpu = $null;
			echo "<td $styleTD>";
			$numCpucmd = $eqLogic->getCmd('info','cpuNumber');
			if (is_object($numCpucmd)) {
				$numCpu = $numCpucmd->execCmd();
			}
			echo $numCpu;
			echo "</span></td>";
			
			$numCoreCpu = $null;
			echo "<td $styleTD>";
			$numCoreCpucmd = $eqLogic->getCmd('info','corePerCpuNumber');
			if (is_object($numCoreCpucmd)) {
				$numCoreCpu = $numCoreCpucmd->execCmd();
			}
			echo $numCoreCpu;
			echo "</span></td>";
			
			$ramTotal = $null;
			echo "<td $styleTD>";
			$ramTotalCmd = $eqLogic->getCmd('info','ramTotal');
			if (is_object($ramTotalCmd)) {
				$ramTotal = $ramTotalCmd->execCmd();
			}
			echo $ramTotal;
			echo "</span></td>";
			
		//	echo "<td $styleTD>";
		//	echo $eqLogic->getConfiguration('ramQuantity');
		//	echo "</span></td>";
			
			$snapShotList = $null;
			echo "<td $styleTD>";
			if ($eqLogic->getConfiguration('type') == 'ESXi') {
				echo '   N/A   ';
			}else {
				$snapShotListCmd = $eqLogic->getCmd('info','snapShotList');
				if (is_object($snapShotListCmd)) {
					$snapShotList = $snapShotListCmd->execCmd();
				}
				echo $snapShotList;
			}	
			echo "</span></td>";						
		echo "</tr>"; 
	}			
?>
	</tbody>
</table>