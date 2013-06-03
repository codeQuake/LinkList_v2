
			<article id="wcf{$link->linkID}" class="message dividers marginTop">
				<div>
					<section class="messageContent">
						<div>
							<div class="messageBody">
								<div class="messageText" style="border: none;">
									<div>
										{@$link->getFormattedMessage()}
									</div>
								</div>
								<footer class="messageOptions contentOptions marginTop clearfix">
									<div>
										<a class="button" href="{link application='linklist' controller='LinkVisit' object=$link}{/link}"><h1>{lang}linklist.link.visit{/lang}</h1></a>
										<a class="button" href="{link application='linklist' controller='LinkEdit' object=$link}{/link}"><h1>{lang}linklist.link.edit{/lang}</h1></a>
									</div>
								</footer>
							</div>
						</div>
					</section>
				</div>
			</article>
