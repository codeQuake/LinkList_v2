<form id="sidebarContainer" method="get" action="{link application='linklist' controller='Category' object=$category}{/link}">
	<fieldset>
		<legend>{lang}linklist.category.displayOptions{/lang}</legend>

		<dl>
			<dt><label for="sortField">{lang}linklist.category.sortBy{/lang}</label></dt>
			<dd>
				<select id="sortField" name="sortField">
					<option value="subject" {if $sortField == 'subject'} selected="selected"{/if}>{lang}linklist.link.title{/lang}</option>
					<option value="time" {if $sortField == 'time'} selected="selected"{/if}>{lang}linklist.link.time{/lang}</option>
					<option value="visits" {if $sortField =='visits'} selected="selected"{/if}>{lang}linklist.link.visits{/lang}</option>
				</select>

				<select name="sortOrder">
					<option value="ASC"{if $sortOrder == 'ASC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.ascending{/lang}</option>
					<option value="DESC"{if $sortOrder == 'DESC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.descending{/lang}</option>
				</select>
			</dd>
		</dl>
	</fieldset>

	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
	</div>
</form>
