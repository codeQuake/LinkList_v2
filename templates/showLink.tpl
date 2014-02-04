<div class="container marginTop containerPadding link box256">
  <div>
    <a class="framed" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}><img src="http://api.webthumbnail.org?width=250&amp;height=250&amp;screen=1280&amp;format=png&amp;url={$link->url}" alt="Captured by webthumbnail.org" /></a>
  </div>
  <div>
    {@$link->getFormattedMessage()}

    {include file='attachments'}
  </div>
  <footer class="linkOptions marginTop">
    <nav class="buttonGroupNavigation jsMobileNavigation">
      <ul class="smallButtons buttonGroup">
        <li>
          <a class="button small" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}>
          <span class="icon icon16 icon-link"></span>
          <span>{lang}linklist.link.visit{/lang}</span>
          </a>
        </li>
        {if $link->getCategory()->getPermission('canEditLink') || ($link->getCategory()->getPermission('canEditOwnLink') && $link->userID = $__wcf->getSession()->userID)}
        <li>
          <a class="button small" href="{link application='linklist' controller='LinkEdit' object=$link}{/link}">
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
        <a title="{$link->ipAddress}" class="jsTooltip button small">
          <span class="icon icon-globe icon16"></span>
          <span class="invisible">
            {$link->ipAddress}
          </span>
        </a>
      </li>
        {/if}
      </ul>
    </nav>

  </footer>
</div>