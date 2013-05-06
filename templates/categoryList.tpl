<div>
    <ul class="linklistCategoryList">        
        {foreach from=$categoryList item=categoryItem}
        <li class="linklistCategoryContainer tabularBox linklistNodeTop linklistNodeTopEmpty" data-category-id="{@$categoryItem->categoryID}">
            <div class="linklistCategoryNode1 linklistCategory box32">
                <span class="icon icon32 icon-folder-close-alt"></span>
                <div>
                    <hgroup class="containerHeadline">
                        <h1>
                            <a href="{link application='linklist' controller='Category' id=$categoryItem->categoryID title=$categoryItem->getTitle()|language}{/link}">{$categoryItem->getTitle()}</a>
                        </h1>
                        {hascontent}
                            <h2 class="linklistCategoryDescription">
                                {content}{$categoryItem->description|language}{/content}
                            </h2>
                        {/hascontent}
                        {if $categoryItem->hasChildren()}
                            <ul class="subCategory">
                                {implode from=$categoryItem->getChildCategories(0) item=subCategoryItem}
                                     <li data-category-id="{@$subCategoryItem->categoryID}">
                                        <span class="icon icon16 icon-folder-close-alt"></span>
                                            <a href="{link application='linklist' controller='Category' id=$subCategoryItem->categoryID title=$subCategoryItem->title|language}{/link}">{$subCategoryItem->title|language}</a>
                                
                                     </li>
                                {/implode}
                            </ul>
                        {/if}
                    </hgroup>
                </div>
            </div>
        </li>
        {/foreach}
    </ul>
</div>
