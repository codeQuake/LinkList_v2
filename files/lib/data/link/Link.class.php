<?php
namespace linklist\data\link;

use linklist\data\category\LinklistCategory;
use linklist\data\LINKLISTDatabaseObject;
use wcf\data\attachment\Attachment;
use wcf\data\attachment\GroupedAttachmentList;
use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\data\IMessage;
use wcf\system\bbcode\AttachmentBBCode;
use wcf\system\bbcode\MessageParser;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\breadcrumb\IBreadcrumbProvider;
use wcf\system\category\CategoryHandler;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\language\LanguageFactory;
use wcf\system\request\IRouteController;
use wcf\system\tagging\TagEngine;
use wcf\system\WCF;
use wcf\util\StringUtil;
use wcf\util\UserUtil;

/**
 *
 * @author Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
class Link extends LINKLISTDatabaseObject implements IMessage, IRouteController, IBreadcrumbProvider {

	protected static $databaseTableName = 'link';

	protected static $databaseTableIndexName = 'linkID';

	protected $categories = null;

	protected $categoryIDs = array();

	public $userProfile = null;

	public function __construct($id, $row = null, $object = null) {
		if ($id !== null) {
			$sql = "SELECT *
					FROM " . static::getDatabaseTableName() . "
					WHERE (" . static::getDatabaseTableIndexName() . " = ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array(
				$id
			));
			$row = $statement->fetchArray();

			if ($row === false) $row = array();
		}

		parent::__construct(null, $row, $object);
	}

	public function getThumb($size = 250) {
		if (LINKLIST_THUMBALIZR) return "https://api.thumbalizr.com/?url=".$this->url."&width=".$size."&api_key=".LINKLIST_THUMBALIZR_APIKEY."&qualitiy=100";
		return "http://api.webthumbnail.org?width=".$size."&height=".$size."&screen=1024&url=".$this->url;
	}

	public function getTitle() {
		return $this->subject;
	}

	public function getMessage() {
		return $this->message;
	}

	public function getTags() {
		$tags = TagEngine::getInstance()->getObjectTags('de.codequake.linklist.link', $this->linkID);
		return $tags;
	}

	public function getFormattedMessage() {
		AttachmentBBCode::setObjectID($this->linkID);

		MessageParser::getInstance()->setOutputType('text/html');
		return MessageParser::getInstance()->parse($this->getMessage(), $this->enableSmilies, $this->enableHtml, $this->enableBBCodes);
	}

	public function getAttachments() {
		if (MODULE_ATTACHMENT == 1 && $this->attachments) {
			$attachmentList = new GroupedAttachmentList('de.codequake.linklist.link');
			$attachmentList->getConditionBuilder()->add('attachment.objectID IN (?)', array(
				$this->linkID
			));
			$attachmentList->readObjects();
			$attachmentList->setPermissions(array(
				'canDownload' => WCF::getSession()->getPermission('user.linklist.link.canDownloadAttachments'),
				'canViewPreview' => WCF::getSession()->getPermission('user.linklist.link.canDownloadAttachments')
			));

			AttachmentBBCode::setAttachmentList($attachmentList);
			return $attachmentList;
		}
		return null;
	}

	public function getExcerpt($maxLength = 500) {
		return StringUtil::truncateHTML($this->getFormattedMessage(), $maxLength);
	}

	public function getUserID() {
		return $this->userID;
	}

	public function getUsername() {
		return $this->username;
	}

	public function getTime() {
		return $this->time;
	}

	public function getUserProfile() {

		if ($this->userProfile === null) {
			$this->userProfile = new UserProfile(new User($this->userID));
		}
			return $this->userProfile;
	}

	public function getLink() {
		return LinkHandler::getInstance()->getLink('Link', array(
			'application' => 'linklist',
			'object' => $this,
			'forceFrontend' => true
		));
	}
	public function __toString() {
		return $this->getFormattedMessage();
	}

	public function getBreadcrumb() {
		return new Breadcrumb($this->subject, $this->getLink());
	}

	public function getCategoryIDs() {
		return $this->categoryIDs;
	}

	public function setCategoryID($categoryID) {
		$this->categoryIDs[] = $categoryID;
	}

	public function setCategoryIDs(array $categoryIDs) {
		$this->categoryIDs = $categoryIDs;
	}

	public function getCategories() {
		if ($this->categories === null) {
			$this->categories = array();

			if (! empty($this->categoryIDs)) {
				foreach ($this->categoryIDs as $categoryID) {
					$this->categories[$categoryID] = new LinklistCategory(CategoryHandler::getInstance()->getCategory($categoryID));
				}
			} else {
				$sql = "SELECT	categoryID
					FROM	linklist" . WCF_N . "_link_to_category
					WHERE	linkID = ?";
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute(array(
					$this->linkID
				));
				while ($row = $statement->fetchArray()) {
					$this->categories[$row['categoryID']] = new LinklistCategory(CategoryHandler::getInstance()->getCategory($row['categoryID']));
				}
			}
		}

		return $this->categories;
	}

	public function getIpAddress() {
		if ($this->ipAddress) {
			return UserUtil::convertIPv6To4($this->ipAddress);
		}

		return '';
	}

	public function isVisible() {
		return true;
	}

	public function canRead() {
		return WCF::getSession()->getPermission('user.linklist.link.canViewCategory');
	}

	public function canAdd() {
		return WCF::getSession()->getPermission('user.linklist.link.canAddLink');
	}

	public function canModerate() {
		return WCF::getSession()->getPermission('mod.linklist.link.canModerateLink');
	}

	public function canDelete() {
		return WCF::getSession()->getPermission('mod.linklist.link.canDeleteLink');
	}


	public function getImage($size) {
		if ($this->image == '') return '<img src="'.$this->getThumb($size).'" alt="" />';
		return '';
	}

	public static function getIpAddressByAuthor($userID, $username = '', $notIpAddress = '', $limit = 10) {
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("userID = ?", array(
			$userID
		));
		if (! empty($username) && ! $userID) $conditions->add("username = ?", array(
			$username
		));
		if (! empty($notIpAddress)) $conditions->add("ipAddress <> ?", array(
			$notIpAddress
		));
		$conditions->add("ipAddress <> ''");

		$sql = "SELECT		DISTINCT ipAddress
			FROM		linklist" . WCF_N . "_link
			" . $conditions . "
			ORDER BY	time DESC";
		$statement = WCF::getDB()->prepareStatement($sql, $limit);
		$statement->execute($conditions->getParameters());

		$ipAddresses = array();
		while ($row = $statement->fetchArray()) {
			$ipAddresses[] = $row['ipAddress'];
		}

		return $ipAddresses;
	}

	public static function getAuthorByIpAddress($ipAddress, $notUserID = 0, $notUsername = '', $limit = 10) {
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("ipAddress = ?", array(
			$ipAddress
		));
		if ($notUserID) $conditions->add("userID <> ?", array(
			$notUserID
		));
		if (! empty($notUsername)) $conditions->add("username <> ?", array(
			$notUsername
		));

		$sql = "SELECT		DISTINCT username, userID
			FROM		linklist" . WCF_N . "_link
			" . $conditions . "
			ORDER BY	time DESC";
		$statement = WCF::getDB()->prepareStatement($sql, $limit);
		$statement->execute($conditions->getParameters());

		$users = array();
		while ($row = $statement->fetchArray()) {
			$users[] = $row;
		}

		return $users;
	}
}
