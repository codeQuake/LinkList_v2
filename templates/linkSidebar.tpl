{capture assign='sidebar'}
		<fieldset>
			<legend class="invisible">{lang}linklist.link.sidebar.image{/lang}</legend>
			<div class="userAvatar">
				<a href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}></a>
			</div>
		</fieldset>
			{hascontent}
			<fieldset>
				<legend>{lang}wcf.tagging.tags{/lang}</legend>
				{content}
				{if $tags|count && MODULE_TAGGING && LINKLIST_ENABLE_TAGS}
				<ul class="sidebarBoxList">
					<li class="box24 tags">
							<ul class="tagList">
								{foreach from=$tags item=tag}
									<li><a href="{link controller='Tagged' object=$tag}objectType=de.codequake.linklist.link{/link}" class="badge tag jsTooltip" title="{lang}wcf.tagging.taggedObjects.de.codequake.linklist.link{/lang}">{$tag->name}</a></li>
								{/foreach}
							</ul>
					</li>
				</ul>
				{/if}{/content}
			</fieldset>
			{/hascontent}


			<fieldset class="linklistSidebarButton">
					<legend></legend>
				<div>
					<a class="button visitButton" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}" {if EXTERNAL_LINK_TARGET_BLANK}target="_blank"{/if}><h3 style="font-size:120%;">{lang}linklist.link.sidebar.visit{/lang}</h3></a>
				</div>
			</fieldset>
{/capture}
