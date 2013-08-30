LINKLIST.Moderation = {};
LINKLIST.Moderation.Offline = {};

LINKLIST.Moderation.Offline.Management = WCF.Moderation.Management.extend({
    init: function (queueID, redirectURL) {
        this._buttonSelector = '#setOnline, #removeContent';
        this._className = 'linklist\\data\\moderation\\queue\\ModerationQueueOfflineAction';
        this._super(queueID, redirectURL, 'linklist.moderation.offline.{actionName}.confirmMessage');
    }
})