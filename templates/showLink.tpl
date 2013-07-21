<div class="container containerPadding marginTop">
  {@$link->getFormattedMessage()}
  <footer class="messageOptions marginTop">
  <a class="button" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}">
    <span>{lang}linklist.link.visit{/lang}</span>
  </a>
  <a class="button" href="{link application='linklist' controller='LinkEdit' object=$link}{/link}">
    <span>{lang}linklist.link.edit{/lang}</span>
  </a>
</footer>
</div>