{include file='documentHeader'}

<head>
	<title>{if $__wcf->getPageMenu()->getLandingPage()->menuItem != 'linklist.pageMenu.index'}{lang}linklist.pageMenu.index{/lang} - {/if}{PAGE_TITLE|language}</title>

	{include file='headInclude' sandbox=false}
	<script data-relocate="true">
		//<![CDATA[
		$(function() {
			new Linklist.Link.MarkAllAsRead();
			});
		//]]>
	</script>
	<link rel="canonical" href="{link application='linklist' controller='CategoryList'}{/link}" />
</head>

<body id="tpl{$templateName|ucfirst}">
{capture assign='headerNavigation'}
	<li class="jsOnly">
		<a title="{lang}cms.news.markAllAsRead{/lang}" class="markAllAsReadButton jsTooltip">
			<span class="icon icon16 icon-ok"></span>
			<span class="invisible">{lang}linklist.link.markAllAsRead{/lang}</span>
		</a>
	</li>

{/capture}
{capture assign='sidebar'}
	{@$__boxSidebar}
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">

	<h1>{lang}linklist.index.title{/lang}</h1>

</header>
{include file='userNotice'}
<section id="dashboard">
	{if $__boxContent|isset}{@$__boxContent}{/if}
</section>

{include file='categoryList' application='linklist'}

{hascontent}
<div class="container marginTop">

	<ul class="containerList infoBoxList">
		{content}
		{if LINKLIST_INDEX_WIO && MODULE_USERS_ONLINE}
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
		{if LINKLIST_INDEX_STATS}

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
</div>

{/hascontent}
{include file='footer' sandbox=false}
</body>
</html>
