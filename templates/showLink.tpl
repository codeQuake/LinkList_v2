<div class="container marginTop containerPadding link" >
  {@$link->getFormattedMessage()}

  {include file='attachments'}
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