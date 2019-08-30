
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


$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
/*
 * Fonction pour l'ajout d'options (historiser/ affichage par exemple) dans l'onglet commandes de l'équipement, appellé automatiquement par plugin.template
 */
function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
    tr += '</td>';
    tr += '<td>';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';
	tr += '<span><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" /> {{Historiser}}<br/></span>'; // checkbox pour le bouton Historiser par exemple de l'onglet commande
    tr += '<span><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" /> {{Affichage}}<br/></span>'; // checkbox pour Rendre visible ou non le bouton Affichage de l'onglet commande
    tr += '</td>';		
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}

function typefieldChange(){
	if ($('#typefield').value() == 'vm') {
    //$('#lienProcedureSSHKey').hide();
		$('#loginPassword').hide();
		$('#passwordESXi').hide();
	//	$('#sshKeyOrLoginPassword').hide();
		$('#ESXiIpAddress').hide();
		$('#ESXIHostLabel').show();
		$('#esxiHostfield').show();
		$('#nbSnapLabel').show();
		$('#ipAddressLabelRightPartOfPage').show();		
		$('#ipAddressfield').show();		
	//	$('#ESXiConnexionType').hide();
	}else { // if ($('#modelfield').value() == 'ESXi') {
		$('#loginPassword').show();
		$('#passwordESXi').show();
	//	$('#sshKeyOrLoginPassword').show();
		$('#ESXiIpAddress').show();
		$('#ESXIHostLabel').hide();
		$('#esxiHostfield').hide();
		$('#nbSnapLabel').hide();
		$('#ipAddressLabelRightPartOfPage').hide();		
		$('#ipAddressfield').hide();		
	//	$('#ESXiConnexionType').show();
    }
}
  
$( "#typefield" ).change(function(){
  setTimeout(typefieldChange,100);
});

// Affichage de la page health 
$('#bt_healthvmware').on('click', function () {
	console.log("On appelle la modal Health");
	$('#md_modal').dialog({title: "{{Santé VMWARE}}"});
	$('#md_modal').load('index.php?v=d&plugin=vmware&modal=health').dialog('open');
});

// Appel à la fonction refresh de l'ESXi (bouton synchroniser à gauche de chaque ESXi)
$('.synchronisation').on('click', function () {
	console.log("On appelle la fonction Synchroniser de l'ESXI nommé : ");
	var id = $(this).attr('data-id');
	console.log(id);
	$.ajax({
        type: "POST",
        url: "plugins/vmware/core/ajax/vmware.ajax.php",
        data: {
            action: "synchronisation",
            id: id,
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
			//console.log("On est dans le JS LE IF de l'appel AJAX - avant le reload de la page");
          	window.location.reload(); // Est-ce que ça recharge la page ?
          	//console.log("On est dans le JS LE IF de l'appel AJAX - après le reload de la page");
        }
    });
});

// Appel au script action-on-vm.ps1 suite à l'appui sur un bouton Gooo sur le dashboard
/*$('#actionVmware').on('click', function (){
	 console.log("On appel la fonction depuis le fichier vmware.js Action Vmware afin d'effectuer une action sur VMware (suppression snapshot / ajout snap / reboot / extinction / etc");
	 console.log("Action demandée ");
	 $actionName = document.getElementById('actionName').value
	 console.log("$actionName");
	 $vmName = document.getElementById('vmName').value
	 console.log("Nom de la VM ");
	 console.log("$vmName");
	 
});*/