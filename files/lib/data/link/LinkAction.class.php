<?php
namespace linklist\data\link;

use linklist\system\log\modification\LinkModificationLogHandler;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\IClipboardAction;
use wcf\system\attachment\AttachmentHandler;
use wcf\system\clipboard\ClipboardHandler;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\language\LanguageFactory;
use wcf\system\moderation\queue\ModerationQueueActivationManager;
use wcf\system\search\SearchIndexManager;
use wcf\system\tagging\TagEngine;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\system\user\activity\point\UserActivityPointHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\visitTracker\VisitTracker;
use wcf\system\WCF;
use wcf\util\UserUtil;

/**
 *
 * @author Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
class LinkAction extends AbstractDatabaseObjectAction implements IClipboardAction {

	protected $className = 'linklist\data\link\LinkEditor';

	public $link;

	protected $permissionsCreate = array(
		'user.linklist.link.canAddLink'
	);

	protected $permissionsDelete = array(
		'mod.linklist.link.canDeleteLink'
	);

	protected $permissionsTrash = array(
		'mod.linklist.link.canTrashLink'
	);

	protected $permissionsEnable = array(
		'mod.linklist.link.canToggleLink'
	);

	protected $permissionsDisable = array(
		'mod.linklist.link.canToggleLink'
	);

	protected $allowGuestAccess = array(
		'getLinkPreview',
		'markAllAsRead'
	);

	public $links = array();

	public function create() {
		// count attachments
		if (isset($this->parameters['attachmentHandler']) && $this->parameters['attachmentHandler'] !== null) {
			$this->parameters['data']['attachments'] = count($this->parameters['attachmentHandler']);
		}

		if (LOG_IP_ADDRESS) {
			// add ip address
			if (! isset($data['ipAddress'])) {
				$data['ipAddress'] = WCF::getSession()->ipAddress;
			}
		} else {
			// do not track ip address
			if (isset($data['ipAddress'])) {
				unset($data['ipAddress']);
			}
		}
		$object = call_user_func(array(
			$this->className,
			'create'
		), $this->parameters['data']);

		// update attachments
		if (isset($this->parameters['attachmentHandler']) && $this->parameters['attachmentHandler'] !== null) {
			$this->parameters['attachmentHandler']->updateObjectID($object->linkID);
		}

		// handle categories
		$editor = new LinkEditor($object);
		// langID != 0
		$languageID = (! isset($this->parameters['data']['languageID']) || ($this->parameters['data']['languageID'] === null)) ? LanguageFactory::getInstance()->getDefaultLanguageID() : $this->parameters['data']['languageID'];
		$editor->update(array(
			'languageID' => $languageID
		));
		$editor->updateCategoryIDs($this->parameters['categoryIDs']);
		$editor->setCategoryIDs($this->parameters['categoryIDs']);

		if (! empty($this->parameters['tags'])) {
			TagEngine::getInstance()->addObjectTags('de.codequake.linklist.link', $object->linkID, $this->parameters['tags'], $this->parameters['data']['languageID']);
		}
		// reset storage
		UserStorageHandler::getInstance()->resetAll('linklistUnreadLinks');

		if (!$object->isDisabled) {
			if ($object->userID !== null) {
				UserActivityEventHandler::getInstance()->fireEvent('de.codequake.linklist.link.recentActivityEvent', $object->linkID, $object->languageID, $object->userID, $object->time);
				UserActivityPointHandler::getInstance()->fireEvent('de.codequake.linklist.activityPointEvent.link', $object->linkID, $object->userID);
			}
			SearchIndexManager::getInstance()->add('de.codequake.linklist.link', $object->linkID, $object->message, $object->subject, $object->time, $object->userID, $object->username, $object->languageID);
		}
		return $object;
	}

	public function publish() {
		foreach ($this->objects as $link) {
			$link->update(array(
				'isDisabled' => 0
			));
			// recent
			UserActivityEventHandler::getInstance()->fireEvent('de.codequake.linklist.link.recentActivityEvent', $link->linkID, $link->languageID, $link->userID, $link->time);
			UserActivityPointHandler::getInstance()->fireEvent('de.codequake.linklist.activityPointEvent.link', $link->linkID, $link->userID);
			// update search index
			SearchIndexManager::getInstance()->add('de.codequake.linklist.link', $link->linkID, $link->message, $link->subject, $link->time, $link->userID, $link->username, $link->languageID);
		}
		// reset storage
		UserStorageHandler::getInstance()->resetAll('linklistUnreadLinks');
	}

	public function update() {
		// count attachments
		if (isset($this->parameters['attachmentHandler']) && $this->parameters['attachmentHandler'] !== null) {
			$this->parameters['data']['attachments'] = count($this->parameters['attachmentHandler']);
		}
		parent::update();
		$objectIDs = array();
		foreach ($this->objects as $object) {
			$objectIDs[] = $object->linkID;
			if (isset($this->parameters['categoryIDs'])) {
				$object->updateCategoryIDs($this->parameters['categoryIDs']);
			}

			// moderated content
			if (isset($this->parameters['data']['isDisabled'])) {
				if ($this->parameters['data']['isDisabled']) {
					$this->addModeratedContent($object->linkID);
				}
				else {
					$this->removeModeratedContent($object->linkID);
				}
			}

			// edit
			if (isset($this->parameters['isEdit'])) {
				$reason = (isset($this->parameters['data']['editReason'])) ? $this->parameters['data']['editReason'] : '';
				LinkModificationLogHandler::getInstance()->edit($object->getDecoratedObject(), "");
			}
			// update tags
			$tags = array();
			if (isset($this->parameters['tags'])) {
				$tags = $this->parameters['tags'];
				unset($this->parameters['tags']);
			}
			if (! empty($tags)) {

				$languageID = (! isset($this->parameters['data']['languageID']) || ($this->parameters['data']['languageID'] === null)) ? LanguageFactory::getInstance()->getDefaultLanguageID() : $this->parameters['data']['languageID'];
				TagEngine::getInstance()->addObjectTags('de.codequake.linklist.link', $object->linkID, $tags, $languageID);
			}
		}
		if (! empty($objectIDs)) SearchIndexManager::getInstance()->delete('de.codequake.linklist.link', $objectIDs);
		if (! empty($objectIDs)) SearchIndexManager::getInstance()->add('de.codequake.linklist.link', $object->linkID, $object->message, $object->subject, $object->time, $object->userID, $object->username, $object->languageID);
	}

	// unmark
	public function validateUnmarkAll() {}

	public function unmarkAll() {
		ClipboardHandler::getInstance()->removeItems(ClipboardHandler::getInstance()->getObjectTypeID('de.codequake.linklist.link'));
	}

	protected function unmarkItems(array $objectIDs = array()) {
		if (empty($objectIDs)) {
			foreach ($this->objects as $link) {
				$objectIDs[] = $link->linkID;
			}
		}

		if (! empty($objectIDs)) {
			ClipboardHandler::getInstance()->unmark($objectIDs, ClipboardHandler::getInstance()->getObjectTypeID('de.codequake.linklist.link'));
		}
	}

	public function validateMarkAsRead() {
		if (empty($this->objects)) {
			$this->readObjects();

			if (empty($this->objects)) {
				throw new UserInputException('objectIDs');
			}
		}
	}

	public function markAsRead() {
		if (empty($this->parameters['visitTime'])) {
			$this->parameters['visitTime'] = TIME_NOW;
		}

		if (empty($this->objects)) {
			$this->readObjects();
		}

		$linkIDs = array();
		foreach ($this->objects as $link) {
			$linkIDs[] = $link->linkID;
			VisitTracker::getInstance()->trackObjectVisit('de.codequake.linklist.link', $link->linkID, $this->parameters['visitTime']);
		}

		// reset storage
		if (WCF::getUser()->userID) {
			UserStorageHandler::getInstance()->reset(array(
			WCF::getUser()->userID
			), 'linklistUnreadLinks');
		}
	}

	public function validateMarkAllAsRead() {
		/**
		 * Does nothing like a boss *
		 */
	}

	public function markAllAsRead() {
		VisitTracker::getInstance()->trackTypeVisit('de.codequake.linklist.link');
		// reset storage
		if (WCF::getUser()->userID) {
			UserStorageHandler::getInstance()->reset(array(
			WCF::getUser()->userID
			), 'linklistUnreadLinks');
		}
	}

	public function validateGetIpLog() {
		if (! LOG_IP_ADDRESS) {
			throw new PermissionDeniedException();
		}

		if (isset($this->parameters['linkID'])) {
			$this->lins = new Link($this->parameters['linkID']);
		}
		if ($this->link === null || ! $this->link->linkID) {
			throw new UserInputException('linkID');
		}

		if (! $this->link->canRead()) {
			throw new PermissionDeniedException();
		}
	}

	public function getIpLog() {
		// get ip addresses of the author
		$authorIpAddresses = Link::getIpAddressByAuthor($this->link->userID, $this->link->username, $this->link->ipAddress);

		// resolve hostnames
		$newIpAddresses = array();
		foreach ($authorIpAddresses as $ipAddress) {
			$ipAddress = UserUtil::convertIPv6To4($ipAddress);

			$newIpAddresses[] = array(
				'hostname' => @gethostbyaddr($ipAddress),
				'ipAddress' => $ipAddress
			);
		}
		$authorIpAddresses = $newIpAddresses;

		// get other users of this ip address
		$otherUsers = array();
		if ($this->link->ipAddress) {
			$otherUsers = Link::getAuthorByIpAddress($this->link->ipAddress, $this->link->userID, $this->link->username);
		}

		$ipAddress = UserUtil::convertIPv6To4($this->link->ipAddress);

		if ($this->link->userID) {
			$sql = "SELECT	registrationIpAddress
				FROM	wcf" . WCF_N . "_user
				WHERE	userID = ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array(
				$this->link->userID
			));
			$row = $statement->fetchArray();

			if ($row !== false && $row['registrationIpAddress']) {
				$registrationIpAddress = UserUtil::convertIPv6To4($row['registrationIpAddress']);
				WCF::getTPL()->assign(array(
				'registrationIpAddress' => array(
				'hostname' => @gethostbyaddr($registrationIpAddress),
				'ipAddress' => $registrationIpAddress
				)
				));
			}
		}

		WCF::getTPL()->assign(array(
		'authorIpAddresses' => $authorIpAddresses,
		'ipAddress' => array(
		'hostname' => @gethostbyaddr($ipAddress),
		'ipAddress' => $ipAddress
		),
		'otherUsers' => $otherUsers,
		'link' => $this->link
		));

		return array(
			'linkID' => $this->link->linkID,
			'template' => WCF::getTPL()->fetch('linkIpAddress', 'linklist')
		);
	}

	// toggle
	public function validateEnable() {
		if (empty($this->objects)) $this->readObjects();
		foreach ($this->objects as $link) {
			if ($link->isActive) {
				throw new PermissionDeniedException();
			}
		}
	}

	public function enable() {
		if (empty($this->objects)) $this->readObjects();
		foreach ($this->objects as $link) {
			$link->update(array(
				'isDisabled' => 0
			));
			LinkModificationLogHandler::getInstance()->enable($link->getDecoratedObject());
			$this->removeModeratedContent($link->linkID);
			$this->publish();
		}

		$this->unmarkItems();
	}

	public function validateDisable() {
		if (empty($this->objects)) $this->readObjects();
		foreach ($this->objects as $link) {
			if ($link->isDisabled) {
				throw new PermissionDeniedException();
			}
		}
	}

	public function disable() {
		if (empty($this->objects)) $this->readObjects();
		foreach ($this->objects as $link) {
			$link->update(array(
				'isDisabled' => 1
			));

			LinkModificationLogHandler::getInstance()->disable($link->getDecoratedObject());
			$this->addModeratedContent($link->linkID);
		}

		$this->unmarkItems();
	}


	// delete
	public function validateDelete() {
		if (empty($this->objects)) $this->readObjects();
	}

	public function delete() {
		if (empty($this->objects)) $this->readObjects();
		$attachedLinksIDs = array();
		foreach ($this->objects as $link) {
			if ($link->attachments != 0) $attachedLinkIDs[] = $link->linkID;
			$this->removeModeratedContent($link->linkID);
		}
		// remove attaches
		if (! empty($attachedLinkIDs)) {
			AttachmentHandler::removeAttachments('de.codequake.linklist.link', $attachedLinkIDs);
		}
		// remove activity points
		UserActivityPointHandler::getInstance()->removeEvents('de.codequake.linklist.activityPointEvent.link', $this->objectIDs);

		// delete
		parent::delete();
		foreach ($this->objects as $link) {
			// remove tags
			TagEngine::getInstance()->deleteObjectTags('de.codequake.linklist.link', $link->linkID);
		}
		if (! empty($this->objectIDs)) SearchIndexManager::getInstance()->delete('de.codequake.linklist.link', $this->objectIDs);
	}

	public function getLinkPreview() {
		$list = new ViewableLinkList();
		$list->getConditionBuilder()->add("link.linkID = ?", array(
			$this->link->linkID
		));
		$list->readObjects();
		$links = $list->getObjects();
		WCF::getTPL()->assign(array(
			'link' => reset($links)
		));
		return array(
			'template' => WCF::getTPL()->fetch('linkPreview', 'linklist')
		);
	}

	public function validateGetLinkPreview() {
		$this->link = $this->getSingleObject();
		foreach ($this->link->getCategories() as $category) {
			if (!$category->getPermission('canViewLink')) throw new PermissionDeniedException();
		}
	}

	protected function removeModeratedContent($linkID) {
		ModerationQueueActivationManager::getInstance()->removeModeratedContent('de.codequake.linklist.link', array(
			$linkID
		));
	}

	protected function addModeratedContent($linkID) {
		ModerationQueueActivationManager::getInstance()->addModeratedContent('de.codequake.linklist.link', $linkID);
	}
}
