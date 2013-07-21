<div class="container marginTop containerPadding link">
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
        <li>
          <a class="button small">test</a>
        </li>
      </ul>
    </nav>
    
</footer>
</div>