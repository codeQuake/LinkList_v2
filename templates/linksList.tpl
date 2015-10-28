
{if $objects|count}

<div class="container marginTop shadow jsClipboardContainer" data-type="de.codequake.linklist.link">
	<ol class="linklist containerList" data-type="de.codequake.linklist.link">
		{foreach from=$objects item=link}
		{if $link->isVisible()}
			<li id="link{$link->linkID}" class="jsClipboardObject link {if $link->isDeleted}linkDeleted{/if} {if !$link->isActive}linkDisabled{/if}" {if $link->isDeleted}data-is-deleted="1"{/if} {if !$link->isActive}data-is-active="0"{/if}>
				{if $link->canTrash() || $link->canDelete() || $link->canToggle()}<input type="checkbox" class="jsClipboardItem" data-object-id="{@$link->linkID}" style="float:left;"/>{/if}
        <div class="box128">
          <div style="height: 128px; width: 128px;">
            <a class="framed" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}>{@$link->getImage(128)}</a>
          </div>
          <div class="details">
            <div class="containerHeadline">
              <h3>
            {foreach from=$link->getLabels() item=label}
				<span class="label badge{if $label->getClassNames()} {$label->getClassNames()}{/if}">{lang}{$label->label}{/lang}</span>
			{/foreach}

                <a data-link-id="{@$link->linkID}" class="linklistLink messageGroupLink framed" href="{link application='linklist' controller='Link' id=$link->linkID title=$link->subject}{/link}">{$link->subject}</a>
                {if MODULE_LIKE && $__wcf->getSession()->getPermission('user.like.canViewLike') && $link->hasLikes() && LINKLIST_ENABLE_LIKES}<span class="likesBadge badge jsTooltip {if $link->cumulativeLikes > 0}green{elseif $link->cumulativeLikes
                  < 0}red{/if}" title="{lang likes=$link->countLikes()->likes dislikes=$link->countLikes()->dislikes}wcf.like.tooltip{/lang}">{if $link->cumulativeLikes > 0}+{elseif $link->cumulativeLikes == 0}&plusmn;{/if}{#$link->cumulativeLikes}
                </span>{/if}
              </h3>
            </div>
            <dl class="plain inlineDataList">
              <dt>{lang}linklist.link.author{/lang}</dt>
              <dd>
                {if $link->getUserProfile()->userID != 0}<a class="userLink" data-user-id="{$link->userID}" href="{link controller='User' object=$link->getUserProfile()}{/link}">{$link->username}</a>{else}{$link->username}{/if} ({$link->time|DateDiff})
              </dd>
            </dl>
            <dl class="plain inlineDataList">
              <dt>{lang}linklist.links.visits{/lang}</dt>
              <dd>{$link->visits}</dd>
            </dl>
            <div>{@$link->getExcerpt()}</div>
            {if $link->getTags()|count && MODULE_TAGGING && LINKLIST_ENABLE_TAGS}
            <ul class="tagList">
              {foreach from=$link->getTags() item=tag}
              <li>
                <a href="{link controller='Tagged' object=$tag}objectType=de.codequake.linklist.link{/link}" class="badge tag jsTooltip" title="{lang}wcf.tagging.taggedObjects.de.codequake.linklist.link{/lang}">{$tag->name}</a>
              </li>
              {/foreach}
            </ul>
            {/if}
            <nav class="buttonGroupNavigation">
              <ul class="buttonList" data-link-id="{$link->linkID}">
                {if $link->isDeleted}<li>
                  <span class="icon icon16 icon-trash" title="{lang}linklist.link.deleted{/lang}"></span>
                </li>{/if}
                {if !$link->isActive}<li>
                  <span class="icon icon16 icon-off" title="{lang}linklist.link.disabled{/lang}"></span>
                </li>{/if}
              </ul>
            </nav>
            <nav class="buttonGroupNavigation linkNavigation jsMobileNavigation">
              <ul class="buttonGroup smallButtons">
                <li>
                  <a class="button" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}>
                    <span class="icon-link icon icon16"></span>
                    <span>{lang}linklist.link.visit{/lang}</span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
			</li>
			{/if}
		{/foreach}
	</ol>
</div>

{/if}