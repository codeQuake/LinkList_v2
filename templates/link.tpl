{include file='documentHeader'}

<head>
    <title>{$link->getTitle()|language} - {PAGE_TITLE}</title>    
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">
    {include file='linkSidebar'}

    {include file='header' sidebarOrientation='right'}
    <header class="boxHeadline">
        <hgroup>
            <h1>{$link->getTitle()|language}</h1>
        </hgroup>
    </header>
    <section id="linkContent">
		<nav class="tabMenu">
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
				<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
					<a title="link" href="{@$__wcf->getAnchor('link')}">
						{lang}linklist.link.tabs.link{/lang}
					</a>
				</li>
			</ul>
		</nav>
		<div id="link" class="container tabMenuContent shadow ui-tabs-panel ui-widget-content ui-corner-bottom" data-menu-item="link">
			<article id="wcf{$link->linkID}" class="message dividers marginTop">
				<div>
					<section class="messageContent">
						<div>
							<div class="messageBody">
								<div class="messageText" style="border: none;">
									<div>
										{@$link->getFormattedMessage()}
									</div>
								</div>
								<footer class="messageOptions contentOptions marginTop clearfix">
									<div>
										<a class="button" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}"><h1>{lang}linklist.link.visit{/lang}</h1></a>
										<a class="button" href="{link application='linklist' controller='LinkEdit' object=$link}{/link}"><h1>{lang}linklist.link.edit{/lang}</h1></a>
									</div>
								</footer>
							</div>
						</div>
					</section>
				</div>
			</article>
		</div>
	</section>
 

{include file='footer' sandbox=false}
</body>
</html>