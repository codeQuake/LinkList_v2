{include file='documentHeader'}

<head>
    <title>{$link->getTitle()|language} - {PAGE_TITLE|language}</title> 
	   
	{include file='headInclude'}
	<link rel="canonical" href="{link application='linklist' controller='Link' object=$link}{/link}" />	
	<script data-relocate="true" src="{@$__wcf->getPath()}js/WCF.Label{if !ENABLE_DEBUG_MODE}.min{/if}.js?v={@$__wcfVersion}"></script>
	<script data-relocate="true" src="{@$__wcf->getPath()}js/WCF.Moderation{if !ENABLE_DEBUG_MODE}.min{/if}.js?v={@$__wcfVersion}"></script>
	<script data-relocate="true">
	  //<![CDATA[
			$(function() {
				WCF.Language.addObject({
					'wcf.message.share': '{lang}wcf.message.share{/lang}',
					'wcf.message.share.facebook': '{lang}wcf.message.share.facebook{/lang}',
					'wcf.message.share.google': '{lang}wcf.message.share.google{/lang}',
					'wcf.message.share.permalink': '{lang}wcf.message.share.permalink{/lang}',
					'wcf.message.share.permalink.bbcode': '{lang}wcf.message.share.permalink.bbcode{/lang}',
					'wcf.message.share.permalink.html': '{lang}wcf.message.share.permalink.html{/lang}',
					'wcf.message.share.reddit': '{lang}wcf.message.share.reddit{/lang}',
					'wcf.message.share.twitter': '{lang}wcf.message.share.twitter{/lang}',
					'wcf.moderation.report.reportContent': '{lang}wcf.moderation.report.reportContent{/lang}',
					'wcf.moderation.report.success': '{lang}wcf.moderation.report.success{/lang}'
					});
				new WCF.Message.Share.Content();
				new WCF.Moderation.Report.Content('de.codequake.linklist.link', '.jsReportLink');
			});
			//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">
	{capture assign='headerNavigation'}
	{if $link->getCategory()->getPermission('canEditLink')}
		<li><a href="{link application='linklist' controller='LinkLog' object=$link}{/link}" title="{lang}linklist.link.log{/lang}" class="jsTooltip"><span class="icon icon16 icon-tasks"></span> <span class="invisible">{lang}linklist.link.log{/lang}</span></a></li>
	{/if}
	{/capture}
	
{if !$anchor|isset}{assign var=anchor value=$__wcf->getAnchor('top')}{/if}
    {include file='linkSidebar' application='linklist'}

    {include file='header' sidebarOrientation='right'}
	<header class="boxHeadline labeledHeadline">
            <h1>{$link->getTitle()|language}</h1>
			<ul class="labelList">
			{foreach from=$link->getLabels() item=label}
				<li><span class="label badge{if $label->getClassNames()} {$label->getClassNames()}{/if}">{lang}{$label->label}{/lang}</span></li>
			{/foreach}
		</ul>
    </header>
	{include file='userNotice'}
	<ul class="messageList">
		<li>
			<article class="link message  messageReduced marginTop jsLink jsMessage" data-object-id="{$link->linkID}" data-link-id="{$link->linkID}" data-object-type="de.codequake.linklist.likeableLink" data-like-liked="{if $linkLikeData[$link->linkID]|isset}{@$linkLikeData[$link->linkID]->liked}{/if}" data-like-likes="{if $linkLikeData[$link->linkID]|isset}{@$linkLikeData[$link->linkID]->likes}{else}0{/if}" data-like-dislikes="{if $linkLikeData[$link->linkID]|isset}{@$linkLikeData[$link->linkID]->dislikes}{else}0{/if}" data-like-users='{if $linkLikeData[$link->linkID]|isset}{ {implode from=$linkLikeData[$link->linkID]->getUsers() item=likeUser}"{@$likeUser->userID}": { "username": "{$likeUser->username|encodeJSON}" }{/implode} }{else}{ }{/if}'>
				<div>
					{assign var='objectID' value=$link->linkID}
					<section class="messageContent">
						<div>
							<header class="messageHeader">
                                <div class="box32">
										<a class="framed" href="{link controller='User' object=$link->getUserProfile()}{/link}">
										{@$link->getUserProfile()->getAvatar()->getImageTag(32)}
									</a>
                                    <div class="messageHeadline">
                                        <h1>
                                            <a href="{link controller='Link' object=$link application='linklist'}{/link}">{$link->getTitle()}</a>
                                        </h1>
                                        <p>
                                            <span class="username">
                                                <a class="userLink" data-user-id="{$link->userID}" href="{link controller='User' object=$link->getUserProfile()}{/link}">
                                                    {$link->username}
                                                </a>
                                            </span>
                                            <a class="permalink" href="{link controller='Link' object=$link application='linklist'}{/link}">
                                                {@$link->time|time}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </header>
							<div class="messageBody">
								<div>
									{if LINKLIST_ENABLE_IMAGE_PREVIEW}<div class="linkImage">
										<a class="framed" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}><img src="http://api.webthumbnail.org?width=200&amp;height=200&amp;screen=1280&amp;format=png&amp;url={$link->url}" alt="Captured by webthumbnail.org" class="previewImage" /></a>
									</div>{/if}
									<div class="messageText">
									  {@$link->getFormattedMessage()}

									  {include file='attachments'}
									</div>
								</div>
								<div class="messageFooter"></div>
								<footer class="messageOptions">
									  <nav class="buttonGroupNavigation jsMobileNavigation">
										<ul class="smallButtons buttonGroup">
										  <li>
											<a class="button" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}>
											<span class="icon icon16 icon-link"></span>
											<span>{lang}linklist.link.visit{/lang}</span>
											</a>
										  </li>
										  {if $link->getCategory()->getPermission('canEditLink') || ($link->getCategory()->getPermission('canEditOwnLink') && $link->userID = $__wcf->getSession()->userID)}
										  <li>
											<a class="button" href="{link application='linklist' controller='LinkEdit' object=$link}{/link}">
											  <span class="icon icon16 icon-pencil"></span>
											  <span>{lang}linklist.link.edit{/lang}</span>
											</a>
										  </li>
										  {/if}
										  <li class="jsReportLink jsOnly" data-object-id="{@$link->linkID}">
											<a title="{lang}wcf.moderation.report.reportContent{/lang}" class="button jsTooltip">
											  <span class="icon icon16 icon-warning-sign"></span>
											  <span class="invisible">{lang}wcf.moderation.report.reportContent{/lang}</span>
											</a>
										  </li>
										  {if $__wcf->getSession()->getPermission('admin.user.canViewIpAddress')}
										  <li>
											<a title="{$link->ipAddress}" class="jsTooltip button">
											  <span class="icon icon-globe icon16"></span>
											  <span class="invisible">
												{$link->ipAddress}
											  </span>
											</a>
										  </li>
										  {/if}
										  <li class="toTopLink"><a href="{@$anchor}" title="{lang}wcf.global.scrollUp{/lang}" class="button jsTooltip"><span class="icon icon16 icon-arrow-up"></span> <span class="invisible">{lang}wcf.global.scrollUp{/lang}</span></a></li>
										</ul>
									  </nav>

									</footer>
						</div></div>
					</section>
				</div>
			</article>
		</li>
	</ul>
			<div class="contentNavigation">			
        <nav>
            <ul>
                <li><a href="{link application='linklist' controller='Link' object=$link appendSession=false}{/link}" class="button jsButtonShare jsOnly" data-link-title="{$link->subject}"><span class="icon icon16 icon-link"></span> <span>{lang}wcf.message.share{/lang}</span></a></li>
			{event name='contentNavigationButtonsBottom'}
            </ul>
        </nav>
        {if ENABLE_SHARE_BUTTONS}
		{include file='shareButtons'}
	    {/if}
    </div>
	{if MODULE_LINKLIST_COMMENTS}{include file='linkCommentList' application='linklist'}{/if}
 
{include file='footer' sandbox=false}
</body>
</html>