
{if $objects|count}

<div class="jsClipboardContainer" data-type="de.codequake.linklist.link">
	<ul class="linklist messageList" data-type="de.codequake.linklist.link">
		{foreach from=$objects item=link}
		{if $link->isVisible()}
			<li id="link{$link->linkID}" class="jsClipboardObject link {if $link->isDisabled}linkDisabled{/if}" {if $link->Disabled}data-is-active="0"{/if}>
		<article class="message messageReduced marginTop">
			<div>
				<section class="messageContent">
					<div>
						<header class="messageHeader">
							<ul class="messageQuickOptions">
								{if $link->canDelete() || $link->canToggle()}<input type="checkbox" class="jsClipboardItem" data-object-id="{@$link->linkID}"/>{/if}
							</ul>
							<div class="messageHeadline">
								<h1><a data-link-id="{@$link->linkID}" class="linklistLink messageGroupLink framed" href="{link application='linklist' controller='Link' id=$link->linkID title=$link->subject}{/link}">{$link->subject}</a></h1>
								<p>
									<span class="username"{if $link->getUserProfile()->userID != 0}<a class="userLink" data-user-id="{$link->userID}" href="{link controller='User' object=$link->getUserProfile()}{/link}">{$link->username}</a>{else}{$link->username}{/if}</span>
									<a class="permalink" href="{link application='linklist' controller='Link' id=$link->linkID title=$link->subject}{/link}">{@$link->time|time}</a>

									{if MODULE_LIKE && $__wcf->getSession()->getPermission('user.like.canViewLike') && $link->likes || $link->dislikes && LINKLIST_ENABLE_LIKES}<span class="likesBadge badge jsTooltip {if $link->cumulativeLikes > 0}green{elseif $link->cumulativeLikes
									< 0}red{/if}" title="{lang likes=$link->likes dislikes=$link->dislikes}wcf.like.tooltip{/lang}">{if $link->cumulativeLikes > 0}+{elseif $link->cumulativeLikes == 0}&plusmn;{/if}{#$link->cumulativeLikes}
									</span>{/if}
								</p>
								<p>
									<span>{$link->visits} {lang}linklist.link.visits{/lang}</span>
								</p>
							</div>
						</header>
						<div class="messageBody">
							<div class="linkBox128">
								<div style="height: 128px; width: 128px;">
									<a class="framed" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}>{@$link->getImage(128)}</a>
								</div>
								<div>
									{if $link->teaser != ''}{@$link->teaser}{else}{@$link->getExcerpt()}{/if}
								</div>
							</div>
							<footer class="messageOptions">
								<nav class="buttonGroupNavigation jsMobileNavigation">
									<ul class="buttonGroup smallButtons">
										<li>
										<a class="button buttonPrimary" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}>
											<span class="icon-link icon icon16"></span>
											<span>{lang}linklist.link.visit{/lang}</span>
										</a>
										</li>
									</ul>
								</nav>
							</footer>
						</div>
					</div>
				</section>
			</div>
		</article>
	{/if}
		{/foreach}
	</ul>
</div>

{/if}
