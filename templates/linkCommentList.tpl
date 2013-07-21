<header class="boxHeadline boxSubHeadline">
	<h2>{lang}linklist.link.comments{/lang} <span class="badge">{@$commentList->countObjects()}</span></h2>
</header>
<div class="container  marginTop">
{include file='__commentJavaScript' commentContainerID='linkCommentList'}

{if $commentCanAdd}
    <ul id="linkCommentList" class="commentList containerList" data-can-add="true" data-object-id="{@$link->linkID}" data-object-type-id="{@$commentObjectTypeID}" data-comments="{@$commentList->countObjects()}" data-last-comment-time="{@$lastCommentTime}">
        {include file='commentList'}
    </ul>
{else}
    {hascontent}
        <ul id="linkCommentList" class="commentList containerList" data-can-add="false" data-object-id="{@$link->linkID}" data-object-type-id="{@$commentObjectTypeID}" data-comments="{@$commentList->countObjects()}" data-last-comment-time="{@$lastCommentTime}">
            {content}
                {include file='commentList'}
            {/content}
        </ul>
    {hascontentelse}
        <div class="containerPadding">
            {lang}linklist.link.comments.noEntries{/lang}
        </div>
    {/hascontent}

{/if}
</div>