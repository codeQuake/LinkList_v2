{include file='documentHeader'}

<head>
	<title>{$link->getTitle()|language} - {PAGE_TITLE|language}</title>

	{include file='headInclude'}
	<link rel="canonical" href="{link application='linklist' controller='Link' object=$link}{/link}" />
	<script data-relocate="true" src="{@$__wcf->getPath('linklist')}js/LINKLIST.js?v={@$__wcfVersion}"></script>

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

				{if MODULE_LIKE && $__wcf->getSession()->getPermission('user.like.canViewLike') && LINKLIST_ENABLE_LIKES}new LINKLIST.Link.Like({if $__wcf->getUser()->userID && $__wcf->getSession()->getPermission('user.like.canLike')}1{else}0{/if}, {@LIKE_ENABLE_DISLIKE}, {@LIKE_SHOW_SUMMARY}, {@LIKE_ALLOW_FOR_OWN_CONTENT});{/if}

				new WCF.Action.Delete('linklist\\data\\link\\LinkAction', '.jsLink');
				new WCF.Message.Share.Content();
				new WCF.Moderation.Report.Content('de.codequake.linklist.link', '.jsReportLink');
			});
			//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">
	{*
	{capture assign='headerNavigation'}
	{if $link->getCategory()->getPermission('canEditLink')}
		<li><a href="{link application='linklist' controller='LinkLog' object=$link}{/link}" title="{lang}linklist.link.log{/lang}" class="jsTooltip"><span class="icon icon16 icon-tasks"></span> <span class="invisible">{lang}linklist.link.log{/lang}</span></a></li>
	{/if}
	{/capture}
	*}

{if !$anchor|isset}{assign var=anchor value=$__wcf->getAnchor('top')}{/if}
	{include file='linkSidebar' application='linklist'}

	{include file='header' sidebarOrientation='right'}
	<header class="boxHeadline">
			<h1>{$link->getTitle()|language}</h1>
		</ul>
	</header>
	{include file='userNotice'}
	<ul class="messageList">
	<li>
			<article class="message messageReduced marginTop jsLink jsMessage" data-user-id="{$link->userID}" data-object-id="{$link->linkID}" data-link-id="{$link->linkID}" data-is-deleted="{$link->isDeleted}" data-is-disabled="{$link->isDisabled}" data-object-type="de.codequake.linklist.likeableLink" data-like-liked="{if $linkLikeData[$link->linkID]|isset}{@$linkLikeData[$link->linkID]->liked}{/if}" data-like-likes="{if $linkLikeData[$link->linkID]|isset}{@$linkLikeData[$link->linkID]->likes}{else}0{/if}" data-like-dislikes="{if $linkLikeData[$link->linkID]|isset}{@$linkLikeData[$link->linkID]->dislikes}{else}0{/if}" data-like-users='{if $linkLikeData[$link->linkID]|isset}{ {implode from=$linkLikeData[$link->linkID]->getUsers() item=likeUser}"{@$likeUser->userID}": { "username": "{$likeUser->username|encodeJSON}" }{/implode} }{else}{ }{/if}'>
				<div>
					{assign var='objectID' value=$link->linkID}
					<section class="messageContent">
						<div>
							<header class="messageHeader">
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
							</header>
							<div class="messageBody">
								<div class="linkBox256">
									<div class="framed">
										<img src="{$link->getThumb()}" alt="" />
									</div>
									<div class="linkText">
										{@$link->getFormattedMessage()}
									</div>
								</div>
								{include file='attachments'}
							<div class="messageFooter"></div>
								<footer class="messageOptions">
									<nav class="buttonGroupNavigation jsMobileNavigation">
										<ul class="smallButtons buttonGroup">
											<li><a class="button buttonPrimary" href="{link controller='LinkVisit' application='linklist' object=$link}{/link}">{lang}linklist.link.visit{/lang}</a></li>
											{if $link->canModerate()}<li><a href="{link controller='LinkEdit' application='linklist' object=$link}{/link}" class="button jsMessageEditButton" title="{lang}wcf.global.button.edit{/lang}"><span class="icon icon16 icon-pencil"></span> <span>{lang}wcf.global.button.edit{/lang}</span></a></li>{/if}
											{if LOG_IP_ADDRESS && $link->ipAddress && $__wcf->session->getPermission('admin.user.canViewIpAddress')}<li class="jsIpAddress jsOnly" data-news-id="{@$link->linkID}"><a title="{lang}linklisst.link.ipAddress{/lang}" class="button jsTooltip"><span class="icon icon16 icon-globe"></span> <span class="invisible">{lang}linklist.link.ipAddress{/lang}</span></a></li>{/if}
											{if $link->canModerate()}<li class="jsOnly"><div class="button"><span class="icon icon16 icon-remove jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$link->linkID}" data-confirm-message="{lang}linklist.link.delete.sure{/lang}"></span></div></li>{/if}
											{event name='messageOptions'}
											<li class="toTopLink"><a href="{@$anchor}" title="{lang}wcf.global.scrollUp{/lang}" class="button jsTooltip"><span class="icon icon16 icon-arrow-up"></span> <span class="invisible">{lang}wcf.global.scrollUp{/lang}</span></a></li>

										</ul>
									</nav>
								</footer>
							</div>
						</div>
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
