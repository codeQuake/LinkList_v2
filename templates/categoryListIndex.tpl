{include file='documentHeader'}

<head>
	<title>{if $__wcf->getPageMenu()->getLandingPage()->menuItem != 'linklist.pageMenu.index'}{lang}linklist.pageMenu.index{/lang} - {/if}{PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">
{capture assign='sidebar'}
	{@$__boxSidebar}
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}linklist.index.title{/lang}</h1>
	</hgroup>
</header>


{include file='categoryList' application='linklist'}


<div class="container marginTop">
{hascontent}
	<ul class="containerList infoBoxList">
		{if LINKLIST_INDEX_STATS}
		{content}
		<li class="box32 statsInfoBox">
			<span class="icon icon32 icon-bar-chart"></span>
			<div>
				<div class="containerHeadline">
					<h3>{lang}linklist.index.stats{/lang}</h3>
					<p>{lang}linklist.index.stats.detail{/lang}</p>
				</div>
			</div>
		</li>
		{/content}
		{/if}
	</ul>
{/hascontent}
</div>

{include file='footer' sandbox=false}
</body>
</html>