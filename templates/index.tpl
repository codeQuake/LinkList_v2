{include file='documentHeader'}

<head>
	<title>{if $__wcf->getPageMenu()->getLandingPage()->menuItem != 'linklist.pageMenu.index'}{lang}linklist.pageMenu.index{/lang} - {/if}{PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">
{include file='header' sandbox=false}

<header class="boxHeadline">
	{if $__wcf->getPageMenu()->getLandingPage()->menuItem == 'linklist.pageMenu.index'}
		<hgroup>
			<h1>{PAGE_TITLE|language}</h1>
			{hascontent}<h2>{content}{PAGE_DESCRIPTION|language}{/content}</h2>{/hascontent}
		</hgroup>
	{else}
		<hgroup>
			<h1>{lang}linklist.pageMenu.index{/lang}</h1>
		</hgroup>
	{/if}
</header>

{include file='userNotice'}



{include file='footer' sandbox=false}
</body>
</html>