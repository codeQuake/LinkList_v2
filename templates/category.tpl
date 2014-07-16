{include file='documentHeader'}

<head>
	<title>{$category->getTitle()|language} - {PAGE_TITLE|language}</title>

	{include file='headInclude' sandbox=false}
	{if !$category->isMainCategory}
		<script data-relocate="true" type="text/javascript">
			//<![CDATA[
				WCF.Clipboard.init('linklist\\page\\CategoryPage', {@$hasMarkedItems}, { });
			//]]>
		</script>
	{/if}
	<link rel="canonical" href="{link application='linklist' controller='Category' object=$category}{if $pageNo > 1}pageNo={@$pageNo}&{/if}sortField={@$sortField}&sortOrder={@$sortOrder}{/link}" />
</head>

<body id="tpl{$templateName|ucfirst}">
{capture assign='sidebar'}
{if !$category->isMainCategory()}
	{include file='categoryDisplayOptions' application='linklist'}
{/if}
	{@$__boxSidebar}
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">

		<h1>{$category->getTitle()|language}</h1>
		{hascontent}<h2>{content}{$category->description|language}{/content}</h2>{/hascontent}

</header>
{include file='userNotice'}
<section id="dashboard">
	{if $__boxContent|isset}{@$__boxContent}{/if}
</section>

{include file='categoryList' application='linklist' sandbox='false'}

{if !$category->isMainCategory()}
<div class="contentNavigation">
{pages print=true assign=pagesLinks controller="Category" application="linklist" id=$categoryID link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
	{if $category->getPermission('canAddLink')}
	<nav>
		<ul>
		<li>
			<a href="{link application='linklist' controller='LinkAdd' id=$categoryID}{/link}" title="{lang}linklist.link.add{/lang}" class="button">
			<span class="icon icon16 icon-plus"></span>
			<span>{lang}linklist.link.add{/lang}</span>
			</a>
		</li>
		{event name='contentNavigationButtonsTop'}
		</ul>
	</nav>
	{/if}
</div>

{include file='linksList' application='linklist'}

	{if $objects|count}
	<div class="contentNavigation">
	{@$pagesLinks}

		{if $category->getPermission('canAddLink')}
			<nav>
				<ul>

						<li><a href="{link application='linklist' controller='LinkAdd' id=$categoryID}{/link}" title="{lang}linklist.link.add{/lang}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}linklist.link.add{/lang}</span></a></li>
						{event name='contentNavigationButtonsTop'}

				</ul>
			</nav>
		{/if}

	<div class="jsClipboardEditor" data-types="[ 'de.codequake.linklist.link' ]"></div>
	</div>
	{/if}
{/if}
{include file='footer' sandbox=false}
</body>
</html>
