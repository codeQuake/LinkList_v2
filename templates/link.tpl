{include file='documentHeader'}

<head>
    <title>{$link->getTitle()|language} - {PAGE_TITLE}</title>    
	{include file='headInclude' sandbox=false}
	<link rel="canonical" href="{link application='linklist' controller='Link' object=$link}{/link}" />	
	<script data-relocate="true" src="{@$__wcf->getPath()}js/WCF.Label{if !ENABLE_DEBUG_MODE}.min{/if}.js?v={@$__wcfVersion}"></script>
</head>

<body id="tpl{$templateName|ucfirst}">
	{capture assign='headerNavigation'}
	{if $link->getCategory()->getPermission('canEditLink')}
		<li><a href="{link application='linklist' controller='LinkLog' object=$link}{/link}" title="{lang}linklist.link.log{/lang}" class="jsTooltip"><span class="icon icon16 icon-tasks"></span> <span class="invisible">{lang}linklist.link.log{/lang}</span></a></li>
	{/if}
	{/capture}
    {include file='linkSidebar' application='linklist'}

    {include file='header' sidebarOrientation='right'}
    <article class="link" data-object-id="{$link->linkID}" data-link-id="{$link->linkID}" data-object-type="de.codequake.linklist.likeableLink" data-like-liked="{if $linkLikeData[$link->linkID]|isset}{@$linkLikeData[$link->linkID]->liked}{/if}" data-like-likes="{if $linkLikeData[$link->linkID]|isset}{@$linkLikeData[$link->linkID]->likes}{else}0{/if}" data-like-dislikes="{if $linkLikeData[$link->linkID]|isset}{@$linkLikeData[$link->linkID]->dislikes}{else}0{/if}" data-like-users='{if $linkLikeData[$link->linkID]|isset}{ {implode from=$linkLikeData[$link->linkID]->getUsers() item=likeUser}"{@$likeUser->userID}": { "username": "{$likeUser->username|encodeJSON}" }{/implode} }{else}{ }{/if}'>

	<header class="boxHeadline labeledHeadline">
            <h1>{$link->getTitle()|language}</h1>
			<ul class="labelList">
			{if $link->isOnline == 0}<li><span class="badge label red">{lang}linklist.link.offline{/lang}</span></li>{/if}
			
			{foreach from=$link->getLabels() item=label}
				<li><span class="label badge{if $label->getClassNames()} {$label->getClassNames()}{/if}">{lang}{$label->label}{/lang}</span></li>
			{/foreach}
		</ul>
    </header>
	{assign var='objectID' value=$link->linkID}
    	<section class="linkContent">
			{include file='showLink' application='linklist'}
			{if MODULE_LINKLIST_COMMENTS}
			{include file='linkCommentList' application='linklist'}
			{/if}
		</section>
 
	</article>
{include file='footer' sandbox=false}
</body>
</html>