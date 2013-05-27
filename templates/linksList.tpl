<div class="contentNavigation">
    {pages print=true assign=pagesLinks controller="Category" id=$categoryID link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
    {hascontent}
        <nav>
            <ul>
                {content}
                    <li><a href="{link application='linklist' controller='LinkAdd' id=$categoryID}{/link}" title="{lang}linklist.link.add{/lang}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}linklist.link.add{/lang}</span></a></li>
                    {event name='contentNavigationButtonsTop'}
                {/content}
            </ul>
        </nav>
        {/hascontent}
</div>

{if $objects|count}
    <div class="tabularBox tabularBoxTitle marginTop jsClipboardContainer" data-type="de.codequake.linklist.link">
        <header>
            <h2>{lang}linklist.links.list{/lang} <span class="badge badgeInverse">{#$items}</span></h2>
        </header>

        <table class="table">
            <thead>
                <tr>
                  <th class="columnMark jsOnly"><label>
                    <input type="checkbox" class="jsClipboardMarkAll" />
                  </label></th>
                    <th class="columnTitle columnLink {if $sortField == 'subject'}active {@$sortOrder}{/if}">
                        <a href="{link application='linklist' controller='Category' id=$categoryID} pageNo={@$pageNo}&sortField=subject&sortOrder={if $sortField== 'subject' && $sortOrder=='ASC'}DESC{else}ASC{/if}{/link}">
                            {lang}linklist.links.title{/lang}
                        </a>
                    </th>
                      <th>
                       <a href="{link application='linklist' controller='Category' id=$categoryID} pageNo={@$pageNo}&sortField=visits&sortOrder={if $sortField== 'visits' && $sortOrder=='ASC'}DESC{else}ASC{/if}{/link}">
                             {lang}linklist.links.visits{/lang}
                        </a>
                    </th>
                    <th>
                        <a href="{link application='linklist' controller='Category' id=$categoryID} pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField== 'time' && $sortOrder=='ASC'}DESC{else}ASC{/if}{/link}">
                            {lang}linklist.links.time{/lang}
                        </a>
                    </th>
                    
                    {event name='columnHeads'}
                </tr>
            </thead>

            <tbody>
                {hascontent}{content}
                    {foreach from=$objects item=link}
                        <tr class="jsClipboardObject link" data-article-id="{@$link->linkID}" data-category-id="{@$link->categoryID}">
                          <td class="columnMark jsOnly">
                            <label>
                              <input type="checkbox" class="jsClipboardItem" data-object-id="{@$link->linkID}" />
                            </label>
                          </td>
                            <td class="columnTitle">
                                <a href="{link application='linklist' controller='Link' id=$link->linkID title=$link->subject}{/link}">{$link->subject}</a>
                            </td>
                          <td class="columnTime">
                                {$link->visits}
                            </td>
                            <td class="columnTime">
                                {$link->time|DateDiff}
                            </td>
                            
                            {event name='columns'}
                        </tr>
                    {/foreach}
                {/content}{/hascontent}
            </tbody>
        </table>
    </div>  

<div class="contentNavigation">
    {@$pagesLinks}
    {hascontent}
        <nav>
            <ul>
                {content}
                    <li><a href="{link application='linklist' controller='LinkAdd' id=$categoryID}{/link}" title="{lang}linklist.link.add{/lang}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}linklist.link.add{/lang}</span></a></li>
                    {event name='contentNavigationButtonsTop'}
                {/content}
            </ul>
        </nav>
        {/hascontent}
</div>
{/if}