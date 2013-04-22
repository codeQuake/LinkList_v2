{include file='documentHeader'}

<head>
	<title>{if $__wcf->getPageMenu()->getLandingPage()->menuItem != 'linklist.pageMenu.index'}{lang}linklist.pageMenu.index{/lang} - {/if}{PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.category.category.title.category.{$categoryID|language}{/lang}</h1>
		{hascontent}<h2>{content}{lang}wcf.category.category.description.{$categoryID}{/lang}{/content}</h2>{/hascontent}
	</hgroup>
</header>

<section id="dashboard">
	{if $__boxContent|isset}{@$__boxContent}{/if}
</section>
{include file='categoryList'}


{include file='footer' sandbox=false}
</body>
</html>