<dl>
    <dt>
        <label for="searchCategories">{lang}linklist.search.categories{/lang}</label>
    </dt>
    <dd>
        <select id="searchCategories" name="categoryIDs[]" multiple="multiple" size="10">
            <option value="*" {if $selectAllCategories} selected="selected"{/if}>{lang}linklist.search.categories.all{/lang}</option>
            <option value="-">--------------------</option>
            <!--loop throug cat's-->
            {foreach from=$nodeList item=$categoryNode}
                <option value="{@$categoryNode->categoryID}">{$categoryNode->title|language}</option>
            {/foreach}
        </select>
        <small>{lang}wcf.global.multiSelect{/lang}</small>
    </dd>
</dl>