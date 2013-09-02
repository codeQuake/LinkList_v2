{include file='documentHeader'}

<head>
    <title>{$link->getTitle()|language} - {PAGE_TITLE}</title>    
	{include file='headInclude' sandbox=false}
	<link rel="canonical" href="{link application='linklist' controller='Link' object=$link}{/link}" />
	
</head>

<body id="tpl{$templateName|ucfirst}">
    {include file='linkSidebar' application='linklist'}

    {include file='header' sidebarOrientation='right'}
    <article class="link" data-object-id="{$link->linkID}" data-link-id="{$link->linkID}" data-object-type="de.codequake.linklist.likeableLink" data-like-liked="{if $likeData[$link->linkID]|isset}{@$likeData[$link->linkID]->liked}{/if}" data-like-likes="{if $likeData[$link->linkID]|isset}{@$likeData[$link->linkID]->likes}{else}0{/if}" data-like-dislikes="{if $likeData[$link->linkID]|isset}{@$likeData[$link->linkID]->dislikes}{else}0{/if}" data-like-users='{if $likeData[$link->linkID]|isset}{ {implode from=$likeData[$link->linkID]->getUsers() item=likeUser}"{@$likeUser->userID}": { "username": "{$likeUser->username|encodeJSON}" }{/implode} }{else}{ }{/if}'>

	<header class="boxHeadline">
            <h1>{$link->getTitle()|language}</h1>
			{if $link->isOnline == 0}<span class="badge label red">{lang}linklist.link.offline{/lang}</span>{/if}
    </header>
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