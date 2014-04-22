<ul class="linklistCategoryList" data-object-id="0">

  {assign var=oldDepth value=0}
  {foreach from=$categoryList item=category}
  {section name=i loop=$oldDepth-$categoryList->getDepth()}</ul></li>{/section}
  <li data-object-id="{$category->categoryID}"  class="linklistCategoryContainer {if $category->isMainCategory}tabularBox tabularBoxTitle marginTop {else}linklistCategoryContainer {if $categoryList->getDepth() == 0}container marginTop{/if}{/if}  categoryDepth{$categoryList->getDepth()+1}">
    {if $category->isMainCategory}
    <header>
      <h2>
        <a href="{link application='linklist' controller='Category' id=$category->categoryID title=$category->getTitle()|language}{/link}">{$category->getTitle()}</a>
      </h2>
    </header>
    {else}
    {cycle assign=alternate name=alternate values='linklistCategoryNode1,linklistCategoryNode2' print=false}
    {if $categoryList->getDepth()+1 == 1}
      {cycle assign=alternate name=alternate reset=true print=false}
    {/if}
    <div class="linklistCategory box32 {@$alternate}">
        <span class="icon icon32 icon-globe"></span>
        <div>
          <div class="containerHeadline">
            <h3>
              <a href="{link application='linklist' controller='Category' id=$category->categoryID title=$category->getTitle()|language}{/link}">{$category->getTitle()}</a>
            </h3>
            {hascontent}
            <span class="linklistCategoryDescription">
              {content}{$category->description|language}{/content}
            </span>
            {/hascontent}
          </div>
          <div class="linkStats">
            <dl class="statsDataList plain">
              <dt>{lang}linklist.links.list{/lang}</dt>
              <dd>{$category->getLinks()}</dd>
              <dt>{lang}linklist.links.visits{/lang}</dt>
              <dd>{$category->getVisits()}</dd>
            </dl>
          </div>
        </div>
      </div>
    {/if}
    <!--children-->
      <ul data-object-id="{@$category->categoryID}">
          {if !$categoryList->current()->hasChildren()}
          </ul>
          </li>
          {/if}
          {assign var=oldDepth value=$categoryList->getDepth()}
          {/foreach}

          {section name=i loop=$oldDepth}</ul></li>{/section}
</ul>