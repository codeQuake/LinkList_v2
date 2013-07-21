{include file='documentHeader'}

<head>
    <title>{$link->getTitle()|language} - {PAGE_TITLE}</title>    
	{include file='headInclude' sandbox=false}
	<script type="text/javascript">
	//<![CDATA[
		$(function() {
					new LINKLIST.Link.TabMenu({@$link->linkID});

					WCF.TabMenu.init();
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