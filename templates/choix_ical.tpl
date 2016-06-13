{* Smarty *}

<form class="form-horizontal" id="formIcal">
	<div class="control-group">
		<label class="control-label">{#icalExport_users#} :</label>
		<div class="controls">
			<input type="radio" name="ical_users" id="ical_users_moi" value="ical_users_moi" checked="checked" style="margin-top:0px" onClick="xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getCheckboxes('formIcal', 'icalProjetsChoix'));">
			<label style="display:inline" for="ical_users_moi">{#icalExport_users_moi#}</label>
			<br>
			<input type="radio" name="ical_users" id="ical_users_tous" value="ical_users_tous" style="margin-top:0px" onClick="xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getCheckboxes('formIcal', 'icalProjetsChoix'));">
			<label style="display:inline" for="ical_users_tous">{#icalExport_users_tous#}</label>
			<br><br>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">{#icalExport_projets#} :</label>
		<div class="controls">
			<input type="radio" name="ical_projets" id="ical_projets_tous" value="ical_projets_tous" checked="checked" onClick="$('divIcalProjets').style.display='none';" style="margin-top:0px" onClick="xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getCheckboxes('formIcal', 'icalProjetsChoix'));">
			<label style="display:inline" for="ical_projets_tous">{#icalExport_projets_tous#}</label>
			<br>
			<input type="radio" name="ical_projets" id="ical_projets_liste" value="ical_projets_liste" style="margin-top:0px" onClick="$('divIcalProjets').style.display='block';xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getCheckboxes('formIcal', 'icalProjetsChoix'));">
			<label style="display:inline" for="ical_projets_liste">{#icalExport_projets_liste#}</label>
			<div id="divIcalProjets" style="display:none">
				<br>
				{foreach from=$listeProjets item=projet}
					<input type="checkbox" style="margin-top:0px" id="icalProjetsChoix_{$projet.projet_id}", value="{$projet.projet_id}" onClick="xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getCheckboxes('formIcal', 'icalProjetsChoix'));"><label style="display:inline" for="icalProjetsChoix_{$projet.projet_id}">{$projet.nom} ({$projet.projet_id})</label>
					<br>
				{/foreach}
			</div>
			<br><br>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">{#icalExport_url#} :</label>
		<div class="controls">
			<input type="text" id="inputLienIcal" value="{$lienIcal}" style="width:270px">
			&nbsp;<a href="#" onmouseover="return coolTip('{#ical_instructions#|escape:"quotes"}', WIDTH, 270)"  onmouseout="nd()" href="javascript:undefined;"><i class="icon-question-sign"></i></a>
			<br><br>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">{#icalExport_download#} :</label>
		<div class="controls">
			<a href="export_ical.php"><img src="assets/img/pictos/download.png" width="20" height="20" border="0" /></a>
			&nbsp;<a href="#" onmouseover="return coolTip('{#ical_instructions2#}', WIDTH, 270)"  onmouseout="nd()" href="javascript:undefined;"><i class="icon-question-sign"></i></a>
		</div>
	</div>
</form>