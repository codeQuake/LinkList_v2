{assign var="user" value=$news->getUserProfile()}
    <div class="box128"> ><a href="{link controller='News' application='cms' object=$news}{/link}" class="framed">{@$user->getAvatarTag(128)}</a>

        <div>
            <div class="containerHeadline">
                <h3>
                    <a href="{link controller='News' object=$news application='cms'}{/link}">{$news->getTitle()}</a>
                    <small>- {@$news->time|time}</small>
                </h3>
            </div>

				<div>
				{@$nes->getExcerpt()|nl2br}
				</div>
        </div>
    </div>