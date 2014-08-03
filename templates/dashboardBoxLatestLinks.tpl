{hascontent}
	<ul class="sidebarBoxList">
		{content}
			{foreach from=$latestLinks item=link}
				<li class="box24">
					<a href="{link application='linklist' controller='Link' object=$link->getDecoratedObject()}{/link}" class="framed jsTooltip" title="{lang}linklist.link.visit{/lang}">{@$link->getUserProfile()->getAvatar()->getImageTag(24)}</a>

					<div class="sidebarBoxHeadline">
						<h3><a href="{link application='linklist' controller='Link' object=$link->getDecoratedObject()}{/link}" class="linklistLink" data-link-id="{@$link->linkID}" data-sort-order="DESC" title="{$link->subject}">{$link->subject}</a></h3>
						<small>{if $link->userID}<a href="{link controller='User' object=$link->getUserProfile()->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$link->getUserProfile()->userID}">{$link->username}</a>{else}{$link->username}{/if} - {@$link->time|time}</small>
					</div>
				</li>
			{/foreach}
		{/content}
	</ul>
{/hascontent}
