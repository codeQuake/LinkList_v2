{include file='documentHeader'}

<head>
    <title>{lang}linklist.moderation.offline{/lang} - {PAGE_TITLE|language}</title>
    {include file='headInclude'}
    <script data-relocate="true" type="text/javascript" src="{@$__wcf->getPath()}js/WCF.Moderation.js?v={@$__wcfVersion}"></script>
    <script data-relocate="true" type="text/javascript" src="{@$__wcf->getPath('linklist')}js/LINKLIST.Moderation.js?v={@$__wcfVersion}"></script>
    <script data-relocate="true">
        //<![CDATA[
        $(function() {
            new LINKLIST.Moderation.Offline.Management({@$queue->queueID}, '{link controller='ModerationList'}{/link}');
            WCF.Language.addObject({
                'linklist.moderation.offline.setOnline.confirmMessage': '{lang}linklist.moderation.offline.setOnline.confirmMessage{/lang}',
                'linklist.moderation.offline.removeContent.confirmMessage': '{lang}linklist.moderation.offline.removeContent.confirmMessage{/lang}'
            });
        });
        //]]>
</script>
</head>
<body id="tpl{$templateName|ucfirst}">

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline">
    <h1>{lang}linklist.moderation.offline{/lang}</h1>
</header>

<div class="contentNavigation">
    <nav>
        <ul>
            <li><a href="{link controller='ModerationList'}{/link}" class="button"><span class="icon icon16 icon-list"></span> <span>{lang}wcf.moderation.moderation{/lang}</span></a></li>
            {event name='contentNavigationButtonsTop'}
        </ul>
    </nav>
</div>
<form method="post" action="{link controller='ModerationOffline' id=$queue->queueID  application='linklist'}{/link}" class="container containerPadding marginTop">
    <fieldset>
        <legend>{lang}linklist.moderation.offline.details{/lang}</legend>
        <dl>
            <dt>{lang}wcf.global.objectID{/lang}</dt>
            <dd>{#$queue->queueID}</dd>
        </dl>
        {if $queue->lastChangeTime}
        <dl>
            <dt>{lang}wcf.moderation.lastChangeTime{/lang}</dt>
            <dd>{@$queue->lastChangeTime|time}</dd>
        </dl>
        {/if}

        <dl>
            <dt>{lang}wcf.moderation.assignedUser{/lang}</dt>
            <dd>
                <ul>
                    {if $assignedUserID && ($assignedUserID != $__wcf->getUser()->userID)}
                    <li><label><input type="radio" name="assignedUserID" value="{@$assignedUserID}" checked="checked" /> {$queue->assignedUsername}</label></li>
                    {/if}
                    <li><label><input type="radio" name="assignedUserID" value="{@$__wcf->getUser()->userID}"{if $assignedUserID == $__wcf->getUser()->userID} checked="checked"{/if} /> {$__wcf->getUser()->username}</label></li>
                    <li><label><input type="radio" name="assignedUserID" value="0"{if !$assignedUserID} checked="checked"{/if} /> {lang}wcf.moderation.assignedUser.nobody{/lang}</label></li>
                </ul>
            </dd>   
        </dl>

        {if $queue->assignedUser}
        <dl>
            <dt></dt>
            <dd><a href="{link controller='User' id=$assignedUserID}{/link}" class="userLink" data-user-id="{@$assignedUserID}">{$queue->assignedUsername}</a></dd>
        </dl>
        {/if}

        <dl>
            <dt><label for="comment">{lang}wcf.moderation.comment{/lang}</label></dt>
            <dd><textarea id="comment" name="comment" rows="4" cols="40">{$comment}</textarea></dd>
        </dl>
        {event name='detailsFields'}

        <div class="formSubmit">
            <input type="submit" value="{lang}wcf.global.button.submit{/lang}" />
        </div>
    </fieldset>
</form>
<header class="boxHeadline boxSubHeadline">
    <h2>{lang}linklist.moderation.offline.content{/lang}</h2>
</header>
<div class="marginTop">
    {@$offlineContent}
</div>


<div class="contentNavigation">
    <nav>
        <ul>
            <li class="jsOnly"><button id="setOnline">{lang}linklist.moderation.offline.setOnline{/lang}</button></li>
            <li class="jsOnly"><button id="removeContent">{lang}linklist.moderation.offline.removeContent{/lang}</button></li>
            
            <li><a href="{link controller='ModerationList'}{/link}" class="button"><span class="icon icon16 icon-list"></span> <span>{lang}wcf.moderation.moderation{/lang}</span></a></li>
            {event name='contentNavigationButtonsBottom'}
        </ul>
    </nav>
</div>

{include file='footer'}

</body>
</html>