{* Smarty *}
<form class="form-horizontal" method="POST" action="" target="_blank" name="formUser"  onSubmit="return false;">
	<div class="control-group">
		<label class="control-label">{#user_identifiant#} :</label>
		<div class="controls">
			{if $user_form.saved eq 1}
				<input id="user_id" type="text" value="{$user_form.user_id|escape:"html"}" readonly/>
			{else}
				<input id="user_id" type="text" value="{$user_form.user_id|escape:"html"}" size="3" maxlength="3" />
			{/if}
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">{#user_nom#} :</label>
		<div class="controls">
			<input type="text" value="{$user_form.nom|escape:"html"}" readonly/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">{#user_email#} :</label>
		<div class="controls">
			<input id="email_user" type="text" value="{$user_form.email|escape:"html"}" size="40" maxlength="255" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">{#user_login#} :</label>
		<div class="controls">
			<input type="text" value="{$user_form.nom|escape:"html"}" readonly/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">{#user_password#} :</label>
		<div class="controls">
			<input id="password_tmp" type="password" value="" size="20" maxlength="20" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">{#user_notifications#} :</label>
		<div class="controls">
			<label class="radio inline">
				<input type="radio" id="notificationsOui" name="notifications" value="oui" {if $user_form.notifications eq "oui"}checked="checked"{/if}>&nbsp;{#oui#}
			</label>
			<label class="radio inline">
				<input type="radio" id="notificationsNon" name="notifications" value="non" {if $user_form.notifications eq "non"}checked="checked"{/if} style="margin-left:20px;">&nbsp;{#non#}
			</label>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">{#user_prefs_vuedefaut#} :</label>
		<div class="controls">
			<label class="radio inline">
				<input type="radio" id="vueDefautPersonne" name="user_prefs_vuedefaut" value="vuePersonne" {if $user_form.tabPreferences.vueDefaut eq "" or $user_form.tabPreferences.vueDefaut eq "vuePersonne"}checked="checked"{/if}>&nbsp;{#menuPlanningCompletPersonne#}
			</label>
			<label class="radio inline">
				<input type="radio" id="vueDefautProjet" name="user_prefs_vuedefaut" value="vueProjet" {if $user_form.tabPreferences.vueDefaut eq "vueProjet"}checked="checked"{/if} style="margin-left:20px;">&nbsp;{#menuPlanningCompletProjet#}
			</label>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<label class="radio inline">
				<input type="radio" id="vueDefautMois" name="user_prefs_vuedefaut_jourmois" value="vueMois" {if $user_form.tabPreferences.vueJourMois eq "" or $user_form.tabPreferences.vueJourMois eq "vueMois"}checked="checked"{/if}>&nbsp;{#menuPlanningMois#}
			</label>
			<label class="radio inline">
				<input type="radio" id="vueDefautJour" name="user_prefs_vuedefaut_jourmois" value="vueJour" {if $user_form.tabPreferences.vueJourMois eq "vueJour"}checked="checked"{/if} style="margin-left:20px;">&nbsp;{#menuPlanningJour#}
			</label>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<label class="radio inline">
				<input type="radio" id="vueDefautLarge" name="user_prefs_vuedefaut_largereduit" value="vueLarge" {if $user_form.tabPreferences.vueLargeReduit eq "" or $user_form.tabPreferences.vueLargeReduit eq "vueLarge"}checked="checked"{/if}>&nbsp;{#menuPlanningLarge#}
			</label>
			<label class="radio inline">
				<input type="radio" id="vueDefautReduit" name="user_prefs_vuedefaut_largereduit" value="vueReduit" {if $user_form.tabPreferences.vueLargeReduit eq "vueReduit"}checked="checked"{/if} style="margin-left:20px;">&nbsp;{#menuPlanningReduit#}
			</label>
		</div>
	</div>	
	<input type="button" class="btn btn-primary" value="{#submit#}" onClick="xajax_submitFormProfil('{$user_form.user_id|escape}', $('email_user').value, $('password_tmp').value, $('notificationsOui').checked,$('vueDefautPersonne').checked,$('vueDefautMois').checked,$('vueDefautLarge').checked);" style="margin-left: 180px;margin-top:20px;"/>
</form>