<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$html = '';
$js = '';

$joursFeries = getJoursFeries();

// PARAMÈTRES ////////////////////////////////
$dateDebut = new DateTime();

// chargement date de début
if(isset($_COOKIE['date_debut_affiche'])) {
	$_SESSION['date_debut_affiche'] = $_COOKIE['date_debut_affiche'];
}
if (isset($_SESSION['date_debut_affiche'])) {
	$dateDebut->setDate(substr($_SESSION['date_debut_affiche'],6,4), substr($_SESSION['date_debut_affiche'],3,2), substr($_SESSION['date_debut_affiche'],0,2));
} else {
	$dateDebut->modify('-' . CONFIG_DEFAULT_NB_PAST_DAYS . ' days');
	$_SESSION['date_debut_affiche'] = $dateDebut->format('d/m/Y');
}

// chargement date de fin
$dateFin = new DateTime();
if(isset($_COOKIE['date_fin_affiche'])) {
	$_SESSION['date_fin_affiche'] = $_COOKIE['date_fin_affiche'];
}
if (isset($_SESSION['date_fin_affiche'])) {
	$dateFin->setDate(substr($_SESSION['date_fin_affiche'],6,4), substr($_SESSION['date_fin_affiche'],3,2), substr($_SESSION['date_fin_affiche'],0,2));
} else {
	$dateFin = clone $dateDebut;
	$dateFin->modify('+' . CONFIG_DEFAULT_NB_DAYS_DISPLAYED . ' days');
	$_SESSION['date_fin_affiche'] = $dateFin->format('d/m/Y');
}
if(isset($_COOKIE['inverserUsersProjets'])) {
	$_SESSION['inverserUsersProjets'] = $_COOKIE['inverserUsersProjets'];
}
if (!isset($_SESSION['inverserUsersProjets'])) {
	// Si préférences utilisateurs est vueProjet
	if ($_SESSION['preferences']['vueDefaut']=='vueProjet')
	{
	  $_SESSION['inverserUsersProjets'] = true;
	}else $_SESSION['inverserUsersProjets'] = false;
}

if(isset($_GET['affichageLarge'])) {
	if($_GET['affichageLarge'] == '1') {
		$_SESSION['affichageLarge'] = true;
	} else {
		$_SESSION['affichageLarge'] = false;
	}
} elseif (!isset($_SESSION['affichageLarge'])) {
	// Si préférences utilisateurs est vueLarge
	if ($_SESSION['preferences']['vueLargeReduit']=='vueLarge')
	{
		$_SESSION['affichageLarge'] = true;
	}else $_SESSION['affichageLarge'] = false;
}

if(!isset($_SESSION['filtreGroupeProjet'])) {
	$_SESSION['filtreGroupeProjet'] = array();
}

if(!isset($_SESSION['filtreGroupeUser'])) {
	$_SESSION['filtreGroupeUser'] = array();
}

if(!isset($_SESSION['filtreUser'])) {
    if (isset($_COOKIE['filtreUser'])) {
      $_SESSION['filtreUser'] = explode(",", $_COOKIE['filtreUser']);
    } else {
      $_SESSION['filtreUser'] = array();
	}
}

if(!isset($_SESSION['filtreTexte'])) {
	$_SESSION['filtreTexte'] = '';
}

if(!isset($_SESSION['filtreStatutTache'])) {
	$_SESSION['filtreStatutTache'] = array();
}

// Modif Tri Planning
$triPlanningPossibleUser = array('nom asc', 'nom desc', 'user_id asc', 'user_id desc', 'team_nom asc, nom asc', 'team_nom desc, nom desc', 'team_nom asc, user_id asc', 'team_nom desc, user_id desc');
$triPlanningPossibleProjet = array('nom asc', 'nom desc', 'projet_id asc', 'projet_id desc', 'groupe_nom asc, nom asc', 'groupe_nom desc, nom desc', 'groupe_nom asc, projet_id asc', 'groupe_nom desc, projet_id desc');
if((isset($_COOKIE['triPlanningUser']) && (in_array($_COOKIE['triPlanningUser'], $triPlanningPossibleUser) || in_array($_COOKIE['triPlanningUser'], $triPlanningPossibleProjet)))) {
	$_SESSION['triPlanningUser'] = $_COOKIE['triPlanningUser'];
}
if((isset($_SESSION['triPlanningUser']) && !in_array($_SESSION['triPlanningUser'], $triPlanningPossibleUser) && !in_array($_SESSION['triPlanningUser'], $triPlanningPossibleProjet)) || !isset($_SESSION['triPlanningUser'])) {
	$_SESSION['triPlanningUser'] = 'nom asc';
}
if((isset($_COOKIE['triPlanningProjet']) && (in_array($_COOKIE['triPlanningProjet'], $triPlanningPossibleUser) || in_array($_COOKIE['triPlanningProjet'], $triPlanningPossibleProjet)))) {
	$_SESSION['triPlanningProjet'] = $_COOKIE['triPlanningProjet'];
}
if((isset($_SESSION['triPlanningProjet']) && !in_array($_SESSION['triPlanningProjet'], $triPlanningPossibleUser) && !in_array($_SESSION['triPlanningProjet'], $triPlanningPossibleProjet)) || !isset($_SESSION['triPlanningProjet'])) {
	$_SESSION['triPlanningProjet'] = 'nom asc';
}

if($_SESSION['inverserUsersProjets'] == True) {
	$_SESSION['triPlanning'] = $_SESSION['triPlanningProjet'];
}else{
	$_SESSION['triPlanning'] = $_SESSION['triPlanningUser'];
}

// chargement nb lignes affichées
if(isset($_COOKIE['nb_lignes']) && is_numeric($_COOKIE['nb_lignes'])) {
	$_SESSION['nb_lignes'] = $_COOKIE['nb_lignes'];
}
if (isset($_SESSION['nb_lignes'])) {
	$nbLignes = $_SESSION['nb_lignes'];
} else {
	$nbLignes = CONFIG_DEFAULT_NB_ROWS_DISPLAYED;
	$_SESSION['nb_lignes'] = $nbLignes;
}
if (isset($_SESSION['page_lignes'])) {
	$pageLignes = $_SESSION['page_lignes'];
} else {
	$pageLignes = 1;
	$_SESSION['page_lignes'] = $pageLignes;
}

// chargement affichage lignes vides ou pas
if(isset($_COOKIE['masquerLigneVide'])) {
	$_SESSION['masquerLigneVide'] = $_COOKIE['masquerLigneVide'];
}
if (isset($_SESSION['masquerLigneVide'])) {
	$masquerLigneVide = $_SESSION['masquerLigneVide'];
} else {
	$masquerLigneVide = 0;
	$_SESSION['masquerLigneVide'] = $masquerLigneVide;
}

// chargement affichage lignes vides ou pas
if(isset($_COOKIE['afficherLigneTotal'])) {
	$_SESSION['afficherLigneTotal'] = $_COOKIE['afficherLigneTotal'];
}
if (isset($_SESSION['afficherLigneTotal'])) {
	$afficherLigneTotal = $_SESSION['afficherLigneTotal'];
} else {
	$afficherLigneTotal = 0;
	$_SESSION['afficherLigneTotal'] = $afficherLigneTotal;
}

$_SESSION['planningView'] = 'jour';

// chargement affichage large ou reduit
if(isset($_SESSION['dimensionCase']) and in_array($_SESSION['dimensionCase'],array('large','reduit'))) {
	$dimensionCase = $_SESSION['dimensionCase'];
}else{
	$_SESSION['dimensionCase'] = 'reduit';
	$dimensionCase = $_SESSION['dimensionCase'];
	}

if($dimensionCase=='large'){
		$largeurCase = 130;
	}else{
		$largeurCase = 34;
	}

$DAYS_INCLUDED = explode(',', CONFIG_DAYS_INCLUDED);

// FIN PARAMÈTRES ////////////////////////////////

$now = new DateTime();

$nbJours = getNbJoursFull($dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d'));
$dateBoutonInferieur = clone $dateDebut;
$dateBoutonInferieur->modify('-' . $nbJours . 'days');
$dateBoutonSuperieur = clone $dateDebut;
$dateBoutonSuperieur->modify('+' . $nbJours . 'days');

$smarty->assign('dateDebut', $dateDebut->format('d/m/Y'));
$smarty->assign('dateFin', $dateFin->format('d/m/Y'));
$smarty->assign('dateDebutTexte', $smarty->get_config_vars('day_' . $dateDebut->format('w')) . ' ' . $dateDebut->format('d/m/Y'));
$smarty->assign('dateFinTexte', $smarty->get_config_vars('day_' . $dateFin->format('w')) . ' ' . $dateFin->format('d/m/Y'));
$smarty->assign('nbJours', $nbJours);
$smarty->assign('masquerLigneVide', $masquerLigneVide);
$smarty->assign('afficherLigneTotal', $afficherLigneTotal);
$smarty->assign('nbLignes', $nbLignes);
$smarty->assign('pageLignes', $pageLignes);
$smarty->assign('filtreGroupeUser', $_SESSION['filtreGroupeUser']);
$smarty->assign('filtreGroupeProjet', $_SESSION['filtreGroupeProjet']);
$smarty->assign('filtreStatutTache', $_SESSION['filtreStatutTache']);
$smarty->assign('filtreUser', $_SESSION['filtreUser']);
$smarty->assign('filtreTexte', $_SESSION['filtreTexte']);
$smarty->assign('triPlanning', $_SESSION['triPlanning']);
$smarty->assign('triPlanningPossibleUser', $triPlanningPossibleUser);
$smarty->assign('triPlanningPossibleProjet', $triPlanningPossibleProjet);
$smarty->assign('inverserUsersProjets', $_SESSION['inverserUsersProjets']);
$smarty->assign('affichageLarge', $_SESSION['affichageLarge']);
$smarty->assign('dateBoutonInferieur', $dateBoutonInferieur->format('d/m/Y'));
$smarty->assign('dateBoutonSuperieur', $dateBoutonSuperieur->format('d/m/Y'));
$smarty->assign('modeAffichage', $_SESSION['planningView']);
$smarty->assign('dimensionCase', $_SESSION['dimensionCase']);
$tmpDate = clone $dateDebut;
$tabTranchesHoraires = explode(',', CONFIG_HOURS_DISPLAYED);

$html .= '<table class="css_tableau" id="tabContenuPlanning">' . CRLF;
$html .= '<tr>' . CRLF;
$html .= '<th id="tdUser_0" rowspan="2"></th>' . CRLF;
$tmpDateFin = clone $dateFin;
while ($tmpDate <= $dateFin) {
	if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED)) {
		$tmpDate->modify('+1 day');
		// IMPORTANT : on modifie la date de fin pour tenir compte des jours à sauter
		$dateFin->modify('+1 day');
		continue;
	} elseif (in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
		$sClass = 'weekend';
		$opacity = 'filter:alpha(opacity=60);-moz-opacity:.60;opacity:.60';
	} else {
		$sClass = 'weekend';
	}
	if( $tmpDate->format('Y-m-d') == date('Y-m-d')) {
		$sClass .= ' today';
	}
	$html .= '<th style="padding-top:6px;padding-bottom:6px;" colspan="' . count($tabTranchesHoraires) . '" class="' . $sClass .'">' . CRLF;
	$html .= $smarty->get_config_vars('planning_semaine2') . ' ' . $tmpDate->format('W') . '&nbsp;&nbsp;&nbsp; ' . $smarty->get_config_vars('planning_jour') . ' : ' . $smarty->get_config_vars('day_' . $tmpDate->format('w')) . ' ' . $tmpDate->format('d/m/Y') . CRLF;
	$html .= '</th>' . CRLF;
	$tmpDate->modify('+1 day');
}
$html .= '</tr>' . CRLF;
$html .= '<tr>' . CRLF;
$tmpDate = clone $dateDebut;
// On réinitialise la dateFin
$dateFin = clone $tmpDateFin;
while ($tmpDate <= $dateFin) {
	if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED)) {
		$tmpDate->modify('+1 day');
		// IMPORTANT : on modifie la date de fin pour tenir compte des jours à sauter
		$dateFin->modify('+1 day');
		continue;
	} elseif (in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
		$sClass = 'weekend';
		$opacity = 'filter:alpha(opacity=60);-moz-opacity:.60;opacity:.60';
	} else {
		$sClass = 'weekend';
	}
	if( $tmpDate->format('Y-m-d') == date('Y-m-d')) {
		$sClass .= ' today';
	}
	reset($tabTranchesHoraires);
	foreach ($tabTranchesHoraires as $trancheHeureCourante) {
		$trancheFin = $trancheHeureCourante + 1;
		if($trancheFin == 24) {
			$trancheFin = 0;
		}
		$html .= '<th align="center" class="' . $sClass . '" nowrap="nowrap" style="padding-left:2px;padding-right:2px;padding-top:6px;padding-bottom:6px;width:' . $largeurCase . 'px;">' . $trancheHeureCourante . '-' .  $trancheFin . $smarty->get_config_vars('tab_h') . '</th>';
	}
	$tmpDate->modify('+1 day');
}
$html .= '</tr>' . CRLF;
// recuperation des projets couvrant la période, pour le filtre de projets
$projetsFiltre = new GCollection('Projet');
$sql = "SELECT distinct pp.*, pg.nom AS groupe_nom
		FROM planning_projet pp
		LEFT JOIN planning_periode pd ON pp.projet_id = pd.projet_id
		LEFT JOIN planning_groupe AS pg ON pp.groupe_id = pg.groupe_id ";
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " INNER JOIN planning_user AS pu ON pd.user_id = pu.user_id ";
}
$sql .= "WHERE (
			(pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "'
			AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(pd.date_debut <= '" . $dateFin->format('Y-m-d') . "'
			AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(pp.statut='a_faire')
			OR
			(pp.statut='en_cours')
	)";
if($user->checkDroit('tasks_view_own_projects')) {
	// on filtre sur les projets dont le user courant est propriétaire ou assigné
	$sql .= " AND (pp.createur_id = '" . $user->user_id . "' OR pd.user_id = '" . $user->user_id . "')";
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " AND pu.user_groupe_id = " . $user->user_groupe_id;
}
$sql .= "	GROUP BY pp.nom, pp.projet_id
			ORDER BY pg.nom, pp.nom";
$projetsFiltre->db_loadSQL($sql);
$smarty->assign('listeProjets', $projetsFiltre->getSmartyData());
if($user->checkDroit('tasks_view_own_projects')) {
	$listeProjetsPossibles = $projetsFiltre->get('projet_id');
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	$listeProjetsPossibles = $projetsFiltre->get('projet_id');
}

// recuperation de la liste des utilisateurs pour filtre sur users
$usersFiltre = new GCollection('User');
$sql = "SELECT pu.*, pug.nom AS groupe_nom
		FROM planning_user pu
		LEFT JOIN planning_user_groupe pug ON pu.user_groupe_id = pug.user_groupe_id
		WHERE visible_planning = 'oui' ";
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	$sql .= " AND pu.user_groupe_id = " . $user->user_groupe_id;
}
$sql .=	" ORDER BY groupe_nom, pu.nom";
$usersFiltre->db_loadSQL($sql);
$smarty->assign('listeUsers', $usersFiltre->getSmartyData());

	//////////////////////////
	// MODIF CALCUL TOTAUX USERS
	//////////////////////////

//modif ajout SQL sur user groupe
$realUsers = new GCollection('User');
$sql = "SELECT pu.*, pug.nom
		FROM planning_user pu
		LEFT JOIN planning_user_groupe pug ON pu.user_groupe_id = pug.user_groupe_id";
if(count($_SESSION['filtreUser']) > 0) {
	$sql .= " WHERE pu.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
}
$realUsers->db_loadSQL($sql);
$nbRealUsers = $realUsers->getCount();

$totalParJour = array();
$totauxJourUsers = array();

// on charge les jours occupés pour toutes les lignes
$periodes = new GCollection('Periode');

$sql = "SELECT planning_periode.*
		FROM planning_periode";

$sql .= " WHERE (
			(planning_periode.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND planning_periode.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(planning_periode.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND planning_periode.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
			)";
if(count($_SESSION['filtreUser']) > 0) {
	$sql.= " AND planning_periode.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
}
if(count($_SESSION['filtreGroupeProjet']) > 0) {
	$sql.= " AND planning_periode.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
}
if($user->checkDroit('tasks_view_own_projects')) {
	$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
}

if(count($_SESSION['filtreStatutTache']) > 0) {
	$sql.= " AND planning_periode.statut_tache IN ('" . implode("','", $_SESSION['filtreStatutTache']) . "')";
}

if($_SESSION['filtreTexte'] != "") {
	$sql.= " AND (planning_periode.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." OR planning_periode.titre LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.custom LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.projet_id LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.user_id LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.statut_tache LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " )";
}
$periodes->db_loadSQL($sql);

// pour chaque période du planning, on remplie le tableau des jours occupés
while ($periode = $periodes->fetch()) {
	$infosJour = $periode->getSmartyData();

	$dateDebut_projet = new DateTime();
	$dateDebut_projet->setDate(substr($periode->date_debut,0,4), substr($periode->date_debut,5,2), substr($periode->date_debut,8,2));

	$dateFin_projet = new DateTime();

	$tmpDate = clone $dateDebut_projet;
	if (is_null($periode->date_fin)) {
		$dateFin_projet = clone $dateDebut_projet;
	}
	else {
		$dateFin_projet->setDate(substr($periode->date_fin,0,4), substr($periode->date_fin,5,2), substr($periode->date_fin,8,2));
	}

	while ($tmpDate <= $dateFin_projet) {
			if(!isset($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')])) {
				$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = '00:00';
			}
			if($infosJour['date_fin'] != '') {
				$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = ajouterDuree($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')], usertime2sqltime(CONFIG_DURATION_DAY, false));
			} else {
				$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = ajouterDuree($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')], usertime2sqltime($infosJour['duree'], false));
			}
			// on additionne le total des jours
			if(!isset($totalParJour[$tmpDate->format('Ymd')])) {
				$totalParJour[$tmpDate->format('Ymd')] = '00:00';
			}
			if($infosJour['date_fin'] != '') {
				$totalParJour[$tmpDate->format('Ymd')] = ajouterDuree($totalParJour[$tmpDate->format('Ymd')], usertime2sqltime(CONFIG_DURATION_DAY, false));
			} else {
				$totalParJour[$tmpDate->format('Ymd')] = ajouterDuree($totalParJour[$tmpDate->format('Ymd')], usertime2sqltime($infosJour['duree'], false));
				//echo $tmpDate->format('Ymd') . ' -- ' . $totalParJour[$tmpDate->format('Ymd')] . ' -- ' . $jour['duree'] . '<br>';
			}
		$tmpDate->modify('+1 day');
	}
}

	//////////////////////////
	// FIN MODIF CALCUL TOTAUX USERS
	//////////////////////////

$nbLine = 1;
$groupeCourant = false;
$idGroupeCourant = -1;

// CHARGEMENT DES LIGNES (USERS SI NORMAL, PROJET SI INVERSÉ)
if($_SESSION['inverserUsersProjets']) {
	$lines = new GCollection('Projet');
	//modif sql
	$sql = "SELECT distinct planning_projet.*, pg.nom AS groupe_nom
            FROM planning_projet
            LEFT JOIN planning_periode pd ON planning_projet.projet_id = pd.projet_id
            LEFT JOIN planning_groupe AS pg ON planning_projet.groupe_id = pg.groupe_id ";

	$sql .= "WHERE (
                (pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "'
                AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
                OR
                (pd.date_debut <= '" . $dateFin->format('Y-m-d') . "'
                AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
                OR
                (statut='a_faire')
                OR
                (statut='en_cours')
        )";
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$sql.= " AND planning_projet.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if($user->checkDroit('tasks_view_own_projects')) {
		$sql .= " AND planning_projet.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		// on filtre sur les projets de l'équipe de ce user
		$sql .= " AND planning_projet.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	$sql .= " ORDER BY " . $_SESSION['triPlanning'];
} else {
	$lines = new GCollection('User');
	$sql = "SELECT planning_user.*, planning_user_groupe.nom AS team_nom
			FROM planning_user
			LEFT JOIN planning_user_groupe ON planning_user.user_groupe_id = planning_user_groupe.user_groupe_id
			WHERE visible_planning = 'oui'";
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND planning_user.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		$sql .= " AND planning_user.user_groupe_id = " . $user->user_groupe_id;
	}
	$sql .= " ORDER BY " . $_SESSION['triPlanning'];
}

$lines->db_loadSQL($sql);
$nbLignesTotal = $lines->getCount();

// on refiltre la liste des user sur le nombre max à afficher
$sql .= " LIMIT " . ($nbLignes*($pageLignes-1)) . "," . $nbLignes;
$lines->db_loadSQL($sql);

// on recupere le nombre de pages pour afficher le pager
$smarty->assign('nbPagesLignes', ceil($nbLignesTotal/$nbLignes));

// FIN CHARGEMENT DES LIGNES (USERS SI NORMAL, PROJET SI INVERSÉ)


$nbLine = 1;
$groupeCourant = false;
$idGroupeCourant = -1;

while($ligneTmp = $lines->fetch()) {
	if($_SESSION['inverserUsersProjets']) {
		$ligneId = $ligneTmp->projet_id;
	} else {
		$ligneId = $ligneTmp->user_id;
	}

	$nbLine++;

	// gestion de l'affichage des groupes (de user ou projet) dans le planning
	if(strpos($_SESSION['triPlanning'], 'groupe_nom') !== FALSE || strpos($_SESSION['triPlanning'], 'team_nom') !== FALSE) {
		if($_SESSION['inverserUsersProjets']) {
			if($ligneTmp->groupe_nom !== $groupeCourant) {
				$html .= '<tr>' . CRLF;
				$html .= '<th nowrap="nowrap" style="background-color:#AAAAAA;color:#000000" id="tdUser_' . $idGroupeCourant . '">&nbsp;' . ($ligneTmp->groupe_nom != '' ? xss_protect($ligneTmp->groupe_nom) : $smarty->get_config_vars('planning_pasDeGroupe')) . '&nbsp;' . CRLF;
				$html .= '</th>' . CRLF;
				$tmpDate = clone $dateDebut;
				while ($tmpDate <= $dateFin) {
					if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
						$tmpDate->modify('+1 day');
						continue;
					}
					foreach ($tabTranchesHoraires as $trancheHeureCourante){
						$html .= '<td style="background-color:#AAAAAA;">&nbsp;</td>' . CRLF;
					}
					$tmpDate->modify('+1 day');
				}
				$html .= '</tr>' . CRLF;
				$idGroupeCourant--;
			}
			$groupeCourant = $ligneTmp->groupe_nom;
		} else {
			if($ligneTmp->team_nom !== $groupeCourant) {
				$html .= '<tr>' . CRLF;
				$html .= '<th nowrap="nowrap" style="background-color:#AAAAAA;color:#000000;" id="tdUser_' . $idGroupeCourant . '">&nbsp;' . ($ligneTmp->team_nom != '' ? xss_protect($ligneTmp->team_nom) : $smarty->get_config_vars('planning_pasDeTeam')) . '&nbsp;' . CRLF;
				$html .= '</th>' . CRLF;
				$tmpDate = clone $dateDebut;
				while ($tmpDate <= $dateFin) {
					if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
						$tmpDate->modify('+1 day');
						continue;
					}
					foreach ($tabTranchesHoraires as $trancheHeureCourante){
						$html .= '<td style="background-color:#AAAAAA;">&nbsp;</td>' . CRLF;
					}
					$tmpDate->modify('+1 day');
				}
				$html .= '</tr>' . CRLF;
				$idGroupeCourant--;
			}
			$groupeCourant = $ligneTmp->team_nom;
		}
	}

	// on charge les jours occupés pour cette ligne
	$periodes = new GCollection('Periode');
	if( $_SESSION['inverserUsersProjets']) {
		$sql = "SELECT planning_periode.*, planning_user.*, planning_projet.createur_id, pc.nom AS nom_createur, pc2.nom AS nom_modifier
				FROM planning_periode
				INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id
				INNER JOIN planning_projet ON planning_projet.projet_id = planning_periode.projet_id
				LEFT JOIN planning_user pc ON planning_periode.createur_id = pc.user_id
				LEFT JOIN planning_user pc2 ON planning_periode.modifier_id = pc2.user_id
				WHERE planning_periode.projet_id = '" . $ligneId . "'";
	} else {
		$sql = "SELECT planning_periode.*, planning_projet.*, pc.nom AS nom_createur, pc2.nom AS nom_modifier
				FROM planning_periode
				INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id
				INNER JOIN planning_projet ON planning_periode.projet_id = planning_projet.projet_id
				LEFT JOIN planning_user pc ON planning_periode.createur_id = pc.user_id
				LEFT JOIN planning_user pc2 ON planning_periode.modifier_id = pc2.user_id
				WHERE planning_periode.user_id = '" . $ligneId . "'";
	}
	$sql .= "	AND (
					(planning_periode.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND planning_periode.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
						OR
					(planning_periode.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND planning_periode.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
						)";
	if($user->checkDroit('tasks_view_own_projects')) {
		$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
		$sql .= " AND (planning_user.user_groupe_id IS NULL OR planning_user.user_groupe_id = " . $user->user_groupe_id . ')';
	}

	if(count($_SESSION['filtreStatutTache']) > 0) {
		$sql.= " AND planning_periode.statut_tache IN ('" . implode("','", $_SESSION['filtreStatutTache']) . "')";
	}
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$sql.= " AND planning_periode.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND planning_periode.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if($_SESSION['filtreTexte'] != "") {
		$sql.= " AND (planning_periode.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." OR planning_periode.titre LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " )";
	}
	$sql.= " ORDER BY planning_periode.date_debut, planning_periode.duree_details";
	// echo $sql . '<br>';
	$periodes->db_loadSQL($sql);

	$ordreJourPrec = array();

	$joursOccupes = array();

	// pour chaque période de cette ligne, on remplie le tableau des jours occupés
	while ($periode = $periodes->fetch()) {
		$infosJour = $periode->getSmartyData();
		if( $_SESSION['inverserUsersProjets']) {
			$infosJour['projet_nom'] = xss_protect($ligneTmp->nom);
			$infosJour['user_nom'] = xss_protect($periode->nom);
		} else {
			$infosJour['projet_nom'] = xss_protect($periode->nom);
			$infosJour['user_nom'] = xss_protect($ligneTmp->nom);
		}

		$dateDebut_projet = new DateTime();
		$dateDebut_projet->setDate(substr($periode->date_debut,0,4), substr($periode->date_debut,5,2), substr($periode->date_debut,8,2));

		$dateFin_projet = new DateTime();

		$tmpDate = clone $dateDebut_projet;
		if (is_null($periode->date_fin)) {
			$dateFin_projet = clone $dateDebut_projet;
		}
		else {
			$dateFin_projet->setDate(substr($periode->date_fin,0,4), substr($periode->date_fin,5,2), substr($periode->date_fin,8,2));
		}

		while ($tmpDate <= $dateFin_projet) {
				if (isset($joursOccupes[$tmpDate->format('Y-m-d')])) {
					if(CONFIG_PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY == 0) {
						$tmpArray = $joursOccupes[$tmpDate->format('Y-m-d')];
						$tmpArray[] = $infosJour;
						$joursOccupes[$tmpDate->format('Y-m-d')] = $tmpArray;
					}
				} else {
					$tmpArray = array($infosJour);
					$joursOccupes[$tmpDate->format('Y-m-d')] = $tmpArray;
				}
			$tmpDate->modify('+1 day');
		}
	}

	// si option activée, on masque la ligne si elle est vide
	if($masquerLigneVide == 1 && count($joursOccupes) == 0) {
		continue;
	}

	$ordreJourCourant = array();

	// on genere la ligne courante
	$html .= '<tr>' . CRLF;
	$html .= '<th id="tdUser_' . ($nbLine-1) . '" nowrap="nowrap"' . ((!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF') ? ' style="background-color:#'.$ligneTmp->couleur. ';color:' . buttonFontColor('#' . $ligneTmp->couleur) . '"' : '') . '>&nbsp;';
	// si le user a le droit, on permet de cliquer pour afficher la fiche de l'item (user ou projet)
	if($_SESSION['inverserUsersProjets'] && $user->checkDroit('projects_manage_all')) {
		$html .= '<a style="font-weight: normal;color:' . (!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF' ? buttonFontColor('#' . $ligneTmp->couleur) . '"' : '#ffffff') . '" href="javascript:xajax_modifProjet(\'' . urlencode($ligneTmp->projet_id) . '\');undefined;">' . xss_protect($ligneTmp->nom) . '</a>';
	} elseif (!$_SESSION['inverserUsersProjets'] && $user->checkDroit('users_manage_all')) {
		$html .= '<a style="font-weight: normal;color:' . (!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF' ? buttonFontColor('#' . $ligneTmp->couleur) . '"' : '#ffffff') . '" href="javascript:xajax_modifUser(\'' . urlencode($ligneTmp->user_id) . '\');undefined;">' . xss_protect($ligneTmp->nom) . '</a>';
	} else {
		$html .= xss_protect($ligneTmp->nom);
	}
	$html .= '&nbsp;</th>' . CRLF;

	$tmpDate = clone $dateDebut;
	// on boucle sur la durée de l'affichage
	while ($tmpDate <= $dateFin) {
		if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED)) {
			$tmpDate->modify('+1 day');
			continue;
		}

		reset($tabTranchesHoraires);
		foreach ($tabTranchesHoraires as $trancheHeureCourante) {
			// définit le style pour case semaine et WE
			if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
				$classTD = 'weekend';
				$opacity = 'filter:alpha(opacity=60);-moz-opacity:.60;opacity:.60';
			} else {
				$classTD = 'week';
				$opacity = '';
			}
			if(CONFIG_PLANNING_LINE_HEIGHT > 0) {
				$styleLigne = ' style="height:' . CONFIG_PLANNING_LINE_HEIGHT . 'px;"';
			} else {
				$styleLigne = '';
			}

			if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
				// on ajoute le jour à la liste des destinations possible pour drag and drop
				$js .= 'destinationsDrag[destinationsDrag.length] = "td_' . $ligneId . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante  . '";' . CRLF;
			}

			if (in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
				// jours fériés
				$ferieObj = new Ferie();
				if($ferieObj->db_load(array('date_ferie', '=', $tmpDate->format('Y-m-d'))) && trim($ferieObj->libelle) != "") {
					$cooltip = '<b>' . $ferieObj->libelle . '</b>';
					$ferie = '<div class="caseFerie" onmouseover="return coolTip(\'' . addslashes($cooltip) . '\')"  onmouseout="nd()" onClick="event.cancelBubble=true;">' . $smarty->get_config_vars('planning_ferie') . '</div>' . CRLF;
				}
			} else {
				$ferie = false;
			}

			if (isset($joursOccupes[$tmpDate->format('Y-m-d')])) {
				// jours avec au moins une case remplie
				$html .= '<td ' . $styleLigne . ' valign="top" id="td_' . $ligneId . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante . '"';
				if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
					$html .= ' onClick="Reloader.stopRefresh();xajax_ajoutPeriode(\'' . $tmpDate->format('Y-m-d') . '\', \'' . $ligneId . '\', \'\', ' . $trancheHeureCourante . ');"';
				}
				$html .= ' class="' . $classTD . (($tmpDate->format('Y-m-d') == date('Y-m-d')) ? ' today' : '') . '">' . CRLF;

				if($ferie !== false) {
					$html .= $ferie;
				}

				$niveauCourant = 0;
				// si il y a des periodes pour le jour courant, on boucle pour toutes les afficher
				foreach ($joursOccupes[$tmpDate->format('Y-m-d')] as $jour) {
					// on checke que la tache couvre la tranche horaire en cours
					if(!couvreTranche($jour['duree_details'], $trancheHeureCourante)) {
						continue;
					}

					// generation des cellules vides pour aligner les cases d'une meme periode
					if(in_array($jour['periode_id'], $ordreJourPrec) && $niveauCourant != array_search($jour['periode_id'], $ordreJourPrec)) {
						for($i=1; $i<=(array_search($jour['periode_id'], $ordreJourPrec)-$niveauCourant); $i++) {
							$html .= '<div class="caseProjets">&nbsp;</div>' . CRLF;
						}
						$ordreJourCourant[array_search($jour['periode_id'], $ordreJourPrec)] = $jour['periode_id'];
						$niveauCourant = $niveauCourant + array_search($jour['periode_id'], $ordreJourPrec) + 1;
					} else {
						$ordreJourCourant[] = $jour['periode_id'];
						$niveauCourant++;
					}

					// mouseover sur la case
					$cooltip =  '<b>' . $smarty->get_config_vars('tab_projet') . '</b> : ' . $jour['projet_nom'] . ' (' . $jour['projet_id'] . ')<br/>'
										. '<b>' . $smarty->get_config_vars('tab_personne') . '</b> : ' . $jour['user_nom'] . ' (' . $jour['user_id'] . ')';
					if($jour['titre'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_titre') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $jour['titre']));
					}
					if($jour['livrable'] == 'oui') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_livrable') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $smarty->get_config_vars('oui')));
					}
					if($jour['statut_tache'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_statut') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $smarty->get_config_vars('winPeriode_statut_' . $jour['statut_tache'])));
					}
					$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_dateDebut') . '</b> : ' . $jour['date_debut'];
					if($jour['date_fin'] != '') {
						$cooltip .= '<br/><b>'  . $smarty->get_config_vars('tab_dateFin') .  '</b> : ' . $jour['date_fin'];
					} else {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_duree') . '</b> : ' . sqltime2usertime($jour['duree']);
						if(isset($jour['duree_details_heure_debut'])) {
							$cooltip .= ' (' . sqltime2usertime($jour['duree_details_heure_debut']) . ' => ' . sqltime2usertime($jour['duree_details_heure_fin']) . ')';
						}
						if($jour['duree_details'] == 'AM') {
							$cooltip .= ' (' . $smarty->get_config_vars('tab_matin') . ')';
						}
						if($jour['duree_details'] == 'PM') {
							$cooltip .= ' (' . $smarty->get_config_vars('tab_apresmidi') . ')';
						}
					}
					if($jour['notes'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_commentaires') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $jour['notes']));
					}
					if($jour['lien'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_lien') . '</b> : ' . ($jour['lien']);
					}
					if($jour['nom_createur'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_nomCreateur') . '</b> : ' . ($jour['nom_createur']);
					}
					if($jour['date_creation'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('periode_date_creation') . '</b> : ' . sqldatetime2userdatetime($jour['date_creation']);
					}
					if($jour['nom_modifier'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('periode_modifier') . '</b> : ' . sqldatetime2userdatetime($jour['date_modif']) . " " . $smarty->get_config_vars('periode_modifier_par') . ' ' . $jour['nom_modifier'];
					}

					// couleur du texte dans la case, selon la couleur de fond de la case
					$couleurTexte = buttonFontColor('#' . $jour['couleur']);
					$couleurFond = $jour['couleur'];
					if($couleurFond =='FFFFFF'){
						$couleurFond = 'fafafa';
					}
					// la case avec le code du projet
					$html .= '<div id="c_' . $jour['periode_id'] . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante . '" class="caseProjets" onmouseover="return coolTip(\'' . xss_protect(addslashes($cooltip)) . '\')"  onmouseout="nd()" onClick="event.cancelBubble=true;" onMouseUp="Reloader.stopRefresh();modifPeriode(this, ' . $jour['periode_id'] . ');" style="color:' . $couleurTexte . ';' . $opacity . ';background-color:#' . $couleurFond . ';';
					if($dimensionCase=='reduit') {
						if ($jour['duree'] > 0 && $jour['duree'] < 8) {
							if ($jour['lien'] != '') {
								$html .= 'background-image:url(\'assets/img/pictos/half_link.gif\'); background-repeat:no-repeat; background-position:bottom right;';
							} else {
								$html .= 'background-image:url(\'assets/img/pictos/half.gif\'); background-repeat:no-repeat; background-position:bottom left;';
							}
						} else {
							if ($jour['lien'] != '') {
								$html .= 'background-image:url(\'assets/img/pictos/link.jpg\'); background-repeat:no-repeat; background-position:bottom right;';
							}
						}
					}
					//modif ajout bord periode fille ou parente
					$periode = new Periode();
					$periode->db_load(array('periode_id', '=', $jour['periode_id']));
					if($periode->estFilleOuParente()) {
						$html .= 'border: 1px solid black;';
					}
					if($jour['statut_tache'] == 'fait' || $jour['statut_tache'] == 'abandon') {
						$html .= 'text-decoration:line-through;';
					}
					if(!$user->checkDroit('tasks_modify_all') && !($user->checkDroit('tasks_modify_own_project') && $jour['createur_id'] == $user->user_id) && !($user->checkDroit('tasks_modify_own_task') && ($jour['user_id'] == $user->user_id || $jour['createur_id'] == $user->user_id))) {
						//si pas les droits on affiche curseur interdit
						$html .= 'cursor:url(\'assets/img/pictos/interdit.png\'), not-allowed';
					}

					$html .= '">';
					//modif ajout picto charge journaliere
					if($dimensionCase=='large'){
						$ratioCharge=round(decimalHours($totauxJourUsers[$jour['user_id']][$tmpDate->format('Ymd')])/CONFIG_DURATION_DAY,1);
						$ratio=round($ratioCharge*10);
						if ($ratio > 10){
							$ratio=11;
						}
						if($ratio == 0) {
							if($jour['lien'] != '') {
								$html .= '<div class="jauge0"></div>';
								$html .= '<img src="assets/img/pictos/link.gif" border="0" style="position: absolute;right:1px;bottom:0px;" />';
							} else {
								$html .= '<div class="jauge0"></div>';
							}
						} else{
							if($jour['lien'] != '') {
								$html .= '<div class="jauge0">';
								$html .= '<div class="jauge' . $ratio .'">';
								if ($ratio==10){
									$html .= '100';
								}
								$html .= '</div></div>';
								$html .= '<img src="assets/img/pictos/link.gif" border="0" style="position: absolute;right:1px;bottom:0px;" />';
							} else {
								$html .= '<div class="jauge0">';
								$html .= '<div class="jauge' . $ratio .'">';
								if ($ratio==10){
									$html .= '100';
								}
								$html .= '</div></div>';
							}
						}
					}
					// Modif affichage simple ou complet

                    if( $_SESSION['inverserUsersProjets']) {
                        $L1=$jour['nom'];
                    }else{
                        $L1=$jour['projet_id'];
                    }
                    $L2=$jour['titre'];
                    $styleL2reduit = 'text-align:center';


					if (isset($jour['duree_details_heure_debut'])){
						$infoDebut=sqltime2usertime($jour['duree_details_heure_debut']);
					}else{
						$infoDebut='09:00';
					}

					if (isset($jour['duree'])){
						$affichageDuree=$jour['duree'];
					}else{
						$affichageDuree= CONFIG_DURATION_DAY . ':00:00';
					}
					if ($L2==''){
						$L2='...';
					 }

					if($dimensionCase=='large'){
						$nom = '&nbsp'.substr($L1, 0, 20);
						$nom.= '<div style="text-align:center;font-weight:bold">'.substr($L2,0,20).'</div>';
						$nom.= '<div style="position:absolute;left:1px;bottom:0">'.$infoDebut.'h</div>';
						$nom.= '<div style="position:absolute;right:15px;bottom:0">'.round(decimalHours($affichageDuree),1).'h/'.round(decimalHours($totauxJourUsers[$jour['user_id']][$tmpDate->format('Ymd')]),1).'h</div>';
					}else{
						$nom = '<div style="text-align:center">'.substr($L1, 0, 5).'</div>';
						//$nom.= '<div style="' . $styleL2reduit . '">'.substr($L2, 0, 5).'</div>';
					}


					if($jour['livrable'] == 'oui') {
						if($dimensionCase=='large'){
							$html .= $nom . '<img src="assets/img/pictos/milestone.png" border="0" style="position:absolute;left:35px;bottom:0px" />';
						}else{
							$html .= $nom . '<img src="assets/img/pictos/milestone.png" border="0" style="position:absolute;left:35%;bottom:25%" />';
						}
					} else {
						$html .= $nom;
					}
					$html .= '</div>';

					if($user->checkDroit('tasks_modify_all') || ($user->checkDroit('tasks_modify_own_project') && $jour['createur_id'] == $user->user_id) || ($user->checkDroit('tasks_modify_own_task') && ($jour['user_id'] == $user->user_id || $jour['createur_id'] == $user->user_id))) {
						// on rend draggable la case du projet
						$js .= 'Drag.init(document.getElementById("c_' . $jour['periode_id'] . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante . '"));' . CRLF;
					}
				}

				$ordreJourPrec = $ordreJourCourant;
				$ordreJourCourant = array();

				// espace vide pour permettre de cliquer en dessous d'une case assignée
				$html.= '<div style="height:8px;padding:0px;margin:0px;font-size:0px"></div>';

				$html .= '</td>' . CRLF;

			} else {
				// jour vide
				$html .= '<td ' . $styleLigne . ' id="td_' . $ligneId . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante . '"';
				if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
					$html .= ' onClick="Reloader.stopRefresh();xajax_ajoutPeriode(\'' . $tmpDate->format('Y-m-d') . '\', \'' . $ligneId . '\', \'\', ' . $trancheHeureCourante . ');"';
				}
				$html .= ' class="' . $classTD . (($tmpDate->format('Y-m-d') == date('Y-m-d')) ? ' today' : '') . '">';
				if($ferie !== false) {
					$html .= $ferie;
				} else {
					$html .= '&nbsp;';
				}
				$html .= '</td>' . CRLF;
			}
		}
		$tmpDate->modify('+1 day');
	}
	$html .= '</tr>' . CRLF;
}

// ligne de total
if($afficherLigneTotal == 1) {
	$html .= '<th id="tdUser_' . ($nbLine-1) . '" nowrap="nowrap">' . $smarty->get_config_vars('tab_totalJour') . '</th>' .CRLF;
	$tmpDate = clone $dateDebut;
	// on boucle sur la durée de l'affichage
	while ($tmpDate <= $dateFin) {
		// définit le style pour case semaine et WE
		if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
			$classTD = 'weekend';
		} else {
			$classTD = 'week';
		}
		if(isset($totalParJour[$tmpDate->format('Ymd')]) && $classTD == 'week')  {
			$i=1;
			$capitalCharge=$nbRealUsers*CONFIG_DURATION_DAY;
			if($capitalCharge != 0){
				$ratioCharge=round(decimalHours($totalParJour[$tmpDate->format('Ymd')])/$capitalCharge,1);
			}else{
			$ratioCharge=0;
			}
			$ratio=round($ratioCharge*10);
			if ($ratio > 10){
				$ratio=11;
			}
			foreach ($tabTranchesHoraires as $trancheHeureCourante) {
				if ($i==count($tabTranchesHoraires)){
					if($dimensionCase=='large'){
						if($ratio == 0) {
							$html .= '<td class="' . $classTD . '">' . $totalParJour[$tmpDate->format('Ymd')];
							$html .= '<div class="jaugeTD"><div class="jauge0"></div></div></td>' . CRLF;

						} else{
							$html .= '<td class="' . $classTD . '">' . $totalParJour[$tmpDate->format('Ymd')];
							$html .= '<div class="jaugeTD"><div class="jauge0">';
							$html .= '<div class="jauge' . $ratio .'">';
							if ($ratio==10){
								$html .= '100';
							}
							$html .= '</div></div></div></td>' . CRLF;
						}
					}else{
						if($ratio == 0) {
							$html .= '<td class="' . $classTD . '" style="font-size:9px;font-weight:normal;text-align: center"">' . $totalParJour[$tmpDate->format('Ymd')];
							$html .= '</td>' . CRLF;

						} else{
							$html .= '<td class="' . $classTD . '" style="font-size:9px;font-weight:normal;text-align: center"">' . $totalParJour[$tmpDate->format('Ymd')];
							$html .= '</td>' . CRLF;
						}
					}
				} else {
				$html .= '<td class="' . $classTD . '" style="background-color:#E6E6E6">&nbsp;</td>' . CRLF;
				}
				$i++;
			}
		} elseif ($classTD == 'week') {
			$i=1;
			foreach ($tabTranchesHoraires as $trancheHeureCourante) {
				$html .= '<td class="' . $classTD . '" style="background-color:#E6E6E6">&nbsp;</td>' . CRLF;
				$i++;
			}
		}
		$tmpDate->modify('+1 day');
	}
}

$html .= '</table>' . CRLF;

$smarty->assign('htmlTableau', $html);



if($_SESSION['inverserUsersProjets']) {
	//////////////////////////
	// TABLEAU RECAP DES PROJETS
	//////////////////////////
	$html = '<table border="0" id="divProjectTable" cellspacing="1" cellpadding="3" class="table table-striped" ' . (isset($_COOKIE['divProjectTable']) && $_COOKIE['divProjectTable'] == 'none' ? 'style="display:none;"' : '') . ' style="margin-top:5px;width:1180px;">' . CRLF;
	$html .= '	<tr>' . CRLF;
	$html .= '		<td width="25" valign="top" nowrap="nowrap">' . $smarty->get_config_vars('tab_code') . '</td>' . CRLF;
	$html .= '		<td valign="top" nowrap="nowrap">' . $smarty->get_config_vars('tab_projet2') . '</td>' . CRLF;
	$html .= '		<td valign="top" nowrap="nowrap">' . $smarty->get_config_vars('tab_periode2') . '</td>' . CRLF;
	$html .= '		<td valign="top" nowrap="nowrap" width="140">' . $smarty->get_config_vars('tab_charge') . '</td>' . CRLF;
	$html .= '	</tr>' . CRLF;
	// recuperation des projets couvrant la période, pour le filtre de projets
	$projets = new GCollection('Projet');
	$sql= "SELECT distinct pp.*, pg.nom AS groupe_nom
		FROM planning_projet pp
		INNER JOIN planning_periode pd ON pp.projet_id = pd.projet_id
		LEFT JOIN planning_groupe AS pg ON pp.groupe_id = pg.groupe_id ";
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		// on filtre sur les projets de l'équipe de ce user
		$sql .= " INNER JOIN planning_user AS pu ON pd.user_id = pu.user_id ";
	}
	$sql .= " WHERE (
			(pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "'
			AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(pd.date_debut <= '" . $dateFin->format('Y-m-d') . "'
			AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
		)";
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$sql .= " AND pp.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND pd.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if($_SESSION['filtreTexte'] != "") {
		$sql.= " AND (pd.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR pd.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." )";
	}
	if($user->checkDroit('tasks_view_own_projects')) {
		$sql .= " AND pp.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		$sql .= " AND pd.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}

	$sql .= "	GROUP BY pp.nom, pp.projet_id
				ORDER BY pp.groupe_id, pp.nom";
	$projets->db_loadSQL($sql);

	while($projet = $projets->fetch()) {
		$html .= '	<tr>' . CRLF;
		$couleurTexte = buttonFontColor('#' . $projet->couleur);
		$cooltipProjet = '<b>' . $smarty->get_config_vars('tab_projet') . '</b> : ' . $projet->nom . '(' . $projet->projet_id . ')<br />' .
							'<b>' . $smarty->get_config_vars('tab_statut') . '</b> :' . $projet->statut . '<br />' .
							'<b>' . $smarty->get_config_vars('tab_chargeEstimee') . '</b> : ' . $projet->charge . '<br />' .
							'<b>' . $smarty->get_config_vars('tab_livraison') . '</b> : ' . $projet->livraison .
							($projet->iteration != '' ? '<br /><b>' . $smarty->get_config_vars('tab_commentaire') . '</b> : ' . htmlentities($projet->iteration) : '');
		$html .= '<td onmouseover="return coolTip(\'' . addslashes($cooltipProjet) . '\', WIDTH, 400)"  onmouseout="nd()" onClick="javascript:Reloader.stopRefresh();xajax_modifProjet(\'' . $projet->projet_id . '\', \'planning\');undefined;" align="center" width="25" nowrap="nowrap" class="codesProjets" style="cursor:pointer;background:#' . $projet->couleur . ';color:' . $couleurTexte .';">' . $projet->projet_id . '</td>' . CRLF;
		$html .= '<td valign="top"><b>' . $projet->nom . '</b>' . (!is_null($projet->iteration) ? '<br />' . $projet->iteration : '');

		$html .= '<td valign="top">';
		// on charge les périodes liées à ce projet
		$periodes = new GCollection('Periode');
		$sql = "SELECT pp.*
				FROM planning_periode AS pp
				INNER JOIN planning_user ON planning_user.user_id = pp.user_id
				WHERE planning_user.visible_planning = 'oui'
				AND projet_id = '" . $projet->projet_id . "'
				AND (
					(pp.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND pp.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
					OR (pp.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND pp.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
					)";
		if(count($_SESSION['filtreUser']) > 0) {
			$sql.= " AND pp.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
		}
		if($_SESSION['filtreTexte'] != "") {
			$sql.= " AND (pp.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR pp.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." )";
		}
		$sql .= " ORDER BY pp.date_debut";
		//echo $sql . '<br>';
		$periodes->db_loadSQL($sql);

		// si aucune période dispo pour ce projet (par exemple si user non visible) on masque le projet
		if($periodes->getCount() == 0) {
			continue;
		}

		$totalJours = 0;
		$totalJoursPassed = 0;
		$totalHeures = "00:00";
		$totalHeuresPassed = "00:00";
		while ($periode = $periodes->fetch()) {
			$html .= '<div style="font-size:10px;">';
			if (is_null($periode->date_fin)) {
				$html .= sqldate2userdate($periode->date_debut) . ' => ' . sqltime2usertime($periode->duree) . ' (' . $periode->user_id . ')';
			} else {
				$html .= sqldate2userdate($periode->date_debut) . ' => ' . sqldate2userdate($periode->date_fin) . ' (' . $periode->user_id . ')';
			}
			if (!is_null($periode->titre)) {
				$html .= ' (' . xss_protect($periode->titre) . ')';
			}
			if (!is_null($periode->notes)) {
				$html .= '&nbsp;&nbsp;&nbsp;' . xss_protect($periode->notes);
			}
			if (!is_null($periode->lien)) {
				$html .= '&nbsp;&nbsp;&nbsp;<a href="' . xss_protect($periode->lien) . '" target="_blank">' . $smarty->get_config_vars('tab_lien') . '</a>';
			}
			$html .= '</div>';

			$date1 = new DateTime();
			$date1->setDate(substr($periode->date_debut,0,4), substr($periode->date_debut,5,2), substr($periode->date_debut,8,2));

			// on additionne les jours de travail
			if(!is_null($periode->date_fin)) {
				$date2 = new DateTime();
				$date2->setDate(substr($periode->date_fin,0,4), substr($periode->date_fin,5,2), substr($periode->date_fin,8,2));
				while ($date1 <= $date2) {
					// on ne compte pas le jour si c'est WE ou jour férié
					if (in_array($date1->format('w'), $DAYS_INCLUDED) && !in_array($date1->format('Y-m-d'), $joursFeries)) {
						$totalJours +=1;
						if($date1 < $now) {
							$totalJoursPassed +=1;
						}
					}
					$date1->modify('+1 day');
				}
			} else {
				$totalHeures = ajouterDuree($totalHeures, $periode->duree);
				if($date1 < $now) {
					$totalHeuresPassed = ajouterDuree($totalHeuresPassed, $periode->duree);
				}

			}
		}

		$html .= '</td>' . CRLF;
		$html .= '<td valign="top">' . CRLF;
		if(!is_null($projet->charge)) {
			$html .= $smarty->get_config_vars('tab_chargeProjet') . ' : ' . $projet->charge . $smarty->get_config_vars('tab_j') . '<br />' . CRLF;
		}
		if($totalJours > 0) {
			$html .= $smarty->get_config_vars('tab_total') . ' : '  . $totalJours . $smarty->get_config_vars('tab_j') . CRLF;
		}
		if($totalHeures != '00:00') {
			if($totalJours > 0) {
				$html .= '&nbsp;' . CRLF;
			} else {
				$html .= $smarty->get_config_vars('tab_total') . ' : ' . CRLF;
			}
			$html .= $totalHeures . CRLF;
		}
		$html .= '<br />' . CRLF;
		if($totalJoursPassed > 0) {
			$html .= $smarty->get_config_vars('tab_passe') . ' : ' . $totalJoursPassed . $smarty->get_config_vars('tab_j') . CRLF;
		}
		if($totalHeuresPassed > 0) {
			if($totalJoursPassed > 0) {
				$html .= '&nbsp;' . CRLF;
			} else {
				$html .= $smarty->get_config_vars('tab_passe') . ' : ' . CRLF;
			}
			$html .= $totalHeuresPassed . CRLF;
		}
		$html .= '</td>' . CRLF;
		$html .= '	</tr>' . CRLF;
	}
	$html .= '</table>' . CRLF;

} else {

	//////////////////////////
	// TABLEAU RECAP DES USERS
	//////////////////////////
	$html = '<table border="0" id="divProjectTable" cellspacing="1" cellpadding="3" class="table table-striped" ' . (isset($_COOKIE['divProjectTable']) && $_COOKIE['divProjectTable'] == 'none' ? 'style="display:none;"' : '') . ' style="margin-top:5px;width:1180px;">' . CRLF;
	$html .= '	<tr>' . CRLF;
	$html .= '		<td width="25" valign="top" nowrap="nowrap">' . $smarty->get_config_vars('tab_code') . '</td>' . CRLF;
	$html .= '		<td valign="top" nowrap="nowrap">' . $smarty->get_config_vars('tab_personne') . '</td>' . CRLF;
	$html .= '		<td valign="top" nowrap="nowrap">' . $smarty->get_config_vars('tab_periode2') . '</td>' . CRLF;
	$html .= '		<td valign="top" nowrap="nowrap" width="140">' . $smarty->get_config_vars('tab_charge') . '</td>' . CRLF;
	$html .= '	</tr>' . CRLF;

	// recuperation des personnes
	$users = new GCollection('User');
	$sql= "SELECT *
			FROM planning_user
			WHERE visible_planning = 'oui' ";
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		$sql .= " AND planning_user.user_groupe_id = " . $user->user_groupe_id;
	}
	if(strpos($_SESSION['triPlanning'], 'nom') !== FALSE) {
		$sql .= "	ORDER BY nom ASC";
	} else {
		$sql .= "	ORDER BY nom DESC";
	}
	$users->db_loadSQL($sql);

	while($userTemp = $users->fetch()) {
		$html .= '	<tr>' . CRLF;
		$couleurTexte = buttonFontColor('#' . $userTemp->couleur);
		$html .= '<td onClick="javascript:Reloader.stopRefresh();xajax_modifUser(\'' . $userTemp->user_id . '\');undefined;" align="center" width="25" nowrap="nowrap" class="codesProjets" style="cursor:pointer;background:#' . $userTemp->couleur . ';color:' . $couleurTexte .';">' . $userTemp->user_id . '</td>' . CRLF;
		$html .= '<td valign="top"><b>' . $userTemp->nom . '</b>';

		$html .= '<td valign="bottom">';
		// on charge les périodes liées aux projets
		$periodes = new GCollection('Periode');
		$sql = "SELECT pp.*
				FROM planning_periode AS pp
				INNER JOIN planning_user ON planning_user.user_id = pp.user_id
				WHERE planning_user.visible_planning = 'oui'
				AND pp.user_id = '" . $userTemp->user_id . "'
				AND (
					(pp.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND pp.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
					OR (pp.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND pp.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
					)";
		if(count($_SESSION['filtreUser']) > 0) {
			$sql.= " AND pp.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
		}
		if($_SESSION['filtreTexte'] != "") {
			$sql.= " AND (pp.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR pp.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." )";
		}
		if(count($_SESSION['filtreGroupeProjet']) > 0) {
			$sql .= " AND pp.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
		}
		if($user->checkDroit('tasks_view_own_projects')) {
			$sql .= " AND pp.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
		}
		if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
			$sql .= " AND pp.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
		}

		$sql .= " ORDER BY pp.date_debut";
		//echo $sql . '<br>';
		$periodes->db_loadSQL($sql);

		// si aucune période dispo pour ce projet (par exemple si user non visible) on masque le projet
		if($periodes->getCount() == 0) {
			$html .= '</td>' . CRLF;
			$html .= '<td valign="top">' . CRLF;
			$html .= '</td>' . CRLF;
			continue;
		}

		$totalJours = 0;
		$totalJoursPassed = 0;
		$totalHeures = "00:00";
		$totalHeuresPassed = "00:00";
		while ($periode = $periodes->fetch()) {
			$html .= '<div style="font-size:10px;">';
			if (is_null($periode->date_fin)) {
				$html .= sqldate2userdate($periode->date_debut) . ' => ' . sqltime2usertime($periode->duree);
				$testHeures = $periode->getHeureDebutFin();
				if(!is_null($testHeures)) {
					$html .= ' (' . sqltime2usertime($testHeures['duree_details_heure_debut']) . ' => ' . sqltime2usertime($testHeures['duree_details_heure_fin']) . ')';
				}
				if($periode->duree_details == 'AM') {
					$html .= ' (' . $smarty->get_config_vars('tab_matin') . ')';
				}
				if($periode->duree_details == 'PM') {
					$html .= ' (' . $smarty->get_config_vars('tab_apresmidi') . ')';
				}
				$html .= ' (' . $periode->projet_id . ')';
			} else {
				$html .= sqldate2userdate($periode->date_debut) . ' => ' . sqldate2userdate($periode->date_fin) . ' (' . $periode->projet_id . ')';
			}
			if (!is_null($periode->titre)) {
				$html .= ' (' . xss_protect($periode->titre) . ')';
			}
			if (!is_null($periode->notes)) {
				$html .= '&nbsp;&nbsp;&nbsp;' . xss_protect($periode->notes);
			}
			if (!is_null($periode->lien)) {
				$html .= '&nbsp;&nbsp;&nbsp;<a href="' . xss_protect($periode->lien) . '" target="_blank">' . $smarty->get_config_vars('tab_lien') . '</a>';
			}
			$html .= '</div>';

			$date1 = new DateTime();
			$date1->setDate(substr($periode->date_debut,0,4), substr($periode->date_debut,5,2), substr($periode->date_debut,8,2));

			// on additionne les jours de travail
			if(!is_null($periode->date_fin)) {
				$date2 = new DateTime();
				$date2->setDate(substr($periode->date_fin,0,4), substr($periode->date_fin,5,2), substr($periode->date_fin,8,2));
				while ($date1 <= $date2) {
					// on ne compte pas le jour si c'est WE ou jour férié
					if (in_array($date1->format('w'), $DAYS_INCLUDED) && !in_array($date1->format('Y-m-d'), $joursFeries)) {
						$totalJours +=1;
						if($date1 < $now) {
							$totalJoursPassed +=1;
						}
					}
					$date1->modify('+1 day');
				}
			} else {
				$totalHeures = ajouterDuree($totalHeures, $periode->duree);
				if($date1 < $now) {
					$totalHeuresPassed = ajouterDuree($totalHeuresPassed, $periode->duree);
				}

			}
		}

		$html .= '</td>' . CRLF;
		$html .= '<td valign="top">' . CRLF;
		if($totalJours > 0) {
			$html .= $smarty->get_config_vars('tab_total') . ' : '  . $totalJours . $smarty->get_config_vars('tab_j') . CRLF;
		}
		if($totalHeures != '00:00') {
			if($totalJours > 0) {
				$html .= '&nbsp;' . CRLF;
			} else {
				$html .= $smarty->get_config_vars('tab_total') . ' : ' . CRLF;
			}
			$html .= $totalHeures . CRLF;
		}
		$html .= '<br />' . CRLF;
		if($totalJoursPassed > 0) {
			$html .= $smarty->get_config_vars('tab_passe') . ' : ' . $totalJoursPassed . $smarty->get_config_vars('tab_j') . CRLF;
		}
		if($totalHeuresPassed > 0) {
			if($totalJoursPassed > 0) {
				$html .= '&nbsp;' . CRLF;
			} else {
				$html .= $smarty->get_config_vars('tab_passe') . ' : ' . CRLF;
			}
			$html .= $totalHeuresPassed . CRLF;
		}
		$html .= '</td>' . CRLF;
		$html .= '	</tr>' . CRLF;
	}

	$html .= '</table>' . CRLF;

}

// anchor for show/hide, move the page to be the entire project table
$html .= '<a id="anchorProjectTable"></a>' . CRLF;

// pour savoir combien de groupes à afficher dans colonne de gauche
$smarty->assign('nbGroupes', ($idGroupeCourant+1));

$smarty->assign('htmlRecap', $html);

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->assign('js', $js);

$smarty->display('www_planning.tpl');
