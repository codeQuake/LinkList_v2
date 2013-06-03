{include file='documentHeader'}

<head>
    <title>{$link->getTitle()|language} - {PAGE_TITLE}</title>    
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">
    {include file='linkSidebar' application='linklist'}

    {include file='header' sidebarOrientation='right'}
    <header class="boxHeadline">
        <hgroup>
            <h1>{$link->getTitle()|language}</h1>
        </hgroup>
    </header>
    	<section id="linkContent" class="marginTop tabMenuContainer" data-active="{$__wcf->getLinkMenu()->getActiveMenuItem()->getIdentifier()}">
			<nav class="tabMenu">
				<ul>
				{foreach from=$__wcf->getLinkMenu()->getMenuItems() item=menuItem}
					<li>
						<a href="{$__wcf->getAnchor($menuItem->getIdentifier())}" title="{lang}{@$menuItem->menuItem}{/lang}">{lang}linklist.link.menu.{@$menuItem->menuItem}{/lang}
						</a>
					</li>
				{/foreach}
				</ul>
			</nav>

			{foreach from=$__wcf->getLinkMenu()->getMenuItems() item=menuItem}
				<div id="{$menuItem->getIdentifier()}" class="container containerPadding tabMenuContent shadow" data-menu-item="{$menuItem->menuItem}">{if $menuItem === $__wcf->getLinkMenu()->getActiveMenuItem()} {@$linkContent}
				{/if}</div>
			{/foreach}
		</section>
 

{include file='footer' sandbox=false}
</body>
</html>