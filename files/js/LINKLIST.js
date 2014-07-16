LINKLIST = {};
LINKLIST.Link = {};

LINKLIST.Link.MarkAllAsRead = Class.extend({
	_proxy: null,

	init: function () {
		// initialize proxy
		this._proxy = new WCF.Action.Proxy({
			success: $.proxy(this._success, this)
		});
		//add clickhandler
		$('.markAllAsReadButton').click($.proxy(this._click, this));
	},

	_click: function () {
		this._proxy.setOption('data', {
			actionName: 'markAllAsRead',
			className: 'linklist\\data\\link\\LinkAction'
		});

		this._proxy.sendRequest();
	},

	_success: function (data, textStatus, jqXHR) {
		//hide unread messages
		$('#mainMenu .active .badge').hide();
		$('.newMessageBadge').hide();
		$('.linklistCategory > div > .containerHeadline .badge').hide();
	}
});

LINKLIST.Link.Like = WCF.Like.extend({

	_getContainers: function () {
		return $('article.message');
	},

	_getObjectID: function (containerID) {
		return this._containers[containerID].data('linkID');
	},

	_buildWidget: function (containerID, likeButton, dislikeButton, badge, summary) {
		var $widgetContainer = this._getWidgetContainer(containerID);
		if (this._canLike) {
			var $smallButtons = this._containers[containerID].find('.smallButtons');
			likeButton.insertBefore($smallButtons.find('.toTopLink'));
			dislikeButton.insertBefore($smallButtons.find('.toTopLink'));
			dislikeButton.find('a').addClass('button');
			likeButton.find('a').addClass('button');
		}

		if (summary) {
			summary.appendTo(this._containers[containerID].find('.messageBody > .messageFooter'));
			summary.addClass('messageFooterNote');
		}
		$widgetContainer.find('.permalink').after(badge);
	},


	_getWidgetContainer: function (containerID) {
		return this._containers[containerID].find('.messageHeader');
	},

	_addWidget: function (containerID, widget) { },

	_setActiveState: function(likeButton, dislikeButton, likeStatus) {
	likeButton = likeButton.find('.button').removeClass('active');
	dislikeButton = dislikeButton.find('.button').removeClass('active');

	if (likeStatus == 1) {
		likeButton.addClass('active');
	}
	else if (likeStatus == -1) {
		dislikeButton.addClass('active');
	}
}
});

LINKLIST.Link.Preview = WCF.Popover.extend({
	/**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
	_proxy: null,

	/**
	 * list of links
	 * @var	object
	 */
	_links: {},

	/**
	 * @see	WCF.Popover.init()
	 */
	init: function () {
		this._super('.linklistLink');

		this._proxy = new WCF.Action.Proxy({
			showLoadingOverlay: false
		});
		WCF.DOMNodeInsertedHandler.addCallback('LINKLIST.Link.Preview', $.proxy(this._initContainers, this));
	},

	/**
	 * @see	WCF.Popover._loadContent()
	 */
	_loadContent: function () {
		var $link = $('#' + this._activeElementID);

		this._proxy.setOption('data', {
			actionName: 'getLinkPreview',
			className: 'linklist\\data\\link\\LinkAction',
			objectIDs: [$link.data('linkID')]
		});

		var $elementID = this._activeElementID;
		var self = this;
		this._proxy.setOption('success', function (data, textStatus, jqXHR) {
			self._insertContent($elementID, data.returnValues.template, true);
		});
		this._proxy.sendRequest();


	}
});
