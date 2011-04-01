<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2011                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

/**
 * Installation/maj des tables breves
 *
 * @param string $nom_meta_base_version
 * @param string $version_cible
 */
function breves_upgrade($nom_meta_base_version,$version_cible){
	$maj = array();
	$maj['create'] = array(
		array('maj_tables',array('spip_breves')),
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

/**
 * Desinstallation/suppression des tables breves
 *
 * @param string $nom_meta_base_version
 */
function breves_vider_tables($nom_meta_base_version) {
	sql_drop_table("spip_breves");
	
	effacer_meta("activer_breves");
	
	effacer_meta($nom_meta_base_version);
}

?>
