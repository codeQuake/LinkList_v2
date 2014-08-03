{include file='documentHeader'}

<head>
	<title>{lang}linklist.link.link{$action|ucfirst}{/lang} - {PAGE_TITLE|language}</title>

	{include file='headInclude'}
	<script data-relocate="true">
		//<![CDATA[
		$(function() {
			new WCF.Category.NestedList();
			WCF.Message.Submit.registerButton('text', $('#messageContainer > .formSubmit > input[type=submit]'));
			new WCF.Message.FormGuard();
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header'}

<header class="boxHeadline">
	<h1>{lang}linklist.link.link{$action|ucfirst}{/lang}</h1>
</header>

{include file='userNotice'}

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{@$action}{/lang}</p>
{/if}

<form id="messageContainer" class="jsFormGuard" method="post" action="{if $action=='add'}{link controller='LinkAdd' application='linklist'}{/link}{else}{link controller='LinkEdit' object=$link application='linklist'}{/link}{/if}">
	{if $linkID|isset}<input type="hidden" name="linkID" value="{$linkID}" />{/if}
	<div class="container containerPadding marginTop shadow">
		<fieldset>
			<legend>{lang}linklist.link.category.categories{/lang}</legend>
			<small>{lang}linklist.link.category.categories.description{/lang}</small>

			<ol class="nestedCategoryList doubleColumned jsCategoryList">
				{foreach from=$categoryList item=categoryItem}
					{if $categoryItem->isAccessible()}
						<li>
							<div>
								<div class="containerHeadline">
									<h3><label{if $categoryItem->getDescription()} class="jsTooltip" title="{$categoryItem->getDescription()}"{/if}>{if !$categoryItem->isMainCategory()}<input type="checkbox" name="categoryIDs[]" value="{@$categoryItem->categoryID}" class="jsCategory"{if $categoryItem->categoryID|in_array:$categoryIDs}checked="checked" {/if}/>{else}<span title="{lang}linklist.link.category.main{/lang}" class="jsTooltip icon icon16 icon-folder-close"></span>{/if} {$categoryItem->getTitle()}</label></h3>
								</div>

								{if $categoryItem->hasChildren()}
									<ol>
										{foreach from=$categoryItem item=subCategoryItem}
											{if $subCategoryItem->isAccessible()}
												<li>
													<label{if $subCategoryItem->getDescription()} class="jsTooltip" title="{$subCategoryItem->getDescription()}"{/if}>{if !$subCategoryItem->isMainCategory()}<input type="checkbox" name="categoryIDs[]" value="{@$subCategoryItem->categoryID}" class="jsChildCategory"{if $subCategoryItem->categoryID|in_array:$categoryIDs}checked="checked" {/if}/>{else}<span title="{lang}linklist.link.category.main{/lang}" class="jsTooltip icon icon16 icon-folder-close"></span>{/if} {$subCategoryItem->getTitle()}</label>
												</li>
											{/if}
										{/foreach}
									</ol>
								{/if}
							</div>
						</li>
					{/if}
				{/foreach}
			</ol>

			{if $errorField == 'categoryIDs'}
				<small class="innerError">
					{if $errorType == 'empty'}
						{lang}wcf.global.form.error.empty{/lang}
					{else}
						{lang}linklist.link.categories.error.{@$errorType}{/lang}
					{/if}
				</small>
			{/if}

			{event name='categoryFields'}
		</fieldset>

		<fieldset>
			<legend>{lang}linklist.link.link{$action|ucfirst}.data{/lang}</legend>

			{if !$__wcf->getUser()->userID}
				<dl>
					<dt><label for="username">{lang}linklist.link.link{$action|ucfirst}.username{/lang}</label></dt>
					<dd>
						<input type="text" id="username" name="username" value="{if $username|isset}{$username}{/if}" required="required" class="medium" />
						{if $errorField == 'username'}
							<small class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
								{if $errorType == 'notFound'}{lang}wcf.user.error.username.notFound{/lang}{/if}
							</small>
						{/if}
					</dd>
				</dl>
			{/if}

			<dl>
				<dt><label for="subject">{lang}linklist.link.link{$action|ucfirst}.title{/lang}</label></dt>
				<dd>
					<input type="text" id="subject" name="subject" value="{if $subject|isset}{$subject}{/if}" required="required" class="medium"/>
					{if $errorField == 'subject'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>

			<dl>
				<dt><label for="url">{lang}linklist.link.link{$action|ucfirst}.url{/lang}</label></dt>
				<dd>
					<input type="text" id="url" name="url" value="{if $url|isset}{$url}{/if}" required="required" class="medium"/>
					{if $errorField == 'url'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}linklist.link.url.{$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>

			<dl{if $errorField == 'teaser'} class="formError{/if}">
				<dt><label for="teaser">{lang}linklist.link.teaser{/lang}</label></dt>
				<dd>
					<textarea id="teaser" name="teaser" rows="5" cols="40">{$teaser}</textarea>
					{if $errorField == 'teaser'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}linklist.link.teaser.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
					<small>{lang}linklist.link.teaser.description{/lang}</small>
				</dd>
			</dl>

			{if $action == 'add'}
				{include file='messageFormMultilingualism'}
			{/if}
			
			{if MODULE_TAGGING && LINKLIST_ENABLE_TAGS}{include file='tagInput'}{/if}

			{event name='dataFields'}
		</fieldset>

		<fieldset>
			<legend>{lang}linklist.link.link{$action|ucfirst}.text{/lang}</legend>

			<dd>
				<textarea id="text" name="text" rows="20" cols="40">{if $text|isset}{$text}{/if}</textarea>
				{if $errorField == 'text'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
							{lang}linklist.link.text.error.{@$errorType}{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</fieldset>

		{if $useCaptcha}{include file='recaptcha'}{/if}
		{include file='messageFormTabs' wysiwygContainerID='text'}

		<div class="formSubmit">
			<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
			{@SECURITY_TOKEN_INPUT_TAG}
			{include file='messageFormPreviewButton'}
		</div>
	</div>
</form>

{include file='footer'}
{include file='wysiwyg'}

</body>
</html>
