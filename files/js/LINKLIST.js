LINKLIST = {};
LINKLIST.Link = {};

//like userProfileMenu
LINKLIST.Link.TabMenu = Class.extend({
    /**
	 * list of containers
	 * @var	object
	 */
    _hasContent: {},

    /**
	 * profile content
	 * @var	jQuery
	 */
    _linkContent: null,

    /**
	 * action proxy
	 * @var	WCF.Action.Proxy
	 */
    _proxy: null,

    /**
	 * target user id
	 * @var	integer
	 */
    _linkID: 0,

    /**
	 * Initializes the tab menu loader.
	 * 
	 * @param	integer		userID
	 */
    init: function (linkID) {
        this._linkContent = $('#linkContent');
        this._linkID = linkID;

        var $activeMenuItem = this._linkContent.data('active');
        var $enableProxy = false;

        // fetch content state
        this._linkContent.find('div.tabMenuContent').each($.proxy(function (index, container) {
            var $containerID = $(container).wcfIdentify();

            if ($activeMenuItem === $containerID) {
                this._hasContent[$containerID] = true;
            }
            else {
                this._hasContent[$containerID] = false;
                $enableProxy = true;
            }
        }, this));

        // enable loader if at least one container is empty
        if ($enableProxy) {
            this._proxy = new WCF.Action.Proxy({
                success: $.proxy(this._success, this)
            });

            this._linkContent.bind('wcftabsbeforeactivate', $.proxy(this._loadContent, this));
        }
    },

    /**
	 * Prepares to load content once tabs are being switched.
	 * 
	 * @param	object		event
	 * @param	object		ui
	 */
    _loadContent: function (event, ui) {
        var $panel = $(ui.newPanel);
        var $containerID = $panel.attr('id');

        if (!this._hasContent[$containerID]) {
            this._proxy.setOption('data', {
                actionName: 'getContent',
                className: 'wcf\\data\\link\\menu\\item\\LinkMenuItemAction',
                parameters: {
                    data: {
                        containerID: $containerID,
                        menuItem: $panel.data('menuItem'),
                        linkID: this._linkID
                    }
                }
            });
            this._proxy.sendRequest();
        }
    },

    /**
	 * Shows previously requested content.
	 * 
	 * @param	object		data
	 * @param	string		textStatus
	 * @param	jQuery		jqXHR
	 */
    _success: function (data, textStatus, jqXHR) {
        var $containerID = data.returnValues.containerID;
        this._hasContent[$containerID] = true;

        // insert content
        var $content = this._linkContent.find('#' + $containerID);
        $('<div>' + data.returnValues.template + '</div>').hide().appendTo($content);

        // slide in content
        $content.children('div').wcfBlindIn();
    }
});
