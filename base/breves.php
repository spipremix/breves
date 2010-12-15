<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2010                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/


/**
 * Interfaces des tables breves pour le compilateur
 *
 * @param array $interfaces
 * @return array
 */
function breves_declarer_tables_interfaces($interfaces) {
	$interfaces['table_des_tables']['breves'] = 'breves';

	$interfaces['exceptions_des_tables']['breves']['id_secteur'] = 'id_rubrique';
	$interfaces['exceptions_des_tables']['breves']['date'] = 'date_heure';
	$interfaces['exceptions_des_tables']['breves']['nom_site'] = 'lien_titre';
	$interfaces['exceptions_des_tables']['breves']['url_site'] = 'lien_url';

	$interfaces['table_titre']['breves'] = 'titre, lang';
	
	$interfaces['table_date']['breves'] = 'date_heure';

	$interfaces['tables_jointures']['spip_breves'][]= 'documents_liens';

	$interfaces['table_statut']['spip_breves'][] = array('champ'=>'statut','publie'=>'publie','previsu'=>'publie,prop','exception'=>'statut');

	$interfaces['table_des_traitements']['LIEN_TITRE'][]= _TRAITEMENT_TYPO;
	$interfaces['table_des_traitements']['LIEN_URL'][]= 'vider_url(%s)';
	
	return $interfaces;
}


/**
 * Table principale spip_breves
 *
 * @param array $tables_principales
 * @return array
 */
function breves_declarer_tables_principales($tables_principales) {


	$spip_breves = array(
			"id_breve"	=> "bigint(21) NOT NULL",
			"date_heure"	=> "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
			"titre"	=> "text DEFAULT '' NOT NULL",
			"texte"	=> "longtext DEFAULT '' NOT NULL",
			"lien_titre"	=> "text DEFAULT '' NOT NULL",
			"lien_url"	=> "text DEFAULT '' NOT NULL",
			"statut"	=> "varchar(6)  DEFAULT '0' NOT NULL",
			"id_rubrique"	=> "bigint(21) DEFAULT '0' NOT NULL",
			"lang"	=> "VARCHAR(10) DEFAULT '' NOT NULL",
			"langue_choisie"	=> "VARCHAR(3) DEFAULT 'non'",
			"maj"	=> "TIMESTAMP");

	$spip_breves_key = array(
			"PRIMARY KEY"	=> "id_breve",
			"KEY id_rubrique"	=> "id_rubrique",
	);
	$spip_breves_join = array(
			"id_breve"=>"id_breve",
			"id_rubrique"=>"id_rubrique");

	$tables_principales['spip_breves']   =
		array('field' => &$spip_breves, 'key' => &$spip_breves_key,'join' => &$spip_breves_join);
	
	return $tables_principales;
}




/**
 * Declarer le surnom des breves
 *
 * @param array $surnoms
 * @return array
 */
function breves_declarer_tables_objets_surnoms($surnoms) {
	$surnoms['breve'] = "breves";
	return $surnoms;
}




?>
