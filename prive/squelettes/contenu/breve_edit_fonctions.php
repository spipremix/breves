<?php

function rubrique_creer_breve($id_rubrique){
	if (!$id_rubrique){
		$in = !count($connect_id_rubrique)
			? ''
			: (" AND ".sql_in('id_rubrique', $connect_id_rubrique));

		$id_rubrique = sql_getfetsel('id_rubrique', 'spip_rubriques', "id_parent=0$in", '', "id_rubrique DESC", 1);

		if (!autoriser('creerbrevedans', 'rubrique', $id_rubrique)){
			// manque de chance, la rubrique n'est pas autorisee, on cherche un des secteurs autorises
			$res = sql_select("id_rubrique", "spip_rubriques", "id_parent=0");
			while (!autoriser('creerbrevedans', 'rubrique', $id_rubrique) && $row_rub = sql_fetch($res)){
				$id_rubrique = $row_rub['id_rubrique'];
			}
		}
	}
  return $id_rubrique;
}