{capture assign='sidebar'}
        <aside class="sidebar">
		<div>
			<fieldset>
			<legend class="invisible">{lang}linklist.link.sidebar.image{/lang}</legend>
			<div class="userAvatar">
				<a class="framed" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}"><img src="http://api.webthumbnail.org?width=150&height=150&screen=1280&format=png&url={$link->url}" alt="Captured by webthumbnail.org" /></a>
			</div>
		</fieldset>
            <fieldset class="linklistLinkSidebar">
                <legend>{lang}linklist.link.sidebar.info{/lang}</legend>
                <div>
                    <ul class="sidebarBoxList">
                        <li class="box24">
							<span class="icon icon32 icon-link"></span>
                            <div class="sidebarBoxHeadline">
                                <h3>{lang}linklist.link.sidebar.title{/lang}</h3>
                                <small>{$link->getTitle()|language}</small>
                            </div>
                        </li>
                        <li class="box24">
                            <a class="framed" href="{link controller='User' id=$link->getUserID() title=$link->getUsername()}{/link}">
								{@$link->getUserProfile()->getAvatar()->getImageTag(24)}
							</a>
							<div class="sidebarBoxHeadline">
                                <h3>{lang}linklist.link.sidebar.author{/lang}</h3>
                                <small>{if $link->getUserID()}<a href="{link controller='User' id=$link->getUserID() title=$link->getUsername()}{/link}">{$link->getUsername()}</a>{else}{$link->getUsername()}{/if}</small>
                            </div>
                        </li>
                        <li class="box24">
							<a class="framed" href="{link application='linklist' controller='Category' object=$link->getCategory()}{/link}">
								<span class="icon icon32 icon-globe"></span>
							</a>
                            <div class="sidebarBoxHeadline">
                                <h3>{lang}linklist.link.sidebar.category{/lang}</h3>
								<small><a href="{link application='linklist' controller='Category' object=$link->getCategory()}{/link}">{$link->getCategory()->getTitle()|language}</a></small>
                            </div>
                        </li>
                        <li class="box24">
							<span class="icon icon32 icon-external-link"></span>
                            <div class="sidebarBoxHeadline">
                                <h3>{lang}linklist.link.sidebar.visits{/lang}</h3>
                                <small>{$link->visits}</small>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </fieldset>

            <fieldset class="LinklistSidebarButton">
                    <legend></legend>
                <div>
                    <a class="button visitButton" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}"><h3 style="font-size:120%;">{lang}linklist.link.sidebar.visit{/lang}</h3></a>
                </div>
            </fieldset>
		</div>
        </aside>
    {/capture}