{include file='documentHeader'}

<head>
    <title>{$link->getTitle()|language} - {PAGE_TITLE}</title>    
	{include file='headInclude' sandbox=false}
	<script type="text/javascript">
	//<![CDATA[
		$(function() {
			{if MODULE_LIKE && $__wcf->getSession()->getPermission('user.like.canViewLike')}new LINKLIST.Link.Like({if $__wcf->getUser()->userID && $__wcf->getSession()->getPermission('user.like.canLike')}1{else}0{/if}, {@LIKE_ENABLE_DISLIKE}, {@LIKE_SHOW_SUMMARY}, {@LIKE_ALLOW_FOR_OWN_CONTENT});{/if}
		});
	//]]>

	</script>
</head>

<body id="tpl{$templateName|ucfirst}">
    {include file='linkSidebar' application='linklist'}

    {include file='header' sidebarOrientation='right'}
    <article class="link" data-object-id="{$link->linkID}" data-link-id="{$link->linkID}" data-object-type="de.codequake.linklist.likeableLink" data-like-liked="{if $likeData[$link->linkID]|isset}{@$likeData[$link->linkID]->liked}{/if}" data-like-likes="{if $likeData[$link->linkID]|isset}{@$likeData[$link->linkID]->likes}{else}0{/if}" data-like-dislikes="{if $likeData[$link->linkID]|isset}{@$likeData[$link->linkID]->dislikes}{else}0{/if}" data-like-users='{if $likeData[$link->linkID]|isset}{ {implode from=$likeData[$link->linkID]->getUsers() item=likeUser}"{@$likeUser->userID}": { "username": "{$likeUser->username|encodeJSON}" }{/implode} }{else}{ }{/if}'>

	<header class="boxHeadline">
        <hgroup>
            <h1>{$link->getTitle()|language}</h1>
        </hgroup>
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