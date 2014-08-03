<ul class="linklistCategoryList" data-object-id="0">
	{assign var=oldDepth value=0}
	{foreach from=$categoryList item=category}
		{assign var=isLastSibling value=false}
		{if !$category->hasChildren() && ($categoryList->getDepth() == 1 || $category->isLastSibling())}{assign var=isLastSibling value=true}{/if}

		{section name=i loop=$oldDepth-$categoryList->getDepth()}</ul></li>{/section}

		<li data-object-id="{$category->categoryID}"  class="linklistCategoryContainer{if $isLastSibling} lastSibling{/if}{if $category->isMainCategory} tabularBox tabularBoxTitle marginTop{elseif $categoryList->getDepth() == 0}container marginTop{/if} categoryDepth{$categoryList->getDepth()+1}">
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

				<div class="linklistCategory box48 {@$alternate}">
					<span class="icon icon48 icon-{$category->getIcon()} {if $category->getUnreadLinks() != 0}new{/if}"></span>
					<div>
						<div class="containerHeadline">
							<h3>
								<a href="{link application='linklist' controller='Category' id=$category->categoryID title=$category->getTitle()|language}{/link}">{$category->getTitle()}</a>
								{if $category->getUnreadLinks() != 0}<span class="badge">{#$category->getUnreadLinks()}</span>{/if}
							</h3>

							{hascontent}
								<span class="linklistCategoryDescription">
								{content}{$category->description|language}{/content}
								</span>
							{/hascontent}
						</div>

						<div class="linkStats">
							<dl class="statsDataList plain">
								<dt>{lang}linklist.link.links{/lang}</dt>
								<dd>{#$category->getLinks()}</dd>

								<dt>{lang}linklist.link.visits{/lang}</dt>
								<dd>{#$category->getVisits()}</dd>
							</dl>
						</div>
					</div>
				</div>
			{/if}

			{* children *}
			<ul data-object-id="{@$category->categoryID}">
				{if !$categoryList->current()->hasChildren()}</ul></li>{/if}
				{assign var=oldDepth value=$categoryList->getDepth()}
	{/foreach}

	{section name=i loop=$oldDepth}</ul></li>{/section}
</ul>
