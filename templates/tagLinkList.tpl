<div class="marginTop tabularBox tabularBoxTitle messageGroupList linklistLinkList">
	<header>
		<h2>{lang}linklist.link.links{/lang}</h2>
	</header>
	
	<table class="table">
		<thead>
			<tr>
				<th colspan="2" class="columnTitle columnSubject">{lang}linklist.links.title{/lang}</th>
				<th class="columnDigits columnVisits">{lang}linklist.links.visits{/lang}</th>
				<th class="columnText columntime">{lang}linklist.links.time{/lang}</th>
				
				{event name='columnHeads'}
			</tr>
		</thead>
		
		<tbody>
			{foreach from=$objects item=link}
					{if $link->isVisible()}
                        <tr id="link{$link->linkID}" class="jsClipboardObject linklistLink"  data-element-id="{@$link->linkID}">
                          <td class="columnIcon">
                            <span class="icon icon32 icon-link"></span>
                          </td>
                            <td class="columnText columnSubject">
								<h3>
									<a data-link-id="{@$link->linkID}" class="linklistLink messageGroupLink framed" href="{link application='linklist' controller='Link' id=$link->linkID title=$link->subject}{/link}">{$link->subject}</a>
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
