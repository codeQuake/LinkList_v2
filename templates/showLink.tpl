<article class="container marginTop containerPadding link" data-object-id="{$link->linkID}" data-link-id="{$link->linkID}" data-object-type="de.codequake.linklist.likeableLink" data-like-liked="{if $likeData[$link->linkID]|isset}{@$likeData[$link->linkID]->liked}{/if}" data-like-likes="{if $likeData[$link->linkID]|isset}{@$likeData[$link->linkID]->likes}{else}0{/if}" data-like-dislikes="{if $likeData[$link->linkID]|isset}{@$likeData[$link->linkID]->dislikes}{else}0{/if}" data-like-users='{if $likeData[$link->linkID]|isset}{ {implode from=$likeData[$link->linkID]->getUsers() item=likeUser}"{@$likeUser->userID}": { "username": "{$likeUser->username|encodeJSON}" }{/implode} }{else}{ }{/if}'>
  {@$link->getFormattedMessage()}
  <footer class="messageOptions marginTop">
    <nav class="buttonGroupNavigation">
      <ul class="smallButtons buttonGroup">
        <li>
          <a class="button small" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}">
            <span>{lang}linklist.link.visit{/lang}</span>
          </a>
        </li>
       <!-- TODO: Abfrage-->
        <li>
          <a class="button small" href="{link application='linklist' controller='LinkEdit' object=$link}{/link}">
            <span class="icon icon16 icon-pencil"></span>
            <span>{lang}linklist.link.edit{/lang}</span>
          </a>
        </li>
      </ul>
    </nav>
    
</footer>
</article>