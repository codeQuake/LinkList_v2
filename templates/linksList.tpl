{if $__wcf->session->getPermission('user.linklist.link.canAddLink')}
<div class="contentNavigation">
    {pages print=true assign=pagesLinks controller="Category" id=$categoryID link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
    {hascontent}
        <nav>
            <ul>
                {content}
                    <li><a href="{link application='linklist' controller='LinkAdd' id=$categoryID}{/link}" title="{lang}linklist.link.add{/lang}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}linklist.link.add{/lang}</span></a></li>
                    {event name='contentNavigationButtonsTop'}
                {/content}
            </ul>
        </nav>
        {/hascontent}
</div>
{/if}

{if $objects|count}

<div class="container marginTop shadow jsClipboardContainer" data-type="de.codequake.linklist.link">
	<ol class="linklist containerList" data-type="de.codequake.linklist.link">
		{foreach from=$objects item=link}
			<li id="link{$link->linkID}" class="jsClipboardObject linklistLink {if $link->isDeleted}linkDeleted{/if} {if !$link->isActive}linkDisabled{/if}" {if $link->isDeleted}data-is-deleted="1"{/if} {if !$link->isActive}data-is-active="0"{/if} ">
				<input type="checkbox" class="jsClipboardItem" data-object-id="{@$link->linkID}" style="float:left;"/>
				<div class="box128">					
					<a class="framed" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}"><img src="http://api.webthumbnail.org?width=128&height=128&screen=1280&format=png&url={$link->url}" alt="Captured by webthumbnail.org" /></a>
					<div class="details">
						<div class="containerHeadline">
						<h3>
							<a data-link-id="{@$link->linkID}" class="linklistLinkLink messageGroupLink framed" href="{link application='linklist' controller='Link' id=$link->linkID title=$link->subject}{/link}">{$link->subject}</a> 
							{if MODULE_LIKE && $__wcf->getSession()->getPermission('user.like.canViewLike') && $link->countLikes()[$link->linkID]|isset}<span class="likesBadge badge jsTooltip {if $link->cumulativeLikes > 0}green{elseif $link->cumulativeLikes < 0}red{/if}" title="{lang likes=$link->countLikes()[$link->linkID]->likes dislikes=$link->countLikes()[$link->linkID]->dislikes}wcf.like.tooltip{/lang}">{if $link->cumulativeLikes > 0}+{elseif $link->cumulativeLikes == 0}&plusmn;{/if}{#$link->cumulativeLikes}</span>{/if}
						</h3>
						</div>
						<dl class="plain inlineDataList">
							<dt>{lang}linklist.link.author{/lang}</dt>
							<dd>{$link->username} ({$link->time|DateDiff})</dd>
						</dl>
						<dl class="plain inlineDataList">
							<dt>{lang}linklist.links.visits{/lang}</dt>
							<dd>{$link->visits}</dd>
						</dl>
						<div class="box24">{@$link->getExcerpt()}</div>
						<nav class="jsMobileNavigation buttonGroupNavigation">
							<ul class="buttonList" data-link-id="{$link->linkID}">
								{if $link->isDeleted}<li><span class="icon icon16 icon-trash" title="{lang}linklist.link.deleted{/lang}"></span></li>{/if}
								{if !$link->isActive}<li><span class="icon icon16 icon-off" title="{lang}linklist.link.disabled{/lang}"></span></li>{/if}
							</ul>
						</nav>
						<nav class="buttonGroupNavigation linkNavigation">
							<ul class="buttonGroup smallButtons">
								<li>
									<a class="button" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}"><span class="icon-link icon icon16"></span>
									<span>{lang}linklist.link.visit{/lang}</span></a>
								</li>
							</ul>
						</nav>
					</div>
					
			</li>
		{/foreach}
	</ol>
</div>

<div class="contentNavigation">
    {@$pagesLinks}
    {hascontent}
        <nav>
            <ul>
                {content}
                    <li><a href="{link application='linklist' controller='LinkAdd' id=$categoryID}{/link}" title="{lang}linklist.link.add{/lang}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}linklist.link.add{/lang}</span></a></li>
                    {event name='contentNavigationButtonsTop'}
                {/content}
            </ul>
        </nav>
  {/hascontent}

  <div class="jsClipboardEditor" data-types="[ 'de.codequake.linklist.link' ]"></div>
</div>

{/if}