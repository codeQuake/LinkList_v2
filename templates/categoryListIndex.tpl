{include file='documentHeader'}

<head>
	<title>{if $__wcf->getPageMenu()->getLandingPage()->menuItem != 'linklist.pageMenu.index'}{lang}linklist.pageMenu.index{/lang} - {/if}{PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
	<link rel="canonical" href="{link application='linklist' controller='CategoryList'}{/link}" />
</head>

<body id="tpl{$templateName|ucfirst}">
{capture assign='sidebar'}
	{@$__boxSidebar}
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}linklist.index.title{/lang}</h1>
	</hgroup>
</header>
<section id="dashboard">
	{if $__boxContent|isset}{@$__boxContent}{/if}
</section>

{include file='categoryList' application='linklist'}


<div class="container marginTop">
{hascontent}
	<ul class="containerList infoBoxList">
		{content}
		{if LINKLIST_INDEX_WIO}

		{/if}
		{if MODULE_USERS_ONLINE && LINKLIST_INDEX_STATS && $usersOnlineList->stats[total]}
		<li class="box32 usersOnlineInfoBox">
			<span class="icon icon32 icon-user"></span>
			<div>
				<div class="containerHeadline">
								<h3><a href="{link controller='UsersOnlineList'}{/link}">{lang}wcf.user.usersOnline{/lang}</a> <span class="badge">{#$usersOnlineList->stats[total]}</span></h3>
								<p>{lang}wcf.user.usersOnline.detail{/lang} {lang}linklist.index.usersOnline.record{/lang}</p>
				</div>
				<ul class="dataList">
								{foreach from=$usersOnlineList->getObjects() item=userOnline}
									<li><a href="{link controller='User' object=$userOnline->getDecoratedObject()}{/link}" class="userLink" data-user-id="{@$userOnline->userID}">{@$userOnline->getFormattedUsername()}</a></li>
								{/foreach}
				</ul>
				{if LINKLIST_INDEX_WIO_LEGEND && $usersOnlineList->getUsersOnlineMarkings()|count}
								<div class="usersOnlineLegend">
									<p>{lang}wcf.user.usersOnline.marking.legend{/lang}:</p>
									<ul class="dataList">
										{foreach from=$usersOnlineList->getUsersOnlineMarkings() item=usersOnlineMarking}
											<li>{@$usersOnlineMarking}</li>
										{/foreach}
									</ul>
								</div>
							{/if}
			</div>
		</li>
		{/if}
		<li class="box32 statsInfoBox">
			<span class="icon icon32 icon-bar-chart"></span>
			<div>
				<div class="containerHeadline">
					<h3>{lang}linklist.index.stats{/lang}</h3>
					<p>{lang}linklist.index.stats.detail{/lang}</p>
				</div>
			</div>
		</li>
		{/if}
		{/content}
		
	</ul>
{/hascontent}
</div>

{include file='footer' sandbox=false}
</body>
</html>