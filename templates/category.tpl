{include file='documentHeader'}

<head>
	<title>{$category->getTitle()|language} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
		<script type="text/javascript">
			//<![CDATA[
				WCF.Clipboard.init('linklist\\page\\CategoryPage', {@$hasMarkedItems}, { });
			//]]>
		</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{$category->getTitle()|language}</h1>
		{hascontent}<h2>{content}{$category->description|language}{/content}</h2>{/hascontent}
	</hgroup>
</header>

<section id="dashboard">
	{if $__boxContent|isset}{@$__boxContent}{/if}
</section>
{include file='categoryList' application='linklist'}

{include file='linksList' application='linklist'}

{include file='footer' sandbox=false}
</body>
</html>