LINKLIST = {};
LINKLIST.Link = {};

LINKLIST.Link.Like = WCF.Like.extend({
    /**
         * @see	WCF.Like._getContainers()
         */
    _getContainers: function() {
        return $('article.link');
    },
	
    /**
	 * @see	WCF.Like._getObjectID()
	 */
    _getObjectID: function(containerID) {
        return this._containers[containerID].data('linkID');
    },
	
    /**
	 * @see	WCF.Like._buildWidget()
	 */
    _buildWidget: function(containerID, likeButton, dislikeButton, badge, summary) {
        this._containers[containerID].find('.boxHeadline > hgroup> h1').append(badge);
		
        if (this._canLike) {
            likeButton.appendTo(this._containers[containerID].find('.smallButtons:eq(0)'));
            dislikeButton.appendTo(this._containers[containerID].find('.smallButtons:eq(0)'));
            dislikeButton.find('a').addClass('button small');
            likeButton.find('a').addClass('button small');
        }
    },
	
    /**
	 * @see	WCF.Like._getWidgetContainer()
	 */
    _getWidgetContainer: function(containerID) {},
	
    /**
	 * @see	WCF.Like._addWidget()
	 */
    _addWidget: function(containerID, widget) {}
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
    init: function() {
        this._super('.linklistLink');
		
        this._proxy = new WCF.Action.Proxy({
            showLoadingOverlay: false
        });
    },

    /**
	 * @see	WCF.Popover._loadContent()
	 */
    _loadContent: function () {
        var $link = $('#' + this._activeElementID);

            this._proxy.setOption('data', {
                actionName: 'getLinkPreview',
                className: 'wcf\\data\\link\\LinkAction',
                objectIDs: [$link.data('$linkID')]
            });

            var $elementID = this._activeElementID;
            var self = this;
            this._proxy.setOption('success', function (data, textStatus, jqXHR) {
                self._insertContent($elementID, data.returnValues.template, true);
            });
            this._proxy.sendRequest();
        
    
});
