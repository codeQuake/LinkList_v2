<article class="message messageReduced">
	<div>
		<section class="messageContent">
			<div>
				<header class="messageHeader">
					<div class="box32">
						{if $link->getUserProfile()->userID}
							<a href="{link controller='User' object=$link->getUserProfile()->getDecoratedObject()}{/link}" class="framed">{@$link->getUserProfile()->getAvatar()->getImageTag(32)}</a>
						{else}
							<span class="framed">{@$link->getUserProfile()->getAvatar()->getImageTag(32)}</span>
						{/if}
						
						<div class="messageHeadline">
							<h1><a href="{@$link->getLink()}">{$link->getTitle()}</a></h1>
							<p>
								<span class="username">{if $link->getUserProfile()->userID}<a href="{link controller='User' object=$link->getUserProfile()->getDecoratedObject()}{/link}">{$link->getUsername()}</a>{else}{$link->getUsername()}{/if}</span>
								{@$link->getTime()|time}
							</p>
						</div>
					</div>
				</header>
				
				<div class="messageBody">
					<div>
						<div class="messageText">
							{@$link->getFormattedMessage()}
						</div>
					</div>
					
					
				</div>
			</div>
		</section>
	</div>
</article>
