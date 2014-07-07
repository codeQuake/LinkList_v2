<?php
namespace linklist\acp\form;

use wcf\acp\form\AbstractCategoryAddForm;
use wcf\system\WCF;

class LinklistCategoryAddForm extends AbstractCategoryAddForm {

	/**
	 *
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'linklist.acp.menu.link.linklist.category.add';

	/**
	 *
	 * @see wcf\acp\form\AbstractCategoryAddForm::$objectTypeName
	 */
	public $objectTypeName = 'de.codequake.linklist.category';

	/**
	 *
	 * @see wcf\acp\form\AbstractCategoryAddForm::$title
	 */
	public $title = 'linklist.acp.category.add';

	public function readFormParameters() {
		parent::readFormParameters();
		if (isset($_POST['isMainCategory'])) $this->additionalData['isMainCategory'] = intval($_POST['isMainCategory']);
	}

	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign('isMainCategory', isset($this->additionalData['isMainCategory']) ? $this->additionalData['isMainCategory'] : 0);
	}
}
