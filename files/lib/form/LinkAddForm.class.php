<?php
namespace linklist\form;

use linklist\data\category\LinklistCategory;
use linklist\data\category\LinklistCategoryNodeTree;
use linklist\data\link\LinkAction;
use linklist\data\link\LinkList;
use linklist\system\cache\builder\CategoryCacheBuilder;
use wcf\form\MessageForm;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\category\CategoryHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\UserInputException;
use wcf\system\language\LanguageFactory;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\FileUtil;
use wcf\util\HeaderUtil;
use wcf\util\StringUtil;
use wcf\util\UserUtil;

/**
 *
 * @author Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser General Public License
 * @pakage de.codequake.linklist
 */
class LinkAddForm extends MessageForm {

	public $action = 'add';

	public $templateName = 'linkAdd';

	public $categoryIDs = array();

	public $category = null;

	public $categoryList = array();

	public $url;

	public $isDisabled = 0;

	public $teaser = '';

	public $tags = array();

	public $enableMultilingualism = true;

	public $image = null;

	public $imageType = 'none';

	public $attachmentObjectType = 'de.codequake.linklist.link';

	public $enableTracking = true;

	public $objectTypeName = 'de.codequake.linklist.category';

	public function readFormParameters() {
		parent::readFormParameters();
		if (isset($_POST['tags']) && is_array($_POST['tags'])) $this->tags = ArrayUtil::trim($_POST['tags']);
		if (isset($_POST['url']) && $_POST['url'] != '') $this->url = StringUtil::trim($_POST['url']);
		if (isset($_POST['teaser'])) $this->teaser = StringUtil::trim($_POST['teaser']);
		if (isset($_REQUEST['categoryIDs']) && is_array($_REQUEST['categoryIDs'])) $this->categoryIDs = ArrayUtil::toIntegerArray($_REQUEST['categoryIDs']);
	}

	public function readData() {
		parent::readData();

		WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('linklist.pageMenu.index'), LinkHandler::getInstance()->getLink('CategoryListIndex', array(
		'application' => 'linklist'
			))));

		$excludedCategoryIDs = array_diff(LinklistCategory::getAccessibleCategoryIDs(), LinklistCategory::getAccessibleCategoryIDs(array(
			'canAddLink'
		)));
		$categoryTree = new LinklistCategoryNodeTree($this->objectTypeName, 0, false, $excludedCategoryIDs);
		$this->categoryList = $categoryTree->getIterator();

		// default values
		if (! count($_POST)) {
			$this->username = WCF::getSession()->getVar('username');

			// multilingualism
			if (! empty($this->availableContentLanguages)) {
				if (! $this->languageID) {
					$language = LanguageFactory::getInstance()->getUserLanguage();
					$this->languageID = $language->languageID;
				}

				if (! isset($this->availableContentLanguages[$this->languageID])) {
					$languageIDs = array_keys($this->availableContentLanguages);
					$this->languageID = array_shift($languageIDs);
				}
			}
		}
	}

	public function validate() {
		parent::validate();
		// categories
		if (empty($this->categoryIDs)) {
			throw new UserInputException('categoryIDs');
		}

		if ($this->languageID === null || $this->languageID == 0) $this->languageID = LanguageFactory::getInstance()->getDefaultLanguageID();

		// url
		if (! FileUtil::isURL($this->url)) {
			throw new UserInputException('url', 'illegalURL');
		}

		foreach ($this->categoryIDs as $categoryID) {
			$category = CategoryHandler::getInstance()->getCategory($categoryID);
			if ($category === null) throw new UserInputException('categoryIDs');

			$category = new LinklistCategory($category);
			if (! $category->isAccessible() || ! $category->getPermission('canAddLink')) throw new UserInputException('categoryIDs');
			if (!$category->getPermission('canAddActiveLink')) $this->isDisabled = 1;
		}
	}

	public function save() {
		parent::save();
		if ($this->languageID === null) {
			$this->languageID = LanguageFactory::getInstance()->getDefaultLanguageID();
		}
		$data = array(
			'languageID' => $this->languageID,
			'subject' => $this->subject,
			'time' => TIME_NOW,
			'teaser' => $this->teaser,
			'message' => $this->text,
			'url' => $this->url,
			'userID' => WCF::getUser()->userID,
			'username' => WCF::getUser()->username,
			'isDisabled' => 0,
			'enableBBCodes' => $this->enableBBCodes,
			'enableHtml' => $this->enableHtml,
			'enableSmilies' => $this->enableSmilies,
			'lastChangeTime' => TIME_NOW
		);
		$linkData = array(
			'data' => $data,
			'tags' => array(),
			'attachmentHandler' => $this->attachmentHandler,
			'categoryIDs' => $this->categoryIDs
		);
		$linkData['tags'] = $this->tags;

		$action = new LinkAction(array(), 'create', $linkData);
		$resultValues = $action->executeAction();

		$this->saved();

		HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Link', array(
		'application' => 'linklist',
		'object' => $resultValues['returnValues']
		)));
		exit();
	}

	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
		'categoryList' => $this->categoryList,
		'categoryIDs' => $this->categoryIDs,
		'url' => $this->url,
		'teaser' => $this->teaser,
		'action' => $this->action,
		'tags' => $this->tags,
		'allowedFileExtensions' => explode("\n", StringUtil::unifyNewlines(WCF::getSession()->getPermission('user.cms.news.allowedAttachmentExtensions')))
		));
	}
}
