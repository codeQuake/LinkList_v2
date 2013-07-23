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
    <header class="boxHeadline">
        <hgroup>
            <h1>{$link->getTitle()|language}</h1>
        </hgroup>
    </header>
    	<section id="linkContent">
			{include file='showLink' application='linklist'}
			{if MODULE_LINKLIST_COMMENTS}
			{include file='linkCommentList' application='linklist'}
			{/if}
		</section>
 

{include file='footer' sandbox=false}
</body>
</html>