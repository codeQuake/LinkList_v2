{if $__wcf->session->getPermission('user.linklist.link.canAddLink')}
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
{/if}

{if $objects|count}
    <div class="tabularBox tabularBoxTitle messageGroupList  shadow marginTop jsClipboardContainer" data-type="de.codequake.linklist.link">
        <header>
            <h2>{lang}linklist.links.list{/lang} <span class="badge badgeInverse">{#$items}</span></h2>
        </header>
        <table class="table">
            <thead>
                <tr>
                  <th class="columnMark"><label>
                    <label>
                      <input type="checkbox" class="jsClipboardMarkAll" />
                    </label>
                  </label></th>
                    <th class="columnTitle columnSubject {if $sortField == 'subject'}active {@$sortOrder}{/if}" colspan="2">
                        <a href="{link application='linklist' controller='Category' id=$categoryID}pageNo={@$pageNo}&sortField=subject&sortOrder={if $sortField== 'subject' && $sortOrder=='ASC'}DESC{else}ASC{/if}{/link}">
                            {lang}linklist.links.title{/lang}
                        </a>
                    </th>
                      <th class="columnDigits columnVisits">
                       <a href="{link application='linklist' controller='Category' id=$categoryID}pageNo={@$pageNo}&sortField=visits&sortOrder={if $sortField== 'visits' && $sortOrder=='ASC'}DESC{else}ASC{/if}{/link}">
                             {lang}linklist.links.visits{/lang}
                        </a>
                    </th>
                    <th class="columnText columnTime">
                        <a href="{link application='linklist' controller='Category' id=$categoryID}pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField== 'time' && $sortOrder=='ASC'}DESC{else}ASC{/if}{/link}">
                            {lang}linklist.links.time{/lang}
                        </a>
                    </th>
                    
                    {event name='columnHeads'}
                </tr>
            </thead>

            <tbody>
					
                    {foreach from=$objects item=link}
					{if $link->isVisible()}
                        <tr id="link{$link->linkID}" class="jsClipboardObject linklistLink {if $link->isDeleted}messageDeleted{/if} {if !$link->isActive}messageDisabled{/if}" {if $link->isDeleted}data-is-deleted="1"{/if} {if !$link->isActive}data-isActive="0"{/if} data-element-id="{@$link->linkID}">
                          <td class="columnMark">
                              <input type="checkbox" class="jsClipboardItem" data-object-id="{@$link->linkID}" />
                          </td>
                          <td class="columnIcon">
                            <span class="icon icon32 {if $link->isDeleted}icon-trash{elseif !$link->isActive && !$link->isDeleted}icon-off{else}icon-link{/if}"></span>
                          </td>
                            <td class="columnText columnSubject">
								<h3>
									<a data-link-id="{@$link->linkID}" class="linklistLinkLink messageGroupLink framed" href="{link application='linklist' controller='Link' id=$link->linkID title=$link->subject}{/link}">{$link->subject}</a>
								</h3>
                            </td>
                          <td class="columnDigits columnVisits">
                                {$link->visits}
                            </td>
                            <td class="columnText columnTime">
                                {$link->time|DateDiff}
                            </td>
                            
                            {event name='columns'}
                        </tr>
						{/if}
                    {/foreach}
					
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

  <div class="jsClipboardEditor" data-types="[ 'de.codequake.linklist.link' ]"></div>
</div>
{/if}