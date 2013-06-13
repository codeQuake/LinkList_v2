{if $__linklist->isActiveApplication() && $__searchAreaInitialized|empty}
	{capture assign='__searchInputPlaceholder'}
		{if $category|isset}{lang}linklist.category.search{/lang}{else}{lang}linklist.category.searchAll{/lang}{/if}
	{/capture}
	{capture append='__searchDropdownOptions'}
		<label><input type="checkbox" name="findLinks" value="1"/> {lang}linklist.search.findLinks{/lang}</label>
	{/capture}
	{capture assign='__searchHiddenInputFields'}
		<input type="hidden" name="types[]" value="de.codequake.linklist.links" />{if $board|isset}<input type="hidden" name="categoryIDs[]" value="{@$category->categoryID}" />{/if}
	{/capture}
{/if}