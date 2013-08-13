{include file='documentHeader'}

<head>
    <title>{lang}linklist.link.link{$action|ucfirst}{/lang} - {PAGE_TITLE|language}</title>
    {include file='headInclude'}
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Message.Submit.registerButton('text', $('#messageContainer > .formSubmit > input[type=submit]'));
			new WCF.Message.FormGuard();
		});
		//]]>
	</script>
</head>
<body id="tpl{$templateName|ucfirst}">
    {include file='header'}
    <header class="boxHeadline">
        <div>
            <h1>{lang}linklist.link.link{$action|ucfirst}{/lang}</h1>
        </div>
    </header>

    {if $errorField}
        <p class="error">{lang}wcf.global.form.error{/lang}</p>
    {/if}

    <form id="messageContainer" class="jsFormGuard" method="post" action="{if $action=='add'}{link controller='LinkAdd' application='linklist'}{/link}{else}{link controller='LinkEdit' object=$link application='linklist'}{/link}{/if}" id="link{$action|ucfirst}Form" enctype="multipart/form-data">
        {if $linkID|isset}<input type="hidden" name="linkID" value="{$linkID}" />{/if}
        <div class="container containerPadding marginTop shadow">
            <fieldset>
                <legend>{lang}linklist.link.link{$action|ucfirst}.data{/lang}</legend>
                <!--username-->
                {if !$__wcf->getUser()->userID}
                 <dl>
                    <dt>
                        <label for="username">
                            {lang}linklist.link.link{$action|ucfirst}.username{/lang}
                        </label>
                    </dt>
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
                <!--category-->
                {if $action == 'add'}
                    <dl>
                        <dt>
                            <label for="category">{lang}linklist.link.link{$action|ucfirst}.category{/lang}</label>
                        </dt>
                        <dd>
                            <select id="category" name="category" required="required">
                                {foreach from=$categoryNodeList item=category}
                                <!-- TODO: add acl--> 
                                    <option value="{$category->categoryID}"{if $categoryID|isset && $categoryID == $category->categoryID} selected="selected"{/if}>{section name=i loop=$categoryNodeList->getDepth()}&nbsp;&raquo;&raquo;&nbsp;{/section}{$category->getTitle()}</option>
                                {/foreach}
                            </select>
                        </dd>
                    </dl>
                {/if}

                <!--title-->
                <dl>
                    <dt>
                        <label for="subject">{lang}linklist.link.link{$action|ucfirst}.title{/lang}</label>
                    </dt>
                    <dd>
                        <input type="text" id="subject" name="subject" value="{if $subject|isset}{$subject}{/if}" required="required" class="medium"/>
                        {if $errorField == 'subject'}
                            <small class="innerError">
                            {if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
                            </small>
                        {/if}
                    </dd>
                </dl>

                <!--url-->
                <dl>
                    <dt>
                        <label for="url">{lang}linklist.link.link{$action|ucfirst}.url{/lang}</label>
                    </dt>
                    <dd>
                        <input type="text" id="url" name="url" value="{if $url|isset}{$url}{/if}" required="required" class="medium"/>
                        {if $errorField == 'url'}
                            <small class="innerError">
                            {if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
                            </small>
                        {/if}
                    </dd>
                </dl>
				<!--image-->
				{if LINKLIST_ENABLE_OWN_PREVIEW && $__wcf->getSession()->getPermission('user.linklist.link.canAddOwnPreview')}
				<dl>
					<dt></dt>
					<dd>
					<label><input type="radio" name="imageType" value="none" {if $imageType == 'none'}checked="checked" {/if}/> {lang}linklist.link.image.screenshotService{/lang}</label>
					<small>{lang}linklist.link.image.screenshotService.description{/lang}</small>
					</dd>
				</dl>
				<dl>
					<dt></dt>
					<dd>
					<label><input type="radio" name="imageType" value="upload" {if $imageType == 'upload'}checked="checked"{/if} />{lang}linklist.link.image.upload{/lang}</label>
					<small><input type="file" name="image" /></small>
					</dd>
				</dl>
				<dl>
					<dt></dt>
					<dd>
					<label><input type="radio" name="imageType" value="link" {if $imageType == 'link'}checked="checked"{/if} />{lang}linklist.link.image.link{/lang}</label>
					<small>
						{lang}linklist.link.image.link.description{/lang}<br/>
						<input type="text" name="image" {if$imageType == 'link'}value="{$image}"{/if} class="medium" />
						
					</small>
					</dd>
				</dl>
				{if $errorField == 'image'}
                            <small class="innerError">{lang}wcf.global.form.error.$errorType{/lang}
                            </small>
                        {/if}
				{/if}
                {if $action == 'add'}
                 {include file='messageFormMultilingualism'}
                {/if}
				{if MODULE_TAGGING && LINKLIST_ENABLE_TAGS}{include file='tagInput'}{/if}
            </fieldset>

             <fieldset>
                <legend>
                     {lang}linklist.link.link{$action|ucfirst}.text{/lang}
                </legend>
            
                <dd>
                    <textarea id="text" name="text" rows="20" cols="40">{if $text|isset}{$text}{/if}</textarea>
                    {if $errorField == 'text'}
                        <small class="innerError">{if $errorType == 'empty'}{lang}wcf.global.form.error.empty{/lang}{/if}
                        </small>
                    {/if}
                </dd>
            
            </fieldset>
			{if $useCaptcha}{include file='recaptcha'}{/if}
            {include file='messageFormTabs' wysiwygContainerID='text' attachmentHandler=null}
             <div class="formSubmit">
                 <input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
            </div>
        </div>
    </form>
    {include file='footer'}
    {include file='wysiwyg'}
</body>
</html>