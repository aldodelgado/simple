{* Smarty *}
<form class="form-horizontal" method="POST" action="" target="_blank">
	<input type="hidden" id="periode_id" name="periode_id" value="{$periode.periode_id}" />
	<input type="hidden" id="saved" name="saved" value="{$periode.saved}" />
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="control-group span6">
				<label class="control-label">{#winPeriode_projet#} :</label>
				<div class="controls">
					<select name="projet_id" id="projet_id" class="input-large select2" tabindex="1">
						<option></option>
						{foreach from=$listeProjets item=projet}
							<option value="{$projet.projet_id}" {if $periode.projet_id eq $projet.projet_id}selected="selected"{/if} {if isset($projet_id_choisi) && $projet_id_choisi eq $projet.projet_id}selected="selected"{/if}>{$projet.nom} ({$projet.projet_id}) {if $projet.livraison neq ''} - S{$projet.livraison}{/if}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="control-group span6">
				<label class="control-label" style="width: 75px;">{#winPeriode_user#} :</label>
				<div class="controls" style="margin-left: 90px;">
					<select name="user_id" id="user_id" class="input-large select2" tabindex="2">
						{foreach from=$listeUsers item=userTmp}
							<option value="{$userTmp.user_id}" {if $periode.user_id eq $userTmp.user_id}selected="selected"{/if} {if isset($user_id_choisi) && $user_id_choisi eq $userTmp.user_id}selected="selected"{/if}>{$userTmp.nom} - {$userTmp.user_id}</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
		{if isset($estFilleOuParente)}
			<div class="row-fluid">
				<div class="control-group span12">
					<label class="control-label"></label>
					<div class="controls">
						<label class="checkbox inline"><input type="checkbox" checked="checked" id="appliquerATous" value="1">	{#winPeriode_appliquerATous#}</label>
					</div>
				</div>
			</div>
		{/if}
		<div class="row-fluid">
			<div class="control-group span6">
				<label class="control-label">{#winPeriode_user3#} :</label>
				<div class="controls">
					<select name="user_id3" id="user_id3" class="input-large select2">
						<option></option>
						{foreach from=$listeUsers item=userTmp}
							<option value="{$userTmp.user_id}">{$userTmp.nom} - {$userTmp.user_id}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="control-group span6">
				<label class="control-label" style="width: 70px;">{#winPeriode_user2#} :</label>
				<div class="controls" style="margin-left: 90px;">
					<select name="user_id2" id="user_id2" class="input-large select2">
						<option></option>
						{foreach from=$listeUsers item=userTmp}
							<option value="{$userTmp.user_id}">{$userTmp.nom} - {$userTmp.user_id}</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
		<hr />
		<div class="row-fluid">
			<div class="control-group span12">
				<label class="control-label">{#winPeriode_debut#} :</label>
				<div class="controls">
					<input type="text" name="date_debut" id="date_debut" size="11" maxlength="10" value="{$periode.date_debut|sqldate2userdate}" style="width:80px" class="datepicker" tabindex="4" />
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="control-group span7">
				<label class="control-label">{#winPeriode_fin#} :</label>
				<div class="controls">
					<label class="radio inline">
						<input type="radio" name="radioChoixFin" id="radioChoixFinDate" value="" {if $periode.duree_details eq ""}checked="checked"{/if} onChange="$('divFinChoixDate').style.display='block';$('divFinChoixDuree').style.display='none';" tabindex="5" />&nbsp;{#winPeriode_finChoixDate#}
					</label>
					<label class="radio inline">
						<input type="radio" name="radioChoixFin" id="radioChoixFinDuree" value="" {if $periode.duree_details neq ""}checked="checked"{/if} onChange="$('divFinChoixDuree').style.display='block';$('divFinChoixDate').style.display='none';" tabindex="6" />&nbsp;{#winPeriode_finChoixDuree#}
					</label>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			{* choix du jour de fin *}
			<div class="control-group span12" id="divFinChoixDate" {if $periode.duree_details neq ""}style="display:none;"{/if}>
				<div class="controls">
					<input type="text" name="date_fin" id="date_fin" size="11" maxlength="10" value="{$periode.date_fin|sqldate2userdate}" onFocus="remplirDateFinPeriode();videChampsFinTache(this.id);" style="width:80px" class="datepicker" onChange="videChampsFinTache(this.id);"  tabindex="7" />
					{if $periode.periode_id eq 0}
						&nbsp;{#winPeriode_ouNBJours#} :
						<input type="text" name="nb_jours" id="nb_jours" size="1" maxlength="2" style="width:30px" onChange="videChampsFinTache(this.id);" tabindex="10" />
						<input type="hidden" id="conserver_duree" value="" />
					{else}
						<input type="hidden" id="nb_jours" value="" />
					{/if}
					{if $periode.periode_id neq 0 && $periode.date_fin neq ""}
						<label class="checkbox inline" style="padding-top: 0px;"><input type="checkbox" id="conserver_duree" name="conserver_duree" value="1" onClick="toggle2('bloc_date_fin');" tabindex="11" />{#winPeriode_conserverDuree#|sprintf:$nbJours}</label>
					{else}
						<input type="hidden" id="conserver_duree" value="" />
					{/if}
				</div>
			</div>
			{* choix de la dur�e *}
			<div class="control-group span12" id="divFinChoixDuree" {if $periode.duree_details eq ''}style="display:none;"{/if}>
				<div class="controls">
					{#winPeriode_ouNBHeures#}<a onmouseover="return coolTip('{#winPeriode_FormatDuree#|escape:"quotes"}', WIDTH, 200)"  onmouseout="nd()" href="javascript:undefined;"><i class="icon-question-sign"></i></a> :
					<input type="text" name="duree" id="duree" size="3" maxlength="5" value="{if $periode.duree_details eq 'duree'}{$periode.duree|sqltime2usertime}{/if}" style="width:36px" onFocus="if(this.value == '')this.value='{$smarty.const.CONFIG_DURATION_DAY|usertime2sqltime:"short"}';" onChange="videChampsFinTache(this.id);" tabindex="12" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{#winPeriode_heureDebut#} <a onmouseover="return coolTip('{#winPeriode_FormatDuree#|escape:"quotes"}', WIDTH, 200)"  onmouseout="nd()" href="javascript:unedfined;"><i class="icon-question-sign"></i></a> :
					<input type="text" id="heure_debut" id="heure_debut" size="3" maxlength="5" value="{if isset($periode.duree_details_heure_debut)}{$periode.duree_details_heure_debut|sqltime2usertime}{/if}" style="width:36px" onChange="videChampsFinTache(this.id);" tabindex="13" />
					{#winPeriode_heureFin#} <a onmouseover="return coolTip('{#winPeriode_FormatDuree#|escape:"quotes"}', WIDTH, 200)"  onmouseout="nd()" href="javascript:unedfined;"><i class="icon-question-sign"></i></a> : <input type="text" id="heure_fin" size="3" maxlength="5" value="{if isset($periode.duree_details_heure_fin)}{$periode.duree_details_heure_fin|sqltime2usertime}{/if}" style="width:36px" onChange="videChampsFinTache(this.id);" tabindex="14" />
					<br />
					<label class="checkbox inline"><input type="checkbox" id="matin" onChange="videChampsFinTache(this.id);" {if $periode.duree_details eq 'AM'}checked="checked"{/if} tabindex="15">{#winPeriode_matin#} ({$smarty.const.CONFIG_DURATION_AM}{#tab_h#})</label>
					<label class="checkbox inline"><input type="checkbox" id="apresmidi" onChange="videChampsFinTache(this.id);" {if $periode.duree_details eq 'PM'}checked="checked"{/if} tabindex="16">{#winPeriode_apresmidi#} ({$smarty.const.CONFIG_DURATION_PM}{#tab_h#})</label>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			{if !isset($estFilleOuParente)}
				<div class="control-group span12">
					<input type="hidden" id="appliquerATous" value="0">
					<label class="control-label">{#winPeriode_repeter#} :</label>
					<div class="controls">
					    <div id="divReccurence" style="float:left;">
						<select name="repetition" id="repetition" 
						onChange="{literal}
						if(this.value=='jour'){$('divOptionsRepetitionJour').style.display='block';$('divExceptionRepetition').style.display='block';}else{$('divOptionsRepetitionJour').style.display='none';}	if(this.value=='semaine'){$('divOptionsRepetitionSemaine').style.display='block';$('divExceptionRepetition').style.display='block';}else{$('divOptionsRepetitionSemaine').style.display='none';}
						if(this.value=='mois'){$('divOptionsRepetitionMois').style.display='block';$('divExceptionRepetition').style.display='block';}else{$('divOptionsRepetitionMois').style.display='none';}
						if(this.value==''){$('divOptionsRepetitionJour').style.display='none';$('divOptionsRepetitionSemaine').style.display='none';$('divOptionsRepetitionMois').style.display='none';$('divExceptionRepetition').style.display='none';}
						{/literal}" class="input-large select2" tabindex="17">
							<option value="">{#winPeriode_repeter_pasderepetition#}</option>
							<option value="jour">{#winPeriode_repeter_jour#}</option>
							<option value="semaine">{#winPeriode_repeter_semaine#}</option>
							<option value="mois">{#winPeriode_repeter_mois#}</option>
						</select>
						</div>
						<div id="divOptionsRecurrence" style="float:left;">
						<span id="divOptionsRepetitionJour" style="display:none;" tabindex="18">
							&nbsp;&nbsp;&nbsp;
							{#winPeriode_repeter_tousles#}
							<select name='nbRepetitionJour' style="width:53px">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							</select>
                            &nbsp;{#winPeriode_jour#}&nbsp;{#winPeriode_repeter_jusque#}
							<input type="text" id="dateFinRepetitionJour" value="" size="11" maxlength="10" style="width:80px" class="datepicker" onFocus="remplirDateRepetition(this.id);" tabindex="18">
						</span>
						<span id="divOptionsRepetitionSemaine" style="display:none;" tabindex="19">
							<div class="control-group">
							&nbsp;&nbsp;&nbsp;&nbsp;
							{#winPeriode_repeter_tousles#}
							<select name='nbRepetitionSemaine' style="width:53px">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							</select>
                            &nbsp;{#winPeriode_semaine#}&nbsp;{#winPeriode_repeter_jusque#}
							<input type="text" id="dateFinRepetitionSemaine" value="" size="11" maxlength="10" style="width:80px;" class="datepicker" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							<br />
							<label class="radio inline">{#winPeriode_repeter_jourderepetition#} :</label>
							<label class="radio inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="1" checked="checked" />{#initial_day_1#}</label>
							<label class="radio inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="2" />{#initial_day_2#}</label>
							<label class="radio inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="3" />{#initial_day_3#}</label>
							<label class="radio inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="4" />{#initial_day_4#}</label>
							<label class="radio inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="5" />{#initial_day_5#}</label>
							<label class="radio inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="6" />{#initial_day_6#}</label>
							<label class="radio inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="0" />{#initial_day_0#}</label>
							</div>
							</span>
						<span id="divOptionsRepetitionMois" style="display:none;" tabindex="18">
							<div class="control-group">
							&nbsp;&nbsp;&nbsp;&nbsp;
							{#winPeriode_repeter_tousles#}
							<select name='nbRepetitionMois' style="width:53px">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							</select>
                            &nbsp;{#winPeriode_mois#}&nbsp;{#winPeriode_repeter_jusque#}
							<input type="text" id="dateFinRepetitionMois" value="" size="11" maxlength="10" style="width:80px" class="datepicker" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							<br />
							<label class="radio inline">{#winPeriode_repeter_jourderepetition#} :</label>
							<label class="radio inline"><input type="radio" name="radioChoixJourRepetition" id="radioChoixJourRepetition" value="0" checked="checked" />Jour du mois</label>
							</div>
						</span>
						<span id="divExceptionRepetition" style="display:none;" tabindex="19">
							<label class="radio inline">{#winPeriode_repeter_exception_siferie#} :</label>
							<label class="radio inline"><input type="radio" name="exceptionRepetition" id="exceptionRepetition" value="1" checked="checked" />{#winPeriode_repeter_exception_decaler#}</label>
							<label class="radio inline"><input type="radio" name="exceptionRepetition" id="exceptionRepetition" value="2" />{#winPeriode_repeter_exception_pasajout#}</label>
							<label class="radio inline"><input type="radio" name="exceptionRepetition" id="exceptionRepetition" value="3" />{#winPeriode_repeter_exception_ajout#}</label>
						</span>

						</div>
					</div>
				</div>
			{else}
				<div class="control-group span12">
					<label class="control-label">{#winPeriode_repeter#} :</label>
					<div class="controls">
					<br />
						<b>{#winPeriode_recurrente#}{$prochaineOccurence|sqldate2userdate}</b>
					</div>
				</div>
			{/if}
		</div>
		<hr/>
		<div class="row-fluid">
			<div class="control-group span6">
				<label class="control-label">{#winPeriode_statut#} :</label>
				<div class="controls">
					<select name="statut_tache" id="statut_tache" class="input-large select2"  tabindex="19">
						<option value="a_faire" {if $periode.statut_tache eq "a_faire"}selected="selected"{/if}>{#winPeriode_statut_a_faire#}</option>
						<option value="en_cours" {if $periode.statut_tache eq "en_cours"}selected="selected"{/if}>{#winPeriode_statut_en_cours#}</option>
						<option value="fait" {if $periode.statut_tache eq "fait"}selected="selected"{/if}>{#winPeriode_statut_fait#}</option>
						<option value="abandon" {if $periode.statut_tache eq "abandon"}selected="selected"{/if}>{#winPeriode_statut_abandon#}</option>
					</select>
				</div>
			</div>
			<div class="control-group span6">
				<label class="control-label" style="width: 70px;">{#winPeriode_livrable#} :</label>
				<div class="controls" style="margin-left: 90px;">
					<select name="livrable" id="livrable" class="input-large select2" tabindex="20">
						<option value="oui" {if $periode.livrable eq "oui"}selected="selected"{/if}>{#oui#}</option>
						<option value="non" {if $periode.livrable eq "non"}selected="selected"{/if}>{#non#}</option>
					</select>
				</div>
			</div>
		</div>
		<hr />
		<div class="row-fluid">
			<div class="control-group span12">
				<label class="control-label">{#winPeriode_titre#} :</label>
				<div class="controls">
					<input type="text" name="titre" id="titre" size="40" maxlength="2000" value="{$periode.titre|escape}" onFocus="xajax_autocompleteTitreTache($('projet_id').value);" class="input-xxlarge" tabindex="21" />
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="control-group span12">
				<label class="control-label">{#winPeriode_lien#} :</label>
				<div class="controls">
					<input type="text" name="lien" id="lien" size="40" maxlength="2000" value="{$periode.lien}" class="input-xxlarge"  tabindex="22" />
					{if $periode.lien neq ""}
						<a class="btn btn-small" style="padding-top:4px;" onmouseover="return coolTip('{#winPeriode_gotoLien#|escape}', WIDTH, 270)"  onmouseout="nd()" href="{if $periode.lien|strpos:"http" !== 0 && $periode.lien|strpos:"\\" !== 0}http://{/if}{$periode.lien}" target="_blank"><i class="icon-share"></i></a>
					{/if}
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="control-group span12">
				<label class="control-label">{#winPeriode_commentaires#} :</label>
				<div class="controls">
					<textarea style="height:45px;" id="notes" name="notes" class="input-xxlarge" tabindex="23">{$periode.notes_xajax|escape:"html"}</textarea>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="control-group span12">
				<label class="control-label">{#winPeriode_custom#} :</label>
				<div class="controls">
					<input type="text" name="custom" id="custom" size="40" maxlength="255" value="{$periode.custom}" class="input-xxlarge"  tabindex="23" />
						<a href="#" onmouseover="return coolTip('{#winPeriode_custom_aide#|escape:"quotes"}', WIDTH, 270)"  onmouseout="nd()" href="javascript:undefined;"><i class="icon-question-sign"></i></a>
				</div>
			</div>
		</div>
	</div>

	{if !in_array("tasks_readonly", $user.tabDroits)}
		<div class="btn-group pull-right" style="margin-right: 170px;">
			<input id="butSubmitPeriode" type="button" class="btn btn-primary" tabindex="24" value="{#winPeriode_valider#|escape:"html"}" onClick="$('divPatienter').style.display='inline';this.disabled=true;xajax_submitFormPeriode('{$periode.periode_id}', $(projet_id).value, $(user_id).value, $(date_debut).value, $(conserver_duree).checked, $(date_fin).value, $(nb_jours).value, $(duree).value, $(heure_debut).value, $(heure_fin).value, $(matin).checked, $(apresmidi).checked, $(repetition).value, $(dateFinRepetitionJour).value,$(dateFinRepetitionSemaine).value,$(dateFinRepetitionMois).value, $(nbRepetitionJour).value,$(nbRepetitionSemaine).value,$(nbRepetitionMois).value,$(jourSemaine).value,getRadioValue('exceptionRepetition'),$(appliquerATous).checked, $(statut_tache).value, $(livrable).value, $(titre).value, $(notes).value, $(lien).value, $(custom).value, $(user_id2).value, $(user_id3).value);" />
			{if $periode.periode_id neq 0}
				<input type="button" class="btn" onClick="if(confirm('{#winPeriode_dupliquer#|escape:"javascript"} ?'))xajax_ajoutPeriode('', '', {$periode.periode_id});" value="{#winPeriode_dupliquer#}" />
				<input type="button" class="btn btn-warning" onClick="if(confirm('{#winPeriode_confirmSuppr#|escape:"javascript"}'))xajax_supprimerPeriode({$periode.periode_id}, false);" value="{#winPeriode_supprimer#}" />
				{if isset($estFilleOuParente)}
					<input type="button" class="btn" onClick="if(confirm('{#winPeriode_confirmSupprRepetition#|escape:"javascript"}'))xajax_supprimerPeriode({$periode.periode_id}, true);" value="{#winPeriode_supprimer_repetition#}" />
					<input type="button" class="btn" onClick="if(confirm('{#winPeriode_confirmSupprRepetition#|escape:"javascript"}'))xajax_supprimerPeriode({$periode.periode_id}, 'avant');" value="{#winPeriode_supprimer_repetition_avant#}" />
					<input type="button" class="btn" onClick="if(confirm('{#winPeriode_confirmSupprRepetition#|escape:"javascript"}'))xajax_supprimerPeriode({$periode.periode_id}, 'apres');" value="{#winPeriode_supprimer_repetition_apres#}" />
				{/if}
			{/if}
		</div>
		<span id="divPatienter" style="display:none;color:#ff0000;font-weight:bold;"><img src="assets/img/pictos/loading16.gif" width="16" height="16" border="0" /></span>
	{/if}
</form>