{if $__linklist->isActiveApplication() && $__searchAreaInitialized|empty}
	{capture assign='__searchInputPlaceholder'}{if $category|isset}{lang}linklist.category.search{/lang}{else}{lang}linklist.category.searchAll{/lang}{/if}{/capture}
	{capture assign='__searchHiddenInputFields'}<input type="hidden" name="types[]" value="de.codequake.linklist.link" />{/capture}
{/if}
