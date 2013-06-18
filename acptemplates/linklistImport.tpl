{include file='header'}
    
<header class="boxHeadline">
    <h1>{lang}linklist.acp.import{/lang}</h1>
</header>

{if $errorField}
    <p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" enctype="multipart/form-data" action="{link controller='LinklistImport' application='linklist'}{/link}">
    <div class="container containerPadding marginTop">
        <fieldset>
            <legend>{lang}linklist.acp.import.sourcefile{/lang}</legend>
            <dl{if $errorField == 'fileUpload'} class="formError"{/if}>
                <dt><label for="fileUpload">{lang}linklist.acp.import.sourcefile{/lang}</label></dt>
                <dd>
                    <input type="file" name="fileUpload" id="fileUpload"  required="required"/>
                    {if $errorField == 'fileUpload'}
                        <small class="innerError">
                              {lang}linklist.acp.import.error.{$errorType}{/lang}
                        </small>
                    {/if}
                </dd>
            </dl>
        </fieldset>
    </div>

    <div class="formSubmit">
        <input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
    </div>
</form>

{include file='footer'}