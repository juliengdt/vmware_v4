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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

//log::add('vmware', 'debug', 'On est dans la fonction PowerCli - On va interroger l\'ESXi'); 
//log::add('vmware', 'alert', 'On est dans la boucle PowerCli - Alerte'); 
//log::add('vmware', 'emergency', 'On est dans la boucle PowerCli - Emergency'); 
//log::add('vmware', 'critical', 'On est dans la boucle PowerCli - Critical'); 
//log::add('vmware', 'error', 'On est dans la boucle PowerCli - Error'); 
//log::add('vmware', 'warning', 'On est dans la boucle PowerCli - Warning'); 
//log::add('vmware', 'notice', 'On est dans la boucle PowerCli - Notice'); 
//log::add('vmware', 'info', 'On est dans la boucle PowerCli - Info'); 
		
class vmware extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */
    
      //Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {
		log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '============== Début du log - Cron Hourly ==============');
		log::add('vmware', 'info', '========================================================');
		foreach (self::byType('vmware') as $vmware) { // parcours tous les équipements du plugin vmware
			if ($vmware->getIsEnable() == 1) { //Vérifie que l'équipement est actif
				$cmd = $vmware->getCmd(null, 'refresh'); // stocke la commande refresh, si elle existe
				if (!is_object($cmd)) { // si la commande n'existe pas on continue à la chercher via le foreach
					continue; 
				}				
          		
				log::add('vmware', 'info', 'début du refresh via le cron jeedom toutes les heures');
				$cmd->execCmd(); // on a trouvé la commande, on l'exécute (Pas besoin d'une boucle else ? se renseigner sur la commande continue, semble permettre de sortir de la boucle;
				log::add('vmware', 'info', 'Fin du refresh via le cron hourly de jeedom');
			}
		}
		log::add('vmware', 'info', 'Fin de la fonction Cron Hourly');
      }

    /*     * *********************Méthodes d'instance************************* */

    /*public function preInsert() {
        
    }

    public function postInsert() {
        
    }*/

    public function preSave() {
		log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '================= Début du log PreSave =================');
		log::add('vmware', 'info', '========================================================');
			  
		/*if($this->getConfiguration("type") == 'ESXi'){ // Création des commandes spécifiques au ESXi
					
			$nbVM = $this->getCmd(null, 'nbVM');
			if (!is_object($nbVM)) {
				$nbVM = new vmwareCmd();
				$nbVM->setName(__('Nombre de VM', __FILE__));
			}
			$nbVM->setLogicalId('nbVM');
			$nbVM->setEqLogic_id($this->getId());
			$nbVM->setType('info');
			$nbVM->setSubType('string');
			$nbVM->save();
			log::add('vmware', 'info', 'Création de la commande Nombre de VM dans l\'équipement ESXi');
			
			$vmList = $this->getCmd(null, 'vmList');
			if (!is_object($vmList)) {
				$vmList = new vmwareCmd();
				$vmList->setName(__('Liste des VMs', __FILE__));
			}
			$vmList->setLogicalId('vmList');
			$vmList->setEqLogic_id($this->getId());
			$vmList->setType('info');
			$vmList->setSubType('string');
			$vmList->save();	 
			log::add('vmware', 'info', 'Création de la commande VMList dans l\'équipement ESXi');
		}*/
		
		if($this->getConfiguration("type",'none') == 'none'){
			$this->setCategory('automatism', 1);
			$this->setConfiguration('type','ESXi');
			$this->setConfiguration('name',$this->getName());
			$this->setConfiguration('esxiHost',$this->getName());
			$this->setLogicalId('vmware'.$this->getName());
			log::add('vmware', 'debug', 'C\'est un ESXi, on vient d\'ajouter des paramètres à sa configuration');
		}
		
		log::add('vmware', 'info', 'Fin du log - Fonction preSave');
    }

    public function postSave() {
			log::add('vmware', 'info', '========================================================');
			log::add('vmware', 'info', '================= Début du log PostSave ================');
			log::add('vmware', 'info', '========================================================');
			
			if($this->getConfiguration("type") == 'ESXi'){ // Création des commandes spécifiques au ESXi
					
				$nbVM = $this->getCmd(null, 'nbVM');
				if (!is_object($nbVM)) {
					$nbVM = new vmwareCmd();
					$nbVM->setName(__('Nombre de VM', __FILE__));
				}
				$nbVM->setLogicalId('nbVM');
				$nbVM->setEqLogic_id($this->getId());
				$nbVM->setType('info');
				$nbVM->setSubType('string');
				$nbVM->save();
				log::add('vmware', 'info', 'Création de la commande Nombre de VM dans l\'équipement ESXi');
				
				$vmList = $this->getCmd(null, 'vmList');
				if (!is_object($vmList)) {
					$vmList = new vmwareCmd();
					$vmList->setName(__('Liste des VMs', __FILE__));
				}
				$vmList->setLogicalId('vmList');
				$vmList->setEqLogic_id($this->getId());
				$vmList->setType('info');
				$vmList->setSubType('string');
				$vmList->save();	 
				log::add('vmware', 'info', 'Création de la commande VMList dans l\'équipement ESXi');
				
				$ramTotal = $this->getCmd(null, 'ramTotal');
				if (!is_object($ramTotal)) {
					$ramTotal = new vmwareCmd();
					$ramTotal->setName(__('Total RAM', __FILE__));
					$ramTotal->setLogicalId('ramTotal');
					$ramTotal->setEqLogic_id($this->getId());
					$ramTotal->setType('info');
					$ramTotal->setSubType('string');
					$ramTotal->save();	 
					log::add('vmware', 'info', 'Création de la commande Ram Total dans l\'équipement ESXi');
				}
												
				$cpuNumber = $this->getCmd(null, 'cpuNumber');
				if (!is_object($cpuNumber)) {
					$cpuNumber = new vmwareCmd();
					$cpuNumber->setName(__('Quantité CPU', __FILE__));
					$cpuNumber->setLogicalId('cpuNumber');
					$cpuNumber->setEqLogic_id($this->getId());
					$cpuNumber->setType('info');
					$cpuNumber->setSubType('string');
					$cpuNumber->save();	 
					log::add('vmware', 'info', 'Création de la commande Quantité CPU dans l\'équipement ESXi');
				}
								
				$corePerCpuNumber = $this->getCmd(null, 'corePerCpuNumber');
				if (!is_object($corePerCpuNumber)) {
					$corePerCpuNumber = new vmwareCmd();
					$corePerCpuNumber->setName(__('Quantité Coeur par CPU', __FILE__));
					$corePerCpuNumber->setLogicalId('corePerCpuNumber');
					$corePerCpuNumber->setEqLogic_id($this->getId());
					$corePerCpuNumber->setType('info');
					$corePerCpuNumber->setSubType('string');
					$corePerCpuNumber->save();	 
					log::add('vmware', 'info', 'Création de la commande Quantité Coeur par CPU dans l\'équipement ESXi');
				}
								
				$osType = $this->getCmd(null, 'osType');
				if (!is_object($osType)) {
					$osType = new vmwareCmd();
					$osType->setName(__('Système Exploitation', __FILE__));
					$osType->setLogicalId('osType');
					$osType->setEqLogic_id($this->getId());
					$osType->setType('info');
					$osType->setSubType('string');
					$osType->save();	 
					log::add('vmware', 'info', 'Création de la commande Système Exploitation dans l\'équipement ESXi');
				}	
			}
			
			
			if($this->getConfiguration("type") == 'vm'){//Création des commandes spécifiques aux VMS
				log::add('vmware', 'debug', 'Func : postSave - Création des commandes spécfiques à une VM - s\'il en manque');
				// Commandes Information				
				$nbSnap = $this->getCmd(null, 'nbSnap');
				if (!is_object($nbSnap)) {
					$nbSnap = new vmwareCmd();
					$nbSnap->setName(__('Nombre de snapshots', __FILE__));
					$nbSnap->setLogicalId('nbSnap');
					$nbSnap->setEqLogic_id($this->getId());
					$nbSnap->setType('info');
					$nbSnap->setSubType('string');
					$nbSnap->save();	 
					log::add('vmware', 'info', 'Création de la commande Nombre de snapshot sur une VM');
				}
								
				$snapShotList = $this->getCmd(null, 'snapShotList'); // A modifier en liste peut-être ?
				if (!is_object($snapShotList)) {
					$snapShotList = new vmwareCmd();
					$snapShotList->setName(__('Liste des snapshots', __FILE__));
					$snapShotList->setLogicalId('snapShotList');
					$snapShotList->setEqLogic_id($this->getId());
					$snapShotList->setType('info');
					$snapShotList->setSubType('string');
					$snapShotList->save();	 
					log::add('vmware', 'info', 'Création de la commande Liste des snapshots sur une VM');
				}
								
				$ramTotal = $this->getCmd(null, 'ramTotal');
				if (!is_object($ramTotal)) {
					$ramTotal = new vmwareCmd();
					$ramTotal->setName(__('Total RAM', __FILE__));
					$ramTotal->setLogicalId('ramTotal');
					$ramTotal->setEqLogic_id($this->getId());
					$ramTotal->setType('info');
					$ramTotal->setSubType('string');
					$ramTotal->save();	 
					log::add('vmware', 'info', 'Création de la commande Ram Total sur une VM');
				}
												
				$cpuNumber = $this->getCmd(null, 'cpuNumber');
				if (!is_object($cpuNumber)) {
					$cpuNumber = new vmwareCmd();
					$cpuNumber->setName(__('Quantité CPU', __FILE__));
					$cpuNumber->setLogicalId('cpuNumber');
					$cpuNumber->setEqLogic_id($this->getId());
					$cpuNumber->setType('info');
					$cpuNumber->setSubType('string');
					$cpuNumber->save();	 
					log::add('vmware', 'info', 'Création de la commande Quantité CPU sur une VM');
				}
								
				$corePerCpuNumber = $this->getCmd(null, 'corePerCpuNumber');
				if (!is_object($corePerCpuNumber)) {
					$corePerCpuNumber = new vmwareCmd();
					$corePerCpuNumber->setName(__('Quantité Coeur par CPU', __FILE__));
					$corePerCpuNumber->setLogicalId('corePerCpuNumber');
					$corePerCpuNumber->setEqLogic_id($this->getId());
					$corePerCpuNumber->setType('info');
					$corePerCpuNumber->setSubType('string');
					$corePerCpuNumber->save();	 
					log::add('vmware', 'info', 'Création de la commande Quantité Coeur par CPU sur une VM');
				}
								
				$osType = $this->getCmd(null, 'osType');
				if (!is_object($osType)) {
					$osType = new vmwareCmd();
					$osType->setName(__('Système Exploitation', __FILE__));
					$osType->setLogicalId('osType');
					$osType->setEqLogic_id($this->getId());
					$osType->setType('info');
					$osType->setSubType('string');
					$osType->save();	 
					log::add('vmware', 'info', 'Création de la commande Système Exploitation sur une VM');
				}				
				
				$online = $this->getCmd(null, 'online');
				if (!is_object($online)) {
					$online = new vmwareCmd();
					$online->setName(__('Online', __FILE__));
					$online->setLogicalId('online');
					$online->setEqLogic_id($this->getId());
					$online->setType('info');
					$online->setSubType('string');
					$online->save();	 
					log::add('vmware', 'info', 'Création de la commande OnLine sur une VM');
				}
				
				$vmwareToolsInstalled = $this->getCmd(null, 'vmwareTools');
				if (!is_object($vmwareToolsInstalled)) {
					$vmwareToolsInstalled = new vmwareCmd();
					$vmwareToolsInstalled->setName(__('Vmware Tools', __FILE__));
					$vmwareToolsInstalled->setLogicalId('vmwareTools');
					$vmwareToolsInstalled->setEqLogic_id($this->getId());
					$vmwareToolsInstalled->setType('info');
					$vmwareToolsInstalled->setSubType('string');
					$vmwareToolsInstalled->save();	 
					log::add('vmware', 'info', 'Création de la commande vmwareToolsInstalled sur une VM');
				}
				
				// Commandes Action 
				$takeSnapshot = $this->getCmd('action', 'takeSnapshot');
				if (!is_object($takeSnapshot)) {
					$takeSnapshot = new vmwareCmd();
					$takeSnapshot->setName(__('Prendre un snapshot', __FILE__));
					$takeSnapshot->setLogicalId('takeSnapshot');
					$takeSnapshot->setEqLogic_id($this->getId());
					$takeSnapshot->setType('action');
					$takeSnapshot->setSubType('message');
					$takeSnapshot->setDisplay('title_placeholder', __('Nom - Description', __FILE__)); // uniquement deux paramètres, ne pas changer le nom title_placeholder
					$takeSnapshot->setDisplay('message_placeholder', __('Memory', __FILE__)); // uniquement deux paramètres, ne pas changer le nom message_placeholder
					$takeSnapshot->setIsVisible(0);
					$takeSnapshot->save();
					log::add('vmware', 'info', 'Création de la commande Prendre un snapshot sur une VM');
				}
				
				$deleteSnapshot = $this->getCmd('action', 'deleteSnapshot');
				if (!is_object($deleteSnapshot)) {
					$deleteSnapshot = new vmwareCmd();
					$deleteSnapshot->setName(__('Supprimer un snapshot', __FILE__));
					$deleteSnapshot->setLogicalId('deleteSnapshot');
					$deleteSnapshot->setEqLogic_id($this->getId());
					$deleteSnapshot->setType('action');
					$deleteSnapshot->setSubType('message');
					$deleteSnapshot->setDisplay('title_placeholder', __('Nom du snap', __FILE__)); // uniquement deux paramètres, ne pas changer le nom title_placeholder
					$deleteSnapshot->setIsVisible(0);
					$deleteSnapshot->save();	 
					log::add('vmware', 'info', 'Création de la commande Supprimer un snapshot sur une VM');
				}
				
				$reboot = $this->getCmd('action', 'reboot');
				if (!is_object($reboot)) {
					$reboot = new vmwareCmd();
					$reboot->setName(__('Reboot Hard', __FILE__));
					$reboot->setLogicalId('reboot');
					$reboot->setEqLogic_id($this->getId());
					$reboot->setType('action');
					$reboot->setSubType('other');
					$reboot->setIsVisible(0);
					$reboot->save();
					log::add('vmware', 'info', 'Création de la commande Reboot Hard sur une VM');
				}
								
				$rebootOS = $this->getCmd('action', 'rebootOS');
				if (!is_object($rebootOS)) {
					$rebootOS = new vmwareCmd();
					$rebootOS->setName(__('Reboot OS', __FILE__));
					$rebootOS->setLogicalId('rebootOS');
					$rebootOS->setEqLogic_id($this->getId());
					$rebootOS->setType('action');
					$rebootOS->setSubType('other');
					$rebootOS->setIsVisible(0);
					$rebootOS->save();
					log::add('vmware', 'info', 'Création de la commande Reboot OS sur une VM');
				}
						
				$stop = $this->getCmd('action', 'stop');
				if (!is_object($stop)) {
					$stop = new vmwareCmd();
					$stop->setName(__('Stop Hard', __FILE__));
					$stop->setLogicalId('stop');
					$stop->setEqLogic_id($this->getId());
					$stop->setType('action');
					$stop->setSubType('other');
					$stop->setIsVisible(0);
					$stop->save();
					log::add('vmware', 'info', 'Création de la commande Stop Hard sur une VM');
				}
				
				$stopOS = $this->getCmd('action', 'stopOS');
				if (!is_object($stopOS)) {
					$stopOS = new vmwareCmd();
					$stopOS->setName(__('Stop OS', __FILE__));
					$stopOS->setLogicalId('stopOS');
					$stopOS->setEqLogic_id($this->getId());
					$stopOS->setType('action');
					$stopOS->setSubType('other');
					$stopOS->setIsVisible(0);
					$stopOS->save();
					log::add('vmware', 'info', 'Création de la commande Stop OS sur une VM');
				}
				
				$powerOn = $this->getCmd('action', 'powerOn');
				if (!is_object($powerOn)) {
					$powerOn = new vmwareCmd();
					$powerOn->setName(__('Power On', __FILE__));
					$powerOn->setLogicalId('powerOn');
					$powerOn->setEqLogic_id($this->getId());
					$powerOn->setType('action');
					$powerOn->setSubType('other');
					$powerOn->setIsVisible(0);
					$powerOn->save();
					log::add('vmware', 'info', 'Création de la commande Power On sur une VM');
				}			
			}
      		
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new vmwareCmd();
			$refresh->setName(__('Rafraichir', __FILE__));
			$refresh->setEqLogic_id($this->getId());
			$refresh->setLogicalId('refresh');
			$refresh->setType('action');
			$refresh->setSubType('other');
			$refresh->save();
			log::add('vmware', 'info', 'Création de la commande Refresh dans l\'équipement ESXi');
		}
		log::add('vmware', 'info', 'Fin du log - Fonction postSave');
	}

    public function preUpdate() {
		log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '================ Début du log PreUpdate ================');
		log::add('vmware', 'info', '========================================================');
		/*if($this->getConfiguration("type") != 'vm'){ // 
			$this->setCategory('automatism', 1);
			$this->setConfiguration('type','ESXi');
			$this->setConfiguration('name',$this->getName());
			$this->setConfiguration('esxiHost',$this->getName());
			$this->setLogicalId('vmware'.$this->getName());
			log::add('vmware', 'debug', 'C\'est un ESXi, on vient d\'ajouter des paramètres à sa configuration');
		}*/
		log::add('vmware', 'debug', 'Fin fonction preUpdate');
    }

    public function postUpdate() {
		log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '=============== Début du log PostUpdate ================');
		log::add('vmware', 'info', '========================================================');
		$cmd = $this->getCmd(null, 'refresh'); // On appelle la commande refresh de l’équipement à chaque fois que l'on update l'équipement du plugin vmware (clic sur sauvegarder)
		if (is_object($cmd)) { //Elle existe on lance la commande
			 $cmd->execCmd();
		}
		log::add('vmware', 'debug', 'Fin fonction postUpdate');
    }
	
	public function getVmInformationList() {
      	log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '========== Début du log getVmInformationList ===========');
		log::add('vmware', 'info', '========================================================');
		
		$timeStartFunction = microtime(true); //get initial start time
		$password = $this->getConfiguration("passwordSSH"); // on récupère le password
		$login = $this->getConfiguration("login"); // on récupère le login
		$hostIP = $this->getConfiguration("ipAddress"); // on récupère l'adresseIP
		$ESXiHostName = $this->getConfiguration("esxiHost"); // on récupère l'adresseIP
  
		log::add('vmware', 'debug', 'Login utilisé : ' . $login . ' - Ip de l\'ESXi : ' . $hostIP); 
			
		$timeStart = microtime(true); //get initial start time
		if (!$connection = ssh2_connect($hostIP,'22')) {
				return 'error connecting';
				log::add('vmware', 'error', 'ESXi injoignable');
		}else{
			log::add('vmware', 'info', 'ESXi joignable');
		}
			
		if (!ssh2_auth_password($connection,$login,$password)){
				return 'error connecting';
				log::add('vmware', 'error', 'Connexion KO à l\'ESXi');
		}else {
			log::add('vmware', 'info', 'Connexion OK à l\'ESXi');
		}
		
		log::add('vmware', 'debug', 'On appelle la commande qui liste les VMs sur l\'ESXi'); 
	
		// Ancienne requete -> renvoyait un tableau complet, mais lors de commentaire avec retour à la ligne, celà plantait la fonction dans Jeedom $request = "vim-cmd vmsvc/getallvms | awk '{print $0\"9999999\"}'";
		//$request = "vim-cmd vmsvc/getallvms | awk -F '[[:space:]][[:space:]]+' '{print $1\":\"$2\":\"$4\":\"$5\":9999999\"}'";
		// Retourne en 0 l'ID   / en 1 le nom de la VM  / en 2 le type de l'OS  / en 3 la version Hardware de la machine  / en 4 9999999 pour la séparation coté jeedom
		//$request = "vim-cmd vmsvc/getallvms | sed -e '1d' -e 's/ \vmx-.*$//' | awk '$1 ~ /^[0-9]+$/ {print $1\":\"$2\":\"$5\":9999999\"}'"; // truncate line at vmx-
		// Retourne en 0 l'ID   / en 1 le nom de la VM  / en 2 le type de l'OS / en 3 9999999 pour la séparation coté jeedom
		$request = "vim-cmd vmsvc/getallvms | sed -e '1d' -e 's/ \[.*$//' | awk '$1 ~ /^[0-9]+$/ {print $1\":\"substr($0,8,80)\":9999999\"}'";
		//Retourne en 0 l'ID / en 1 le nom / en 2 9999999 pour la séparation coté jeedom de chaque ligne
		$result = ssh2_exec($connection, $request . ' 2>&1');
		stream_set_blocking($result, true);
		$vmlist = stream_get_contents($result);
		//$typeOfVmList = gettype($vmlist);
		$vmListArray = explode("9999999", $vmlist); // Conversion en tableau à chaque fois que l'on trouve la suite de charactères 9999999
		//$typeOfVmListArray = gettype($vmListArray);
		
	//	$vmlistTest = str_replace("9999999","+",$vmlist);
	//	$vmListArrayTest = explode("+", $vmlistTest); // Conversion en tableau à chaque fois que l'on trouve la suite de charactères 9999999
		
		//$firstLineRemoved = array_shift($vmListArray); // on supprime la première ligne du tableau avec les entêtes plus besoin car sed s'occupe de supprimer la première ligne
		$lastLineRemoved = array_pop($vmListArray); // on supprime la dernière ligne du tableau car elle est vide
		
		log::add('vmware', 'info', 'Contenu de la variable qui contient le retour de la commande Liste VM'); 
		log::add('vmware', 'info', $vmlist); 
		
		log::add('vmware', 'debug', 'Contenu du tableau PHP - premier élement'); 
		log::add('vmware', 'debug', $vmListArray[0]);
		
		log::add('vmware', 'debug', 'Contenu du tableau PHP - deuxième élement'); 
		log::add('vmware', 'debug', $vmListArray[1]);			
							
		$cpt =0; // initialisation d'un compteur pour remplir le tableau à deux dimensions qui va contenir la liste des VMs
		foreach ($vmListArray as $vm) {
			//log::add('vmware', 'debug', 'Contenu de la variable VM dans le foreach AVANT les trim et rtrim'); 
			//log::add('vmware', 'debug', $vm);
		
			$snapList = ""; // on initialise à vide la variable snapList
			// Ancienne version, avant de modifier la requête qui récupèrer les VMs et séparent les éléments avec des : $vmLineArray = preg_split('/\h{2,}/',$vm); // on éclate la ligne contenant le détail d'une VM en un tableau
			$vm = trim($vm); // on supprime les espaces au début et à la fin
			$vm = rtrim($vm,':');  // nettoyage de la variable pour enlever le dernier : qui ne sera pas utile
			$vmLineArray = preg_split('/:/',$vm); // on éclate la ligne contenant le détail d'une VM en un tableau en se basant sur les : on est obligé de les entourer de /
			//log::add('vmware', 'debug', 'Contenu de la variable VM dans le foreach APRES les trim et rtrim'); 
			//log::add('vmware', 'debug', $vm);
		
			//log::add('vmware', 'debug', 'ForEach Création tableau contenant les VMs Element 0 '.$vmLineArray[0].''); 
			//log::add('vmware', 'debug', 'ForEach Création tableau contenant les VMs Element 1 '.$vmLineArray[1].''); 
			//log::add('vmware', 'debug', 'ForEach Création tableau contenant les VMs Element 2 '.$vmLineArray[2].''); 
			//log::add('vmware', 'debug', 'ForEach Création tableau contenant les VMs Element 3 '.$vmLineArray[3].''); 
			log::add('vmware', 'debug', 'ForEach Création tableau contenant les VMs - '.$vmLineArray[1].''); 
			
			// stockage des informations récupérées lors de l'interrogation de la liste des vms
			$ID = intval($vmLineArray[0]);
			$vmName = $vmLineArray[1];
			//$osType = $vmLineArray[2];
			//$osType = str_replace("Guest","",$vmLineArray[2]);
			//$hardwareVersion = $vmLineArray[3];
			//$description = $vmLineArray[5];
			
			// On va chercher les informations qu'il nous manque
			// Récupération et stockage de l'état de la VM (allumée ou éteinte)
			//$_request = "vim-cmd vmsvc/get.guest ".$ID ." | grep guestState | sed -n 1p | cut -d '\"' -f 2";
			$_request = "vim-cmd vmsvc/power.getstate ".$ID." | sed -n 2p";
			$result = ssh2_exec($connection, $_request . ' 2>&1');
			stream_set_blocking($result, true);
			$started = stream_get_contents($result);
			$started = preg_replace("#\n|\t|\r#","",$started); // on supprime les retours à la ligne et autre retour chariots

			// Récupération et stockage de l'IP si la VM est allumée, sinon on ne peut pas l'obtenir
			//if($started == "running") {
			if($started == "Powered on") {
				$_request = "vim-cmd vmsvc/get.guest ".$ID ." | grep ipAddress | sed -n 1p | cut -d '\"' -f 2";				
				$result = ssh2_exec($connection, $_request . ' 2>&1');
				stream_set_blocking($result, true);
				$IPAddress = stream_get_contents($result);
				$IPAddress = preg_replace("#\n|\t|\r#","",$IPAddress); // on supprime les retours à la ligne et autre retour chariots
			}else {
				$IPAddress = "Not_Found";
			}
			
			// Récupération et stockage de l'OS
			//if($started == "running") {
			if($started == "Powered on") {
				$_request = "vim-cmd vmsvc/get.guest ".$ID ." | grep guestId | cut -d '\"' -f 2";
				$result = ssh2_exec($connection, $_request . ' 2>&1');
				stream_set_blocking($result, true);
				$osType = stream_get_contents($result);
				$osType = preg_replace("#\n|\t|\r#","",$osType); // on supprime les retours à la ligne et autre retour chariots
				$osType = str_replace("Guest","",$osType);
			}else {
				$osType = "Not Found";
			}
			// Récupération et stockage du nombre de CPU
			$_request = "vim-cmd vmsvc/get.config ".$ID." | grep numCPU | sed -n 1p | cut -d '=' -f 2 | cut -d ',' -f 1 | cut -d ' ' -f 2";
			$result = ssh2_exec($connection, $_request . ' 2>&1');
			stream_set_blocking($result, true);
			$cpuNb = stream_get_contents($result);
			$cpuNb = preg_replace("#\n|\t|\r#","",$cpuNb); // on supprime les retours à la ligne et autre retour chariots
			
			// Récupération et stockage du nombre de coeurParCPU
			$_request = "vim-cmd vmsvc/get.config ".$ID ." | grep numCoresPerSocket | sed -n 1p | cut -d '=' -f 2 | cut -d ',' -f 1 | cut -d ' ' -f 2";
			$result = ssh2_exec($connection, $_request . ' 2>&1');
			stream_set_blocking($result, true);
			$corePerCpu = stream_get_contents($result);
			$corePerCpu = preg_replace("#\n|\t|\r#","",$corePerCpu); // on supprime les retours à la ligne et autre retour chariots
		   
			// Récupération et stockage de la quantité de RAM déclarée sur la VM
			$_request = "vim-cmd vmsvc/get.config ".$ID ." | grep memoryMB | sed -n 1p | cut -d '=' -f 2 | cut -d ',' -f 1 | cut -d ' ' -f 2";
			$result = ssh2_exec($connection, $_request . ' 2>&1');
			stream_set_blocking($result, true);
			$ramQuantity = stream_get_contents($result);
			$ramQuantity = preg_replace("#\n|\t|\r#","",$ramQuantity); // on supprime les retours à la ligne et autre retour chariots
			$ramQuantity = trim($ramQuantity);
			$ramGBQuantity = round(intval($ramQuantity) / 1024,2);
			log::add('vmware', 'debug', 'valeur en GB de la ram sur la VM ' . $ramGBQuantity); 
			
			// Récupération et stockage du nombre de snapshot déclaré sur la VM
	/*		$_request = "vim-cmd vmsvc/snapshot.get ".$ID ." | grep 'Snapshot Name' | wc -l";
			$result = ssh2_exec($connection, $_request . ' 2>&1');
			stream_set_blocking($result, true);
			$nbSnap = stream_get_contents($result);
			$nbSnap = preg_replace("#\n|\t|\r#","",$nbSnap); // on supprime les retours à la ligne et autre retour chariots et on met une virgule à la place
	*/		
			// Récupération et stockage de la liste des snapshots déclaré sur la VM
	/*		$_request = "vim-cmd vmsvc/snapshot.get ".$ID ." | grep 'Snapshot Name' | cut -d ':' -f 2 | cut -d ' ' -f 2-30";
			$result = ssh2_exec($connection, $_request . ' 2>&1');
			stream_set_blocking($result, true);
			$snapListing = stream_get_contents($result);
			if($nbSnap == 0) {
				$snapList = "NON";
				log::add('vmware', 'debug', '0 snapshot trouvé');
			}elseif ($nbSnap == 1){
				  $snapListArray = preg_split('/\s+/', $snapListing); // on crée un tableau en coupant au retour à la ligne
				  $lastLineRemovedSnapList = array_pop($snapListArray); // on supprime la dernière ligne du tableau car elle est vide
				  $snapList = implode(" ",$snapListArray);
				  //$snapList = $snapListArray ;
				  log::add('vmware', 'debug', '1 snapshot trouvé');
				  log::add('vmware', 'debug', $snapList);
			}elseif($nbSnap > 1){ // conversion de la ligne retournée en tableau dans le cas ou l'on aurait plusieurs snapshot
				  $snapListArray = preg_split('/\r\n|\r|\n/', $snapListing); // on crée un tableau en coupant au retour à la ligne
				  $lastLineRemovedSnapList = array_pop($snapListArray); // on supprime la dernière ligne du tableau car elle est vide
				  $snapList = $snapListArray;
				  log::add('vmware', 'debug', 'X snapshots trouvés');
			}
		*/	
			$vmListFull[$cpt]=array(
			'id' => $ID,
			'Name' => $vmName,
			'IPAddress' => $IPAddress,
			'GuestId' => $osType,
			'NumCpu' => $cpuNb,
			'CoresPerSocket' => $corePerCpu,
			//'MemoryGB' => $ramQuantity,
			'MemoryGB' => $ramGBQuantity,
			//'HardwareVersion' => $hardwareVersion,
			//'SnapName' => $snapList,
			'PowerState' => $started,
			//'Description' => $description	
			);
			$cpt = $cpt+1;
			$vmNameList = $vmName."<br>".$vmNameList;
		}
		$vmNameList = "<br>".$vmNameList."<br>"; // Permet de faire un retour à la ligne avant et après la liste des VMs pour que ça soit plus propre sur le widget
		
		$closesession = ssh2_exec($connection, 'exit'); // Fermeture de la connexion SSH à l'hyperviseur
		stream_set_blocking($closesession, true);
		stream_get_contents($closesession);
		
		$timeEnd = microtime(true); //get script end time
		$timePS = $timeEnd - $timeStart;//calculate the difference between start and stop
		log::add('vmware', 'debug', 'Durée de la requête pour lister les VMs ; ' . $timePS . ' secondes'); 
		
		log::add('vmware', 'debug', 'On appelle la fonction qui crée un équipement par VM'); 
		vmware::saveVmAsEquipment($vmListFull,$hostIP,$ESXiHostName); // On appelle la fonction qui va créer chaque VM comme un équipement à part entière
		log::add('vmware', 'debug', 'On vient de terminer l\'appel à la fonction qui doit créer un équipement par VM'); 
			
		$NbVM = count($vmListFull);// Permet de compter le nombre de VM et ensuite tout dans la class vmwareCmd et la fonction execute, s'en servir pour peupler la commande information Nombre de VM
		log::add('vmware', 'debug', 'Voici le nombre de VM trouvée : ' . $NbVM); 
		$NbVM = "<br>".count($vmListFull); // pour la mise en forme sur la tuile du widget de l'ESXi, on met un retour à la ligne pour que ça soit plus propre
		
		$timeEndFunction = microtime(true); //get script end time
		$time = $timeEndFunction - $timeStartFunction;//calculate the difference between start and stop
		log::add('vmware', 'info', 'Durée du traitement de la fonction getVmInformationList ; ' . $time . 's dont ' . $timePS . 's pour lister les VMs'); 
		log::add('vmware', 'info', 'Fin fonction getVmInformationList'); 
		$hoteESXi = $this->getName(); // permet d'obtenir le name de l'élément eqLogic en cours de traitement
		$type = "ESXi";
		
		return array($vmNameList,$NbVM,$hoteESXi,$type);
}
	
	public function saveVmAsEquipment($vmListFull,$ESXiHostIp,$ESXiHostName) {
		log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '============= Début du log saveVmAsEquipment ===========');
		log::add('vmware', 'info', '========================================================');
		
		log::add('vmware', 'debug', 'On va commencer la boucle foreach'); 
		foreach($vmListFull as $vm) {
		  log::add('vmware', 'info', 'On va traiter la VM : '.$vm['Name'].''); 
		  $deviceid = "";
		  $ipAddress = "";
		  $name = "";
		  $os = "";
		  $started = "";
		//  $nbSnapCount = 0;
		//  $snapList = "";
		  
		  $deviceid = $vm['Name'];
		  $ipAddress = $vm['IPAddress'];
		  $name = $vm['Name'];
		  $os = $vm['GuestId'];
		  //$started = str_replace(array("notRunning","running"), array("No","Yes"), $vm['PowerState'] );
		  $started = str_replace(array("Powered off","Powered on"), array("No","Yes"), $vm['PowerState'] );
		 
		  $vmware = self::byLogicalId('vmware'.$deviceid,'vmware'); // Création de l'enveloppe vide d'une VM
		  if (!is_object($vmware)) {
			$vmware = new vmware();
			$vmware->setLogicalId('vmware'.$deviceid);
			$vmware->setName($name);
			$vmware->setEqType_name('vmware');
			$vmware->setIsVisible(0);
			$vmware->setIsEnable(1);
		  }
					 
		  // On crée la liste pour les snapshots
		  /*if(is_array($vm['SnapName'])){
			  	foreach ($vm['SnapName'] as $snap){
				$snapList .= $snap . ",";
				$nbSnapCount = $nbSnapCount + 1 ;
				}
			}elseif($vm['SnapName'] != "NON"){
				$snapList = $vm['SnapName'];
				$nbSnapCount = "1";
				
			}elseif($vm['SnapName'] == "NON"){
				$snapList = "Aucun snapshot trouvé";
				$nbSnapCount = "0";
			}
			$snapList = rtrim($snapList, ',');  // nettoyage de la variable pour enlever la virgule à la fin de la liste
			*/			
			$vmware->setConfiguration('name',$vm['Name']);
			//$vmware->setConfiguration('ramQuantity',$vm['MemoryGB']);
			//$vmware->setConfiguration('Started',$started);
			//$vmware->setConfiguration('nbSnap',$nbSnapCount);
			$vmware->setConfiguration('vmIPAddress',$vm['IPAddress']);
			$vmware->setConfiguration('type', 'vm');
			//$vmware->setConfiguration('snapList', $snapList);
			$vmware->setConfiguration('ESXiHostIpAddress', $ESXiHostIp);
			$vmware->setConfiguration('esxiHost', $ESXiHostName);
			$vmware->save();				
			
			// Stockage des valeurs dans les commandes information
			//$vmware->checkAndUpdateCmd('nbSnap', $nbSnapCount); 
			//$vmware->checkAndUpdateCmd('snapShotList', $snapList); 
			$vmware->checkAndUpdateCmd('ramTotal', $vm['MemoryGB']); 
			$vmware->checkAndUpdateCmd('cpuNumber', $vm['NumCpu']); 
			$vmware->checkAndUpdateCmd('corePerCpuNumber', $vm['CoresPerSocket']); 
			$vmware->checkAndUpdateCmd('osType', $os); 
			$vmware->checkAndUpdateCmd('online', $started); 
		}
		log::add('vmware', 'info', 'Fin fonction saveVmAsEquipment'); 
	}
 
	public function getEsxiInformationList() {
		log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '========== Début du log getEsxiInformationList ===========');
		log::add('vmware', 'info', '========================================================');
		
		$password = $this->getConfiguration("passwordSSH"); // on récupère le password
		$login = $this->getConfiguration("login"); // on récupère le login
		$hostIP = $this->getConfiguration("ipAddress"); // on récupère l'adresseIP
		$ESXiHostName = $this->getConfiguration("esxiHost"); // on récupère l'adresseIP
  
		log::add('vmware', 'debug', 'Login utilisé : ' . $login . ' - Ip de l\'ESXi : ' . $hostIP); 
			
		if (!$connection = ssh2_connect($hostIP,'22')) {
				return 'error connecting';
				log::add('vmware', 'error', 'ESXi injoignable');
		}else{
			log::add('vmware', 'info', 'ESXi joignable');
		}
			
		if (!ssh2_auth_password($connection,$login,$password)){
				return 'error connecting';
				log::add('vmware', 'error', 'Connexion KO à l\'ESXi');
		}else {
			log::add('vmware', 'info', 'Connexion OK à l\'ESXi');
		}
		
		
		log::add('vmware', 'debug', 'On appelle la commande qui récupère la ram totale de l\'ESXi'); 
		$_request = "vim-cmd hostsvc/hostsummary | grep memorySize | cut -d '=' -f 2 | cut -d ',' -f 1";
		$result = ssh2_exec($connection, $_request . ' 2>&1');
		stream_set_blocking($result, true);
		$memoryESXi = stream_get_contents($result);
		$memoryESXi = preg_replace("#\n|\t|\r#","",$memoryESXi); // on supprime les retours à la ligne et autre retour chariots
		$memoryESXi = trim($memoryESXi);
		$memoryGBESXi = round(intval($memoryESXi) / 1024 / 1024 / 1024,2);
		log::add('vmware', 'debug', 'valeur en GB de la ram' . $memoryGBESXi); 
		
		log::add('vmware', 'debug', 'On appelle la commande qui récupère le nombre de CPU de l\'ESXi'); 
		$_request = "vim-cmd hostsvc/hostsummary | grep numCpuPkgs | cut -d '=' -f 2 | cut -d ',' -f 1";
		$result = ssh2_exec($connection, $_request . ' 2>&1');
		stream_set_blocking($result, true);
		$numCpuESXi = stream_get_contents($result);
		$numCpuESXi = preg_replace("#\n|\t|\r#","",$numCpuESXi); // on supprime les retours à la ligne et autre retour chariots
		$numCpuESXi = trim($numCpuESXi);
		
		log::add('vmware', 'debug', 'On appelle la commande qui récupère le nombre de coeur par CPU de l\'ESXi'); 
		$_request = "vim-cmd hostsvc/hostsummary | grep numCpuCores | cut -d '=' -f 2 | cut -d ',' -f 1";
		$result = ssh2_exec($connection, $_request . ' 2>&1');
		stream_set_blocking($result, true);
		$numCpuCoresESXi = stream_get_contents($result);
		$numCpuCoresESXi = preg_replace("#\n|\t|\r#","",$numCpuCoresESXi); // on supprime les retours à la ligne et autre retour chariots
		$numCpuCoresESXi = trim($numCpuCoresESXi);
		
		log::add('vmware', 'debug', 'On appelle la commande qui récupère la version VMWARE de l\'ESXi'); 
		$_request = "vim-cmd hostsvc/hostsummary | grep fullName | cut -d '=' -f 2 | cut -d ',' -f 1";
		$result = ssh2_exec($connection, $_request . ' 2>&1');
		stream_set_blocking($result, true);
		$osESXI = stream_get_contents($result);
		log::add('vmware', 'debug', 'valeur de la variable OS avant nettoyage' . $osESXI); 
		$osESXI = str_replace("\"","",$osESXI); // on supprime les retours à la ligne, retour chariots OU les Guillemets pour faire propre le nom
		$osESXIClean = trim($osESXI);
		log::add('vmware', 'debug', 'valeur de la variable OS propre' . $osESXIClean); 
				
		$this->checkAndUpdateCmd('ramTotal', $memoryGBESXi); 
		$this->checkAndUpdateCmd('cpuNumber', $numCpuESXi); 
		$this->checkAndUpdateCmd('corePerCpuNumber', $numCpuCoresESXi); 
		$this->checkAndUpdateCmd('osType', $osESXIClean); 
		
		log::add('vmware', 'info', 'Fin fonction getEsxiInformationList'); 
	}


	public function updateVmInformations($vmNameToUpdate,$esxiHostNameOfVm) {
      	log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '========== Début du log updateVmInformations ===========');
		log::add('vmware', 'info', '========================================================');
		// Fonction ayant pour but de mettre à jour les informations du nb de snapshot / liste des snapshots 
		// VM allumée ou éteinte sur une VM via le bouton refresh
		
		log::add('vmware', 'debug', 'Valeur de la variable vmNameToUpdate : ' . $vmNameToUpdate ); 
		log::add('vmware', 'debug', 'Valeur de la variable esxiHostNameOfVm : ' . $esxiHostNameOfVm ); 
		
		$login = "";
		$password = "";
		$hostIP = "";
		
		$plugin = plugin::byId('vmware'); // Récupération des équipements du plugin vmware en se basant sur l'ID du plugin
		$eqLogicVmware = eqLogic::byType($plugin->getId());
		
		foreach ($eqLogicVmware as $eqLogicEsxiHost) {
			//log::add('vmware', 'debug', 'for each' ); 
			//$eqLogicNameToCompare = $eqLogicEsxiHost->getConfiguration('name');
			//log::add('vmware', 'debug',$eqLogicEsxiHost->getConfiguration('name'));
			if (strcmp($eqLogicEsxiHost->getConfiguration('name'),$esxiHostNameOfVm) == 0) { // on cherche l'hote ESXI 
			//log::add('vmware', 'debug', 'IF dans le foreach - donc on a trouvé un nom qui match' ); 
			$password = $eqLogicEsxiHost->getConfiguration("passwordSSH"); // on récupère le password
			$login = $eqLogicEsxiHost->getConfiguration("login"); // on récupère le login
			$hostIP = $eqLogicEsxiHost->getConfiguration("ipAddress"); // on récupère l'adresseIP
			}				
		}
  
		log::add('vmware', 'debug', 'Login utilisé : ' . $login . ' - Ip de l\'ESXi : ' . $hostIP); 

		if (!$connection = ssh2_connect($hostIP,'22')) {
				return 'error connecting';
				log::add('vmware', 'error', 'ESXi injoignable');
		}else{
			log::add('vmware', 'info', 'ESXi joignable');
		}
			
		if (!ssh2_auth_password($connection,$login,$password)){
				return 'error connecting';
				log::add('vmware', 'error', 'Connexion KO à l\'ESXi');
		}else {
			log::add('vmware', 'info', 'Connexion OK à l\'ESXi');
		}
		
		log::add('vmware', 'debug', 'On appelle la commande qui cherche l\'ID de la vm à mettre à jour'); 
		$request = "vim-cmd vmsvc/getallvms | grep \"".$vmNameToUpdate ."\" | cut -d ' ' -f 1";
		$result = ssh2_exec($connection, $request . ' 2>&1');
		stream_set_blocking($result, true);
		$vmIDToUpdate = stream_get_contents($result);
		$vmIDToUpdate = intval($vmIDToUpdate);
		log::add('vmware', 'debug', 'Contenu de la variable vmIDToUpdate : ' . $vmIDToUpdate); 
						
		// On va chercher les informations que l'on souhaite mettre à jour
		// Récupération et stockage de l'état de la VM (allumée ou éteinte)
		$_request = "vim-cmd vmsvc/get.guest ".$vmIDToUpdate ." | grep guestState | sed -n 1p | cut -d '\"' -f 2";
		$result = ssh2_exec($connection, $_request . ' 2>&1');
		stream_set_blocking($result, true);
		$started = stream_get_contents($result);
		$started = preg_replace("#\n|\t|\r#","",$started); // on supprime les retours à la ligne et autre retour chariots
		$started = str_replace(array("notRunning","running"), array("No","Yes"), $started );
		log::add('vmware', 'debug', 'Contenu de la variable started : ' . $started); 
		
		// Récupération et stockage du nombre de snapshot déclaré sur la VM
		$_request = "vim-cmd vmsvc/snapshot.get ".$vmIDToUpdate ." | grep 'Snapshot Name' | wc -l";
		$result = ssh2_exec($connection, $_request . ' 2>&1');
		stream_set_blocking($result, true);
		$nbSnap = stream_get_contents($result);
		$nbSnap = preg_replace("#\n|\t|\r#","",$nbSnap); // on supprime les retours à la ligne et autre retour chariots et on met une virgule à la place
		
		$snapList = ""; // on initialise à vide la variable snapList
		// Récupération et stockage de la liste des snapshots déclaré sur la VM
		$_request = "vim-cmd vmsvc/snapshot.get ".$vmIDToUpdate ." | grep 'Snapshot Name' | cut -d ':' -f 2 | cut -d ' ' -f 2-30";
		$result = ssh2_exec($connection, $_request . ' 2>&1');
		stream_set_blocking($result, true);
		$snapListing = stream_get_contents($result);
		if($nbSnap == 0) {
			$snapList = "NON";
			log::add('vmware', 'debug', '0 snapshot trouvé ' .$snapList);
		}elseif ($nbSnap == 1){
			  $snapListArray = preg_split('/\s+/', $snapListing); // on crée un tableau en coupant au retour à la ligne
			  $lastLineRemovedSnapList = array_pop($snapListArray); // on supprime la dernière ligne du tableau car elle est vide
			  $snapList = implode(" ",$snapListArray);
			  //$snapList = $snapListArray ;
			  log::add('vmware', 'debug', '1 snapshot trouvé ' .$snapList);
			  log::add('vmware', 'debug', $snapList);
		}elseif($nbSnap > 1){ // conversion de la ligne retournée en tableau dans le cas ou l'on aurait plusieurs snapshot
			  $snapListArray = preg_split('/\r\n|\r|\n/', $snapListing); // on crée un tableau en coupant au retour à la ligne
			  $lastLineRemovedSnapList = array_pop($snapListArray); // on supprime la dernière ligne du tableau car elle est vide
			  $snapList = $snapListArray;
			  log::add('vmware', 'debug', 'X snapshots trouvés ');
		}
	
		$closesession = ssh2_exec($connection, 'exit'); // Fermeture de la connexion SSH à l'hyperviseur
		stream_set_blocking($closesession, true);
		stream_get_contents($closesession);
		if(is_array($snapList)){
			  	foreach ($snapList as $snap){
				$snapListe .= $snap . ",";
				$nbSnapCount = $nbSnapCount + 1 ;
				log::add('vmware', 'debug', 'X snapshots trouvés préparation affichage pour le widget de la VM' .$snap);
				}
			}elseif($snapList != "NON"){
				$snapListe = $snapList;
				$nbSnapCount = "1";
				
			}elseif($snapList == "NON"){
				$snapListe = "Aucun snapshot trouvé";
				//log::add('vmware', 'debug', 'Aucun snapshot trouvé on remplace NON par Aucun snapshot trouvé' .$snapListe);
				$nbSnapCount = "0";
			}
		$snapListe = rtrim($snapListe, ',');  // nettoyage de la variable pour enlever la virgule à la fin de la liste
		
		// Stockage des valeurs dans les commandes information
		$this->checkAndUpdateCmd('nbSnap', $nbSnapCount); 
		$this->checkAndUpdateCmd('snapShotList', $snapListe); 
		$this->checkAndUpdateCmd('online', $started); 
			
		log::add('vmware', 'info', 'Fin fonction updateVmInformations'); 
	}


	public function actionOnVM($actionType,$snapName,$snapDescription,$memory) { // type d'action / nom du snapshot / descript du snap / avec mémoire ou non
		log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '================= Début du log actionOnVM ==============');
		log::add('vmware', 'info', '========================================================');		
		
		$login = "";
		$password = "";
		$hostIP = "";
		
		$esxiHostName = $this->getConfiguration("esxiHost"); // récupération du nom de l'ESXi qui héberge la VM pour récupérer les informations de connexion
		$esxiHostIpAddress = $this->getConfiguration("ESXiHostIpAddress"); // récupération de l'IP de l'ESXi qui héberge la VM
		$vmName = $this->getConfiguration("name"); // récupération du nom de la VM
		log::add('vmware', 'debug', 'ActionType : ' . $actionType . ' - Nom de la VM : ' . $vmName . ' - Ip de l\'ESXi associé à la VM: ' . $esxiHostIpAddress . ' - Nom de l\'ESXi associé à la VM: ' . $esxiHostName); 
		$eqLogics = eqLogic::byLogicalId('vmware'.$esxiHostName,'vmware');
		
		$password = $eqLogics->getConfiguration("passwordSSH"); // on récupère le password
		$login = $eqLogics->getConfiguration("login"); // on récupère le login
		$hostIP = $eqLogics->getConfiguration("ipAddress"); // on récupère l'adresseIP
		log::add('vmware', 'debug', 'Login utilisé : ' . $login . ' - Ip de l\'ESXi : ' . $hostIP); 
		
		log::add('vmware', 'debug', 'Liste des paramètres transmis : ');
		log::add('vmware', 'debug', 'SnapName : '. $snapName);
		log::add('vmware', 'debug', 'snapDescription : '. $snapDescription );
		log::add('vmware', 'debug', 'memory : '. $memory);
		
		// Prévoir de rajouter une option pour le port ssh à spécifier comme ssh-commander
		if (!$connection = ssh2_connect($hostIP,$this->getConfiguration('sshport','22'))) {
			log::add('vmware', 'error', 'ESXi injoignable');
			return 'error connecting';
		}else {
			log::add('vmware', 'info', 'ESXi joignable');
		}
		if (!ssh2_auth_password($connection,$login,$password)){
			log::add('vmware', 'error', 'Connexion KO à l\'ESXi');
			return 'error connecting';
		}else {
			log::add('vmware', 'info', 'Connexion OK à l\'ESXi');
		}		
				
		// Récupération de l'ID et execution de la commande souhaitée dans la foulée (xargs ne fonctionne pas PIPE ne fonctionne pas)
		if($actionType == 'snapshot.create'){
			log::add('vmware', 'debug', 'IF snapshot create');
			
			$_request = "vim-cmd vmsvc/getallvms | grep \"".$vmName."\" | cut -d ' ' -f 1";  // Récupération de l'ID de la VM
			log::add('vmware', 'debug', 'Contenu de la requête pour l\'ID : ' . $_request); 
			$result = ssh2_exec($connection, $_request . ' 2>&1');
			stream_set_blocking($result, true);
			$vmID = stream_get_contents($result);
			$vmID=preg_replace("#\n|\t|\r#","",$vmID); // on supprime les caractères du type nouvelles lignes / tabulation / retours chariots
			log::add('vmware', 'debug', 'ID de la VM ' . $vmID);
			$_request = "vim-cmd vmsvc/".$actionType." ".$vmID." ".$snapName." ".$snapDescription." ".$memory." 0";	// Dernier zéro pour l'état de la VM (Quiesced ou non pour le snapshot)
			log::add('vmware', 'debug', 'Contenu de la requete snapshot create ' . $_request);
		}elseif ($actionType == 'snapshot.remove') { 
			log::add('vmware', 'debug', 'ELSE IF snapshot remove');			
			$_request = "vim-cmd vmsvc/getallvms | grep \"". $vmName."\" | cut -d ' ' -f 1"; // Récupération de l'ID de la VM
			log::add('vmware', 'debug', 'Contenu de la requête pour l\'ID : ' . $_request); 
			$result = ssh2_exec($connection, $_request . ' 2>&1');
			stream_set_blocking($result, true);
			$vmID = stream_get_contents($result);
			$vmID=preg_replace("#\n|\t|\r#","",$vmID); // on supprime les caractères du type nouvelles lignes / tabulation / retours chariots
			log::add('vmware', 'debug', 'ID de la VM ' . $vmID);
			
			// Récupération de l'ID du snapshot
			$_request = " vim-cmd vmsvc/snapshot.get ".$vmID." | grep -A 1 ".$snapName." | sed '1d' | awk '{print $4}'";
			// Original, mais prend le dernier snapshot disponible sans tenir du compte du nom $_request = " vim-cmd vmsvc/get.snapshotinfo ".$vmID." ".$snapName." |grep id | cut -d '=' -f 2 | cut -d ',' -f 1 "; // premier cut permet de couper au caractère = et prendre le deuxième élément, second permet de couper au caractère , et de prendre le premier élément
			log::add('vmware', 'debug', 'Contenu de la requete  Find snapshot ID ' . $_request);				
			$result = ssh2_exec($connection, $_request . ' 2>&1');
			stream_set_blocking($result, true);
			$snapID = stream_get_contents($result);
			$snapID=preg_replace("#\n|\t|\r#","",$snapID); // on supprime les caractères du type nouvelles lignes / tabulation / retours chariots
			log::add('vmware', 'debug', 'ID du snapshot' . $snapID);
			
			$_request = "vim-cmd vmsvc/".$actionType." ".$vmID." ".$snapID; 
			log::add('vmware', 'debug', 'Contenu de la requete snapshot create ' . $_request);				
		}else {
			log::add('vmware', 'debug', 'ELSE - action autre qu\'un snapshot create ou remove');
			$_request = "vim-cmd vmsvc/getallvms | grep \"" . $vmName . "\" | cut -d ' ' -f 1 | xargs vim-cmd vmsvc/".$actionType."";	
		}			
							
		$result = ssh2_exec($connection, $_request . ' 2>&1');
		stream_set_blocking($result, true);
		$result = stream_get_contents($result);
		log::add('vmware', 'debug', 'contenu de $result numéro 2 ' . $result);
		
		$closesession = ssh2_exec($connection, 'exit');
		stream_set_blocking($closesession, true);
		stream_get_contents($closesession);
		
		log::add('vmware', 'info', 'Fin fonction actionOnVM'); 
		return $result; // a voir ce que l'on peut faire de ça, besoin réel ?	
	}
		
	
    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class vmwareCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    
      public function dontRemoveCmd() {
		return true;
      }
     
	public function execute($_options = array()) {
		log::add('vmware', 'info', '========================================================');
		log::add('vmware', 'info', '================== Début du log execute ================');
		log::add('vmware', 'info', '========================================================');		
		
        $eqlogic = $this->getEqLogic(); 
		
		$options = array(); // tableau vide
		if (isset($_options['title'])) { // Si Title est définit (champ nommé Nom - Description sur un scénario)
			$options = arg2array($_options['title']); // On convertit en tableau les informations contenu dans title (nom / description) dans $options
		}
		
		switch ($this->getLogicalId()) {				
		case 'refresh':  
			log::add('vmware', 'debug', 'On est dans le case refresh de la class vmwareCmd '); 
			if($eqlogic->getConfiguration("type") == 'ESXi'){
				log::add('vmware', 'debug', 'On appel la fonction getEsxiInformationList '); 
				$return = $eqlogic->getEsxiInformationList() ;
				log::add('vmware', 'debug', 'On appel la fonction getvmInformationList '); 
				$vmListing = $eqlogic->getVmInformationList() ; //Lance la fonction pour récupérer la liste des VMs et stocke le résultat dans vmListing
				$eqlogic->checkAndUpdateCmd('nbVM', $vmListing[1]); // stocke le contenu de vmListing dans la commande nbVM
				$eqlogic->checkAndUpdateCmd('vmList', $vmListing[0]); // stocke le contenu de vmListing dans la commande vmList
				$eqlogic->setConfiguration('esxiHost',$vmListing[2]); // stocke le contenu de vmListing dans la commande hoteESXi
				$eqlogic->setConfiguration('type',$vmListing[3]); // stocke le contenu de vmListing dans la commande type
			}else if($eqlogic->getConfiguration("type") == 'vm') {
				//log::add('vmware', 'debug', 'C\'est une VM mais on ne fait rien '); 
				log::add('vmware', 'debug', 'C\'est une VM que l\'on va mettre à jour '); 
				$eqlogic->updateVmInformations($eqlogic->getConfiguration("name"),$eqlogic->getConfiguration("esxiHost")) ;
			}else {
				log::add('vmware', 'debug', 'Ca n\'est pas un ESXi server, on ne rafraichit rien'); 
			}
            break;
		case 'takeSnapshot': 
				log::add('vmware', 'info', 'On appelle la fonction ActionOnVM - takesnapshot pour : ' . $eqlogic->getConfiguration("esxiHost") . ' '); 
				log::add('vmware', 'debug', 'Valeur retrouvée dans l\'appel du scénario : champ Nom : ' . $options['Nom'] . ' ');
				log::add('vmware', 'debug', 'Valeur retrouvée dans l\'appel du scénario : champ Description :  ' . $options['Description'] . ' ');
				log::add('vmware', 'debug', 'Valeur retrouvée dans l\'appel du scénario : champ Memory : ' . $_options['message']  .' '); // Attention on attaque bien le champ $_options (transmis à la fonction execute, pas au tableau créé un peu plus haut
				$memory = $_options['message'];
				log::add('vmware', 'debug', 'Valeur retrouvée dans l\'appel du scénario : champ Memory dans variable memory : ' . $memory . ' '); // Attention on attaque bien le champ $_options (transmis à la fonction execute, pas au tableau créé un peu plus haut
				$memory = str_replace(array("NON","OUI"), array("0","1"), $memory ); // On envoi 0 ou 1 selon l'état de la mémoire souhaité lors du snapshot
				$action = $eqlogic->actionOnVM('snapshot.create',$options['Nom'],$options['Description'],$memory);
			break;
		case 'deleteSnapshot':	
				log::add('vmware', 'info', 'On appelle la fonction ActionOnVM - snapshot delete');  
				$action = $eqlogic->actionOnVM('snapshot.remove',$options['Nom']); // liste ou saisie manuelle ?)
			break;
		case 'reboot':	
				log::add('vmware', 'info', 'On appelle la fonction ActionOnVM - reboot'); 
				$action = $eqlogic->actionOnVM('power.reset');
			break;
		case 'rebootOS':	
				log::add('vmware', 'info', 'On appelle la fonction ActionOnVM - rebootOS'); 
				$action = $eqlogic->actionOnVM('power.reboot');
			break;
		case 'stop':	
				log::add('vmware', 'info', 'On appelle la fonction ActionOnVM - stop'); 
				$action = $eqlogic->actionOnVM('power.off');
			break;
		case 'stopOS':	
				log::add('vmware', 'info', 'On appelle la fonction ActionOnVM - stopOS'); 
				$action = $eqlogic->actionOnVM('power.shutdown');
			break;
		case 'powerOn':	
				log::add('vmware', 'info', 'On appelle la fonction ActionOnVM - powerOn'); 
				$action = $eqlogic->actionOnVM('power.on');
			break;
		}
		log::add('vmware', 'info', 'Fin fonction execute');
    }

    /*     * **********************Getteur Setteur*************************** */
}

