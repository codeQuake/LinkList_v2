<div>
  <ul class="linklistCategoryList">
    {foreach from=$categoryList item=categoryItem}
    <li class="linklistCategoryContainer container linklistNodeTop" data-category-id="{@$categoryItem->categoryID}">
      <div class="linklistCategoryNode1 linklistCategory box32">
        <span class="icon icon32 icon-globe"></span>
        <div>
          <div class="containerHeadline">
            <h3>
              <a href="{link application='linklist' controller='Category' id=$categoryItem->categoryID title=$categoryItem->getTitle()|language}{/link}">{$categoryItem->getTitle()}</a>
            </h3>
            {hascontent}
            <span class="linklistCategoryDescription">
              {content}{$categoryItem->description|language}{/content}
            </span>
            {/hascontent}
            
            {if $categoryItem->hasChildren()}
            <ul class="subCategory">
              {foreach from=$categoryItem->getChildCategories() item=subCategoryItem}
              <li data-category-id="{@$subCategoryItem->categoryID}">
                <span class="icon icon16 icon-globe"></span>
                <a href="{link application='linklist' controller='Category' id=$subCategoryItem->categoryID title=$subCategoryItem->title|language}{/link}">{$subCategoryItem->title|language}</a>

              </li>
              {/foreach}
            </ul>
            {/if}
          </div>
            <div class="linkStats">
              <dl class="statsDataList plain">
                <dt>{lang}linklist.links.list{/lang}</dt>
                <dd>{$categoryItem->getLinks()}</dd>
                <dt>{lang}linklist.links.visits{/lang}</dt>
                <dd>{$categoryItem->getVisits()}</dd>
              </dl>
            </div>
        </div>
      </div>
    </li>
    {/foreach}
  </ul>
</div>