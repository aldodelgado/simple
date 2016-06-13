{* Smarty *}
{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="span12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/projets.php" class="btn btn-small" ><img src="assets/img/pictos/projets.png" border="0" width="18">&nbsp;{#menuListeProjets#}</a>
					<a href="{$BASE}/groupe_form.php" class="btn btn-small"><img src="assets/img/pictos/addgroupe.png" border="0" width="18">&nbsp;{#menuCreerGroupe#}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<div class="soplanning-box margin-top-10">
				<form action="" method="GET" class="form-inline">
					<label class="checkbox inline">{#projet_liste_afficherGroupesProjets#} :</label>
					<label class="checkbox inline">
						<input type="checkbox" name="statut[]" id="a_faire" value="a_faire" {if in_array('a_faire', $listeStatuts)}checked="checked"{/if}>{#projet_liste_statutAfaire#}
					</label>
					<label class="checkbox inline">
						<input type="checkbox" name="statut[]" id="en_cours" value="en_cours" {if in_array('en_cours', $listeStatuts)}checked="checked"{/if}>{#projet_liste_statutEnCours#}
					</label>
					<label class="checkbox inline">
						<input type="checkbox" name="statut[]" id="fait" value="fait" {if in_array('fait', $listeStatuts)}checked="checked"{/if}>{#projet_liste_statutFait#}
					</label>
					<label class="checkbox inline">
						<input type="checkbox" name="statut[]" id="abandon" value="abandon" {if in_array('abandon', $listeStatuts)}checked="checked"{/if}>{#projet_liste_statutAbandon#}
					</label>
					<input type="submit" value="{#formAfficher#|escape:"html"}" class="btn btn-small" style="margin-left: 10px;"/>

					<div class="btn-group" style="margin-left:70px">
						<div class="input-append">
							<input type="text" style="width:150px;" name="rechercheProjet" value="{$rechercheProjet|default:""}" />
							<input type="submit" value="{#projet_liste_chercher#}" class="btn {if $rechercheProjet neq ""}btn-danger{/if}" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<div class="soplanning-box margin-top-10">
				{if $groupes|@count > 0}
					<table class="table table-striped">
						<tr>
							<th>&nbsp;</th>
							<th align="center">
								{if $order eq "nom"}
									{if $by eq "asc"}
										<a href="{$BASE}/groupe_list.php?page=1&order=nom&by=desc">{#groupe_liste_nom#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" border="0" alt="" />
									{else}
										<a href="{$BASE}/groupe_list.php?page=1&order=nom&by=asc">{#groupe_liste_nom#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" border="0" alt="" />
									{/if}
								{else}
									<a href="{$BASE}/groupe_list.php?page=1&order=nom&by={$by}">{#groupe_liste_nom#} ({$groupes|@count})</a>
								{/if}
							</th>
							{assign var=totalProjets value=0}
							{foreach name=groupes item=groupe from=$groupes}
								{assign var=totalProjets value=$totalProjets+$groupe.totalProjets}
							{/foreach}
							<th>{#groupe_liste_nbProjets#} ({$totalProjets})</th>
						</tr>
						{foreach name=groupes item=groupe from=$groupes}
							{assign var=couleurLigne value="#ffffff"}
							<tr bgcolor='{$couleurLigne}' onMouseOver="javascript:this.style.backgroundColor='#EEEEEE'" onMouseOut="javascript:this.style.backgroundColor='{$couleurLigne}'">
								<td align="center" nowrap="nowrap">
									<a href="{$BASE}/groupe_form.php?groupe_id={$groupe.groupe_id}"><img src="{$BASE}/assets/img/pictos/edit.gif" border="0" width="16" height="16" /></a>
									&nbsp;
									<a href="{$BASE}/process/groupe_save.php?groupe_id={$groupe.groupe_id}&action=delete" onClick="javascript:return confirm('{#groupe_liste_confirmSuppr#|escape:"javascript"}')"><img src="{$BASE}/assets/img/pictos/delete.gif" border="0" width="16" height="16" /></a>
								</td>
								<td>{$groupe.nom|xss_protect}&nbsp;</td>
								<td>{$groupe.totalProjets}&nbsp;</td>
							</tr>
							<tr>
							</tr>
						{/foreach}
						{if $nbPages > 1}
							<tr>
								<td colspan="7" align="right">
									{if $currentPage > 1}<a href="{$BASE}/groupe_list.php?page={$currentPage-1}">&lt;&lt; {#action_precedent#}</a>&nbsp;&nbsp;{/if}
									{section name=pagination loop=$nbPages}
										{if $smarty.section.pagination.iteration == $currentPage}<b>{else}<a href="{$BASE}/groupe_list.php?page={$smarty.section.pagination.iteration}">{/if}
										{$smarty.section.pagination.iteration}
										{if $smarty.section.pagination.iteration == $currentPage}</b>{else}</a>{/if}&nbsp;
									{/section}
									{if $currentPage < $nbPages}<a href="{$BASE}/groupe_list.php?page={$currentPage+1}">{#action_suivant#} &gt;&gt;</a>{/if}
								</td>
							</tr>
						{/if}
					</table>
				{else}
					{#info_noRecord#}
				{/if}
			</div>
		</div>
	</div>
</div>

{* CHARGEMENT SCROLL Y *}

<script language="javascript">
	{literal}

	var yscroll = getCookie('yposProjets');
	window.onscroll = function() {document.cookie='yposProjets=' + window.pageYOffset;};
	addEvent(window, 'load', chargerYScrollPos);

	{/literal}
</script>

{include file="www_footer.tpl"}