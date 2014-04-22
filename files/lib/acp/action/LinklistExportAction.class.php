<?php
namespace linklist\acp\action;

use wcf\action\AbstractAction;
use wcf\data\object\type\ObjectTypeCache;
use linklist\data\link\LinkList;
use wcf\util\XMLWriter;
use wcf\util\StringUtil;
use wcf\system\io\TarWriter;
use wcf\system\WCF;

class LinklistExportAction extends AbstractAction {
	public $data = array();
	public $neededPermissions = array(
		'admin.linklist.data.canImportData'
	);
	public $filename;

	public function readParameters() {
		parent::readParameters();
		// categories
		$objectTypeID = ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.category', 'de.codequake.linklist.category');
		$sql = "SELECT * FROM wcf" . WCF_N . "_category WHERE objectTypeID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$objectTypeID
		));
		$i = 0;
		while ($row = $statement->fetchArray()) {
			$this->data['categoryData'][$i]['categoryID'] = $row['categoryID'];
			$this->data['categoryData'][$i]['parentID'] = $row['parentCategoryID'];
			$this->data['categoryData'][$i]['description'] = $row['description'];
			$this->data['categoryData'][$i]['title'] = $row['title'];
			$this->data['categoryData'][$i]['isDisabled'] = 0;
			$i ++;
		}
		
		// links
		$list = new Linklist();
		$list->readObjects();
		$i = 0;
		foreach ($list->getObjects() as $item) {
			$this->data['linkData'][$i]['linkID'] = $item->linkID;
			$this->data['linkData'][$i]['subject'] = $item->getTitle();
			$this->data['linkData'][$i]['url'] = $item->url;
			$this->data['linkData'][$i]['categoryID'] = $item->categoryID;
			$this->data['linkData'][$i]['message'] = $item->message;
			$this->data['linkData'][$i]['userID'] = $item->userID;
			$this->data['linkData'][$i]['username'] = $item->username;
			$this->data['linkData'][$i]['time'] = $item->time;
			$this->data['linkData'][$i]['languageID'] = $item->languageID;
			$this->data['linkData'][$i]['enableSmilies'] = $item->enableSmilies;
			$this->data['linkData'][$i]['enableBBCodes'] = $item->enableBBCodes;
			$this->data['linkData'][$i]['enableHtml'] = $item->enableHtml;
			$this->data['linkData'][$i]['visits'] = $item->visits;
			$this->data['linkData'][$i]['ipAddress'] = $item->ipAddress;
			$i ++;
		}
	}

	protected function buildXML() {
		$xml = new XMLWriter();
		$xml->beginDocument('data', '', '');
		foreach ($this->data['categoryData'] as $cat) {
			$xml->startElement('linkListCategory');
			$xml->writeElement('categoryID', $cat['categoryID']);
			$xml->writeElement('parentID', $cat['parentID']);
			$xml->writeElement('title', $cat['title']);
			$xml->writeElement('description', $cat['description']);
			$xml->endElement();
		}
		if (isset($this->data['linkData'])) {
			foreach ($this->data['linkData'] as $link) {
				$xml->startElement('linkListLink');
				$xml->writeElement('linkID', $link['linkID']);
				$xml->writeElement('categoryID', $link['categoryID']);
				$xml->writeElement('subject', $link['subject']);
				$xml->writeElement('message', $link['message']);
				$xml->writeElement('userID', $link['userID']);
				$xml->writeElement('username', $link['username']);
				$xml->writeElement('url', $link['url']);
				$xml->writeElement('time', $link['time']);
				$xml->writeElement('visits', $link['visits']);
				$xml->writeElement('enableSmilies', $link['enableSmilies']);
				$xml->writeElement('enableBBCodes', $link['enableBBCodes']);
				$xml->writeElement('enableHtml', $link['enableHtml']);
				$xml->writeElement('ipAddress', $link['ipAddress']);
				$xml->endElement();
			}
		}
		$xml->endDocument(LINKLIST_DIR . 'tmp/linkListData.xml');
	}

	protected function tar() {
		$this->filename = LINKLIST_DIR . 'tmp/linkListData-Export.' . StringUtil::getRandomID() . '.gz';
		$tar = new TarWriter($this->filename, true);
		$this->buildXML();
		$tar->add(LINKLIST_DIR . 'tmp/linkListData.xml', '', LINKLIST_DIR . 'tmp/');
		$tar->create();
		@unlink(LINKLIST_DIR . 'tmp/linkListData.xml');
	}

	public function execute() {
		parent::execute();
		$this->tar();
		$this->executed();
		// headers for downloading file
		header('Content-Type: application/x-gzip; charset=utf8');
		header('Content-Disposition: attachment; filename="LinkListData-Export.tar.gz"');
		readfile($this->filename);
		// delete temp file
		@unlink($this->filename);
	}
}
