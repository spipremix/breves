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

if (!defined("_ECRIRE_INC_VERSION")) return;


/**
 * Definir les meta de configuration liee aux breves
 *
 * @param array $metas
 * @return array
 */
function breves_configurer_liste_metas($metas){
	$metas['activer_breves'] =  'non';
	return $metas;
}

/**
 * Permet des calculs de noms d'url sur les breves. 
 *
 * @param array $array liste des objets acceptant des urls
 * @return array
**/
function breves_declarer_url_objets($array){
	$array[] = 'breve';
	return $array;
}



/**
 * Ajouter les breves a valider sur les rubriques 
 *
 * @param 
 * @return 
**/
function breves_rubrique_encours($flux){
	if ($flux['args']['type'] == 'rubrique') {
		$lister_objets = charger_fonction('lister_objets','inc');

		$id_rubrique = $flux['args']['id_objet'];

		//
		// Les breves a valider
		//
		$flux['data'] .= $lister_objets('breves', array(
			'titre'=>_T('info_breves_valider'),
			'statut'=>array('prepa','prop'),
			'id_rubrique'=>$id_rubrique,
			'par'=>'date_heure'));
	}
	return $flux;
}




/**
 * Ajouter les breves references sur les vues de rubriques
 *
 * @param 
 * @return 
**/
function breves_affiche_enfants($flux) {
	global $spip_lang_right;
	
	if ($flux['args']['exec'] == 'naviguer') {
		$id_rubrique = $flux['args']['id_rubrique'];
  
		if ($GLOBALS['meta']["activer_breves"] == 'oui') {
			$lister_objets = charger_fonction('lister_objets','inc');
			$bouton_breves = '';
			$id_parent = sql_getfetsel('id_parent', 'spip_rubriques', 'id_rubrique='.$id_rubrique);
			if (autoriser('creerbrevedans','rubrique',$id_rubrique,NULL,array('id_parent'=>$id_parent))) {
				$bouton_breves .= icone_inline(_T('icone_nouvelle_breve'), generer_url_ecrire("breves_edit","id_rubrique=$id_rubrique&new=oui"), "breve-24.png","new", $spip_lang_right)
				. "<br class='nettoyeur' />";
			}

			$flux['data'] .= $lister_objets('breves', array('titre'=>_T('icone_ecrire_nouvel_article'), 'where'=>"statut != 'prop' AND statut != 'prepa'", 'id_rubrique'=>$id_rubrique, 'par'=>'date_heure'));
			$flux['data'] .= $bouton_breves;
		}
	}
	return $flux;
}




/**
 * Bloc sur les informations generales concernant chaque type d'objet
 *
 * @param string $texte
 * @return string
 */
function breves_accueil_informations($texte){
	include_spip('base/abstract_sql');

	$q = sql_select("COUNT(*) AS cnt, statut", 'spip_breves', '', 'statut', '','', "COUNT(*)<>0");

	$cpt = array();
	$cpt2 = array();
	$defaut = $where ? '0/' : '';
	while($row = sql_fetch($q)) {
	  $cpt[$row['statut']] = $row['cnt'];
	  $cpt2[$row['statut']] = $defaut;
	}
 
	if ($cpt) {
		if ($where) {
			$q = sql_select("COUNT(*) AS cnt, statut", 'spip_breves', $where, "statut");
			while($row = sql_fetch($q)) {
				$r = $row['statut'];
				$cpt2[$r] = intval($row['cnt']) . '/';
			}
		}
		$texte .= "<div class='accueil_informations breves verdana1'>";
		$texte .= afficher_plus_info(generer_url_ecrire("breves",""))."<b>"._T('info_breves_02')."</b>";
		$texte .= "<ul style='margin:0px; padding-$spip_lang_left: 20px; margin-bottom: 5px;'>";
		if (isset($cpt['prop'])) $texte .= "<li>"._T("texte_statut_attente_validation").": ".$cpt2['prop'].$cpt['prop'] . '</li>';
		if (isset($cpt['publie'])) $texte .= "<li><b>"._T("texte_statut_publies").": ".$cpt2['publie'] .$cpt['publie'] . "</b>" .'</li>';
		$texte .= "</ul>";
		$texte .= "</div>";
	}
	return $texte;
}


/**
 * Compter les breves dans une rubrique
 * 
 * @param array $flux
 * @return array
 */
function breves_objet_compte_enfants($flux){
	if ($flux['args']['objet']=='rubrique'
	  AND $id_rubrique=intval($flux['args']['id_objet']))
	  	$flux['data']['breve'] = sql_countsel('spip_breves', "id_rubrique=".intval($id_rubrique)." AND (statut='publie' OR statut='prop')");

	return $flux;
}


/**
 * Ajouter les breves a valider sur la page d'accueil 
 *
 * @param 
 * @return 
**/
function breves_accueil_encours($flux){
	$lister_objets = charger_fonction('lister_objets','inc');


	$flux .= $lister_objets('breves', array(
		'titre'=>afficher_plus_info(generer_url_ecrire('breves'))._T('info_breves_valider'),
		'statut'=>array('prepa','prop'),
		'par'=>'date_heure'));

	return $flux;
}



/**
 * Optimiser la base de donnee en supprimant les liens orphelins
 *
 * @param int $n
 * @return int
 */
function breves_optimiser_base_disparus($flux){
	$n = &$flux['data'];



	# les breves qui sont dans une id_rubrique inexistante
	$res = sql_select("B.id_breve AS id",
		        "spip_breves AS B
		        LEFT JOIN spip_rubriques AS R
		          ON B.id_rubrique=R.id_rubrique",
			"R.id_rubrique IS NULL
		         AND B.maj < $mydate");

	$n+= optimiser_sansref('spip_breves', 'id_breve', $res);


	//
	// Breves
	//

	sql_delete("spip_breves", "statut='refuse' AND maj < $mydate");


	

	return $flux;

}


?>
