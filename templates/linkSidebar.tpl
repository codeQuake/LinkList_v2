{capture assign='sidebar'}
		<fieldset>
		<legend>{lang}linklist.link.author{/lang}</legend>
		<div class="box32">
			<div class="userAvatar">
				<a class="framed userLink" data-user-id="{$link->getUserProfile()->userID}" href="{link controller='User' object=$link->getUserProfile()}{/link}">{@$link->getUserProfile()->getAvatar()->getImageTag(24)}</a>
			</div>
			<div class="userDetails">
				<div class="containerHeadline">
					<h3><a class="userLink" data-user-id="{$link->getUserProfile()->userID}" href="{link controller='User' object=$link->getUserProfile()}{/link}">{$link->getUserProfile()->username}</a></h3>
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>{lang}linklist.link.general{/lang}</legend>
		<dl class="plain inlineDataList">
			<dt>{lang}linklist.link.clicks{/lang}</dt>
			<dd>{$link->clicks}</dd>
			<dt>{lang}linklist.link.visits{/lang}</dt>
			<dd>{$link->visits}</dd>
			<dt>{lang}linklist.link.comments{/lang}</dt>
			<dd>{@$commentList->countObjects()}</dd>
		</dl>
	</fieldset>
	{if $link->getCategories()|count}
		<fieldset>
			<legend>{lang}linklist.link.category.categories{/lang}</legend>

			<ul>
				{foreach from=$link->getCategories() item=category}
					<li><a href="{link application='linklist' controller='Category' object=$category}{/link}" class="jsTooltip" title="{lang}linklist.link.categorizedNews{/lang}">{$category->getTitle()}</a></li>
				{/foreach}
			</ul>
		</fieldset>
	{/if}
	{if $tags|count}
		<fieldset>
			<legend>{lang}wcf.tagging.tags{/lang}</legend>
			<ul class="tagList">
			{foreach from=$tags item=tag}
				<li><a href="{link controller='Tagged' object=$tag}objectType=de.codequake.linklist.link{/link}" class="badge tag jsTooltip" title="{lang}wcf.tagging.taggedObjects.de.codequake.linklist.link{/lang}">{$tag->name}</a></li>
			{/foreach}
		</fieldset>
	{/if}
	{event name='boxes'}
{/capture}
