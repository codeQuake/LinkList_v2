{if $user->linklistLinks != 0}
	<dt><a href="{link controller='Search'}types[]=de.codequake.linklist.link&userID={@$user->userID}{/link}" title="{lang username=$user->username}wcf.user.searchLinks{/lang}" class="jsTooltip">{lang}linklist.link.links{/lang}</a></dt>
	<dd>{#$user->linklistLinks}</dd>
{/if}
