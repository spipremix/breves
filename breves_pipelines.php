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


?>
