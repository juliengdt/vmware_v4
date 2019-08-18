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
			<th>{{Nom de la VM}}</th>
			<th>{{Adresse IP}}</th>
			<th>{{Allumée ?}}</th>
			<th>{{OS}}</th>
			<th>{{Nb CPU}}</th>
			<th>{{Nb Coeur/CPU}}</th>
			<th>{{RAM}}</th>
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
			$styleTD = "style='font-size : 1em; cursor : default;'";
			echo '<tr>';
			echo "<td $styleTD>";
			echo '<a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">'.$eqLogic->getHumanName(true).'';
			echo "</span></td>";
			
			echo "<td $styleTD>";
			echo $eqLogic->getConfiguration('vmIPAddress');
			echo "</span></td>";
			
			$online = $null;
			echo "<td $styleTD>";
			$onlinecmd = $eqLogic->getCmd('info','online');
			if (is_object($onlinecmd)) {
				$online = $onlinecmd->execCmd();
			}
			echo $online;			
			echo "</span></td>";
			//echo "<td $styleTD>";
			//echo str_replace(array("notRunning","running"), array("NON","OUI"), $eqLogic->getConfiguration('Started'));
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
			$snapShotListCmd = $eqLogic->getCmd('info','snapShotList');
			if (is_object($snapShotListCmd)) {
				$snapShotList = $snapShotListCmd->execCmd();
			}
			echo $snapShotList;
			echo "</span></td>";
			
		//	echo "<td $styleTD>";
		//	echo $eqLogic->getConfiguration('snapList');
		//	echo "</td>";
			
			echo "</tr>"; 
	}			
?>
	</tbody>
</table>