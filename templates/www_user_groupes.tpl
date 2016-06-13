{* Smarty *}
{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="span12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/user_list.php" class="btn btn-small" ><img src="assets/img/pictos/users.png" border="0" width="18">&nbsp;{#menuGestionUsers#}</a>
					<a href="javascript:xajax_modifUserGroupe();undefined;" class="btn btn-small"><img src="assets/img/pictos/adduser_groupes.png" border="0" width="18">&nbsp;{#menuCreerUserGroupe#}</a>
					<a href="javascript:xajax_modifUser();undefined;" class="btn btn-small"><img src="assets/img/pictos/adduser.png" border="0" width="18">&nbsp;{#menuCreerUser#}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<div class="soplanning-box margin-top-10">
				{if $groupes|@count > 0}
					<table class="table table-striped">
						<tr align="middle">
							<th>&nbsp;</th>
							<th align="center">
								{if $order eq "nom"}
									{if $by eq "asc"}
										<a href="{$BASE}/user_groupes.php?page=1&order=nom&by=desc">{#user_liste_groupe#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" border="0" alt="" />
									{else}
										<a href="{$BASE}/user_groupes.php?page=1&order=nom&by=asc">{#user_liste_groupe#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" border="0" alt="" />
									{/if}
								{else}
									<a href="{$BASE}/user_groupes.php?page=1&order=nom&by={$by}">{#user_liste_groupe#} ({$groupes|@count})</a>
								{/if}
							</th>
							{assign var=totalUsers value=0}
							{foreach name=groupes item=groupe from=$groupes}
								{assign var=totalUsers value=$totalUsers+$groupe.totalUsers}
							{/foreach}
							<th>{#user_groupe_nbUsers#} ({$totalUsers})</th>
						</tr>
						{foreach name=groupes item=groupe from=$groupes}
							<tr bgcolor="#FFFFFF" onMouseOver="javascript:this.style.backgroundColor='#EEEEEE'" onMouseOut="javascript:this.style.backgroundColor='#FFFFFF'">
								<td align="center" nowrap="nowrap" width="60">
									<a href="javascript:xajax_modifUserGroupe({$groupe.user_groupe_id});undefined;"><img src="{$BASE}/assets/img/pictos/edit.gif" border="0" width="16" height="16" /></a>
									&nbsp;
									<a href="javascript:if(confirm('{#confirm#|escape:"javascript"}')){literal}{{/literal}javascript:xajax_supprimerUserGroupe({$groupe.user_groupe_id});{literal}}{/literal};undefined;"><img src="{$BASE}/assets/img/pictos/delete.gif" border="0" width="16" height="16" /></a>
								</td>
								<td>{$groupe.nom|escape}&nbsp;</td>
								<td>{$groupe.totalUsers}&nbsp;</td>
							</tr>
						{/foreach}
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