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

	$interfaces['table_des_traitements']['LIEN_TITRE'][]= _TRAITEMENT_TYPO;
	$interfaces['table_des_traitements']['LIEN_URL'][]= 'vider_url(%s)';
	
	return $interfaces;
}


function breves_declarer_tables_objets_sql($tables){
	$tables['spip_breves'] = array(
		'texte_retour' => 'icone_retour',
		'texte_objets' => 'public:breves', // _T('public:breves')
		'texte_objet' => 'public:breve', // _T('public:breve')
		'texte_modifier' => 'icone_modifier_breve', // _T('icone_modifier_breve')
		'texte_creer' => 'icone_nouvelle_breve', // _T('icone_nouvelle_breve')
		'info_aucun_objet'=> 'info_aucun_breve', // _T('info_aucun_breve')
		'info_1_objet' => 'info_1_breve', // _T('info_1_breve')
		'info_nb_objets' => 'info_nb_breves', // _T('info_nb_breves')
		'titre' => 'titre, lang',
		'date' => 'date_heure',
		'principale' => 'oui',
		'field'=> array(
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
			"maj"	=> "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"	=> "id_breve",
			"KEY id_rubrique"	=> "id_rubrique",
		),
		'join' => array(
			"id_breve"=>"id_breve",
			"id_rubrique"=>"id_rubrique"
		),
		'statut' =>  array(
			array(
				'champ'=>'statut',
				'publie'=>'publie',
				'previsu'=>'publie,prop',
				'exception'=>'statut'
			)
		),
		'statut_titres' => array(
			'prop' => 'titre_breve_proposee', //_T('titre_breve_proposee')
			'publie' => 'titre_breve_publiee', //_T('titre_breve_publiee')
			'refuse' => 'titre_breve_refusee', //_T('titre_breve_refusee')
		),
		'statut_textes_instituer' => 	array(
			'prop' => 'texte_statut_propose_evaluation', //_T('texte_statut_propose_evaluation')
			'publie' => 'texte_statut_publie', //_T('texte_statut_publie')
			'refuse' => 'texte_statut_refuse', //_T('texte_statut_refuse')
		),

		'rechercher_champs' => array(
		  'titre' => 8, 'texte' => 2, 'lien_titre' => 1, 'lien_url' => 1
		),
		'rechercher_jointures' => array(
			'document' => array('titre' => 2, 'descriptif' => 1)
		),
		'champs_versionnes' => array('id_rubrique', 'titre', 'lien_titre', 'lien_url', 'texte'),
	);

	return $tables;
}


?>
