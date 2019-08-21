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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
    
    //ajax::init();

	if (init('action') == 'synchronisation') {
		//$eqLogic = vmware::byId(init('id'));
		log::add('vmware', 'debug', 'DEBUG action synchronisation fichier ajax - DEBUT ');
		$eqLogic = vmware::byId(init('id'));
		log::add('vmware', 'debug', 'DEBUG action synchronisation fichier ajax - Juste après la récupération par l\'ID');
		if ($eqLogic->getIsEnable() == 1) { //Vérifie que l'équipement est actif
			$cmd = $eqLogic->getCmd(null, 'refresh'); // stocke la commande refresh, si elle existe
			if (!is_object($cmd)) { // si la commande n'existe pas on continue à la chercher via le foreach
				continue; 
			}		
			log::add('vmware', 'debug', 'Debut appel refresh via le bouton de synchronisation de la page équipement de l\'ESXi dont l\'ID est : '. init('id') .' et le nom est :  '. $eqLogic->getConfiguration('name') .'');
			//ajax::success($cmd->execCmd());
			$cmd->execCmd(); // on a trouvé la commande, on l'exécute (Pas besoin d'une boucle else ? se renseigner sur la commande continue, semble permettre de sortir de la boucle;
			log::add('vmware', 'debug', 'Fin du refresh via le bouton synchronisation de la page équipement');
		// }
		ajax::success();
	}

    throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}

