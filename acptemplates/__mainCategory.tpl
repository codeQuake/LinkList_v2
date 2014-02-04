<dl{if $errorField == 'isMainCategory'} class="formError"{/if}>
	<dt class="reversed"><label for="isMainCategory">{lang}linklist.acp.category.isMainCategory{/lang}</label></dt>
		<dd>
			<input type="checkbox" id="isMainCategory" name="isMainCategory"{if $isMainCategory} checked="checked"{/if} value="1" />
		    <small>{lang}linklist.acp.category.isMainCategory.description{/lang}</small>
		</dd>
</dl>