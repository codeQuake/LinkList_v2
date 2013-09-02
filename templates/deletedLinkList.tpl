{include file='linksList' application='linklist'}
<script data-relocate="true">
    //<![CDATA[
    $(function () {
        WCF.Clipboard.init('wcf\\page\\DeletedContentListPage', {@$objects->getMarkedItems()}, { }, 0);
    });
    //]]>
</script>