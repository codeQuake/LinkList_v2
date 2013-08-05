{assign var="user" value=$link->getUserProfile()}
    <div class="box128"> 
	<a href="{link controller='Link' application='linklist' object=$link}{/link}" class="framed"><img src="http://api.webthumbnail.org?width=128&height=128&screen=1280&format=png&url={$link->url}" alt="Captured by webthumbnail.org" /></a> 

        <div>
            <div class="containerHeadline">
                <h3>
                    <a href="{link controller='Link' object=$link}{/link}">{$link->getTitle()}</a>
                    <small>- {@$link->time|time}</small>
                </h3>
            </div>

				<div>
				{@$link->getExcerpt()|nl2br}
				</div>
        </div>
    </div>