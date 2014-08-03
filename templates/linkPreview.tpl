{assign var="user" value=$link->getUserProfile()}
	<div class="box128">
	<div style="height: 128px; width: 128px;"><a href="{link controller='Link' application='linklist' object=$link application='linklist'}{/link}" class="framed">{@$link->getImage(128)}</a></div>


		<div>
			<div class="containerHeadline">
				<h3>
					<a href="{link controller='Link' object=$link application='linklist'}{/link}">{$link->getTitle()}</a>
					<small>- {@$link->time|time}</small>

				</h3>
			</div>

				<div>
				{@$link->getExcerpt()|nl2br}
				</div>
		</div>
	</div>
