{include file='documentHeader'}

<head>
    <title>{$link->getTitle()|language} - {PAGE_TITLE}</title>    
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">
    {capture assign='sidebar'}
        <aside class="sidebar">
            <fieldset class="linklistLinkSidebar">
                <legend>{lang}linklist.link.sidebar.info{/lang}</legend>
                <div>
                    <ul class="sidebarBoxList">
                        <li class="box24">
                            <div class="sidebarBoxHeadline">
                                <h1>{lang}linklist.link.sidebar.title{/lang}</h1>
                                <h2><small>{$link->getTitle()|language}</small></h2>
                            </div>
                        </li>
                        <li class="box24">
                            <div class="sidebarBoxHeadline" style="margin-top: 10px;">
                                <h1>{lang}linklist.link.sidebar.author{/lang}</h1>
                                <h2><small><a href="{link controller='User' id=$link->getUserID() title=$link->getUsername()}{/link}">{$link->getUsername()}</a></small></h2>
                            </div>
                        </li>
                        <li class="box24">
                            <div class="sidebarBoxHeadline" style="margin-top: 10px;">
                                <h1>{lang}linklist.link.sidebar.category{/lang}</h1>
                                <h2><small><a href="{link application='linklist' controller='Category' object=$link->getCategory()}{/link}">{$link->getCategory()->getTitle()|language}</a></small></h2>
                            </div>
                        </li>
                        <li class="box24">
                            <div class="sidebarBoxHeadline" style="margin-top: 10px;">
                                <h1>{lang}linklist.link.sidebar.visits{/lang}</h1>
                                <h2><small>{$link->visits}</small></h2>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </fieldset>

            <fieldset class="LinklistSidebarButton">
                    <legend></legend>
                <div>
                    <a class="button" href="{link controller='LinkVisit' object=$link}{/link}"><h1 style="font-size:120%;">{lang}linklist.link.sidebar.visit{/lang}</h1></a>
                </div>
            </fieldset>
        </aside>
    {/capture}

    {include file='header' sidebarOrientation='left'}
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
								<div class="messageText"style="border: none;">
									<div>
										{@$link->getFormattedMessage()}
									</div>
								</div>
								<footer class="messageOptions contentOptions marginTop clearfix">
									<div>
										<a class="button" href="{link controller='LinkVisit' object=$link}{/link}"><h1 style="font-size:120%;">{lang}linklist.link.sidebar.visit{/lang}</h1></a>
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