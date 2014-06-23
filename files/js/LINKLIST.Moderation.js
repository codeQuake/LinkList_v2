LINKLIST.Moderation = {};
LINKLIST.Moderation.Offline = {};

LINKLIST.Moderation.Offline.Management = WCF.Moderation.Management.extend({
	init: function (queueID, redirectURL) {
		this._buttonSelector = '#setOnline, #removeContent';
		this._className = 'linklist\\data\\moderation\\queue\\ModerationQueueOfflineAction';
		this._super(queueID, redirectURL, 'linklist.moderation.offline.{actionName}.confirmMessage');
		this._confirmationTemplate.removeContent = $('<fieldset><dl><dt><label for="message">' + WCF.Language.get('linklist.moderation.offline.removeContent.reason') + '</label></dt><dd><textarea name="message" id="message" cols="40" rows="3" /></dd></dl></fieldset>');
	}
});
