<dl{if $errorField == 'isMainCategory'} class="formError"{/if}>
	<dt class="reversed"><label for="isMainCategory">{lang}linklist.acp.category.isMainCategory{/lang}</label></dt>
		<dd>
			<input type="checkbox" id="isMainCategory" name="isMainCategory"{if $isMainCategory} checked="checked"{/if} value="1" />
			<small>{lang}linklist.acp.category.isMainCategory.description{/lang}</small>
		</dd>
</dl>

<dl{if $errorField == 'icon'} class="formError"{/if}>
	<dt><label for="icon">{lang}linklist.acp.category.icon{/lang}</label></dt>
		<dd>
			<input type="text" id="icon" name="icon" value="{if $icon|isset}{$icon}{/if}" />
			<small>{lang}linklist.acp.category.icon.description{/lang}</small>
		</dd>
</dl>
