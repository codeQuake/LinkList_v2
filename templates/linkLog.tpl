{include file='documentHeader'}
<head>
	<title>{lang}linklist.link.log{/lang} - {$link->getTitle}  {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header'}

<header class="boxHeadline marginTop">
	<h1><a href="{link application='linklist' controller='LinkLog' object=$link}{/link}">{lang}linklist.link.log{/lang}</a></h1>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	{pages print=true assign=pagesLinks application='linklist' controller='LinkLog' object=$link link="pageNo=%d"}
	
	{hascontent}
		<nav>
			<ul>
				{content}
					{event name='contentNavigationButtonsTop'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>
    {hascontent}
	<div class="tabularBox tabularBoxTitle marginTop">
		<header>
			<h2>{lang}linklist.link.log.title{/lang} <span class="badge badgeInverse">{#$items}</span></h2>
		</header>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnID{if $sortField == 'logID'} active {@$sortOrder}{/if}"><a href="{link application='linklist' controller='LinkLog' object=$link}pageNo={@$pageNo}&sortField=logID&sortOrder={if $sortField == 'logID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
					<th class="columnText">{lang}linklist.link.log.action{/lang}</th>
					<th class="columnText{if $sortField == 'username'} active {@$sortOrder}{/if}"><a href="{link application='linklist' controller='LinkLog' object=$link}pageNo={@$pageNo}&sortField=username&sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.user.username{/lang}</a></th>
					<th class="columnDate{if $sortField == 'time'} active {@$sortOrder}{/if}"><a href="{link application='linklist' controller='LinkLog' object=$link}pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}linklist.links.time{/lang}</a></th>
					
					{event name='columnHeads'}
				</tr>
			</thead>
			<tbody>
				{content}
					{foreach from=$objects item=entry}
						<tr>
							<td class="columnID">{#$entry->logID}</td>
							<td class="columnText">{@$entry}</td>
							<td class="columnText"><a href="{link controller='User' id=$entry->userID title=$entry->username}{/link}" class="userLink" data-user-id="{@$entry->userID}">{$entry->username}</a></td>
							<td class="columnDate">{@$entry->time|time}</td>
							
							{event name='columns'}
						</tr>
					{/foreach}
				{/content}
			</tbody>
		</table>
	</div>
{hascontentelse}
<p class="info">{lang}linklist.link.log.noEntries{/lang}</p>
{/hascontent}

<div class="contentNavigation">
	{@$pagesLinks}
	
	{hascontent}
		<nav>
			<ul>
				{content}
					{event name='contentNavigationButtonsBottom'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>

{include file='footer'}

</body>
</html>
