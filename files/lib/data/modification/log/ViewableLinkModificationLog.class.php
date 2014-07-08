<?php
namespace linklist\data\modification\log;

use wcf\data\DatabaseObjectDecorator;
use wcf\system\WCF;

class ViewableLinkModificationLog extends DatabaseObjectDecorator {

	protected static $baseClass = 'wcf\data\modification\log\ModificationLog';

	public function __toString() {
		return WCF::getLanguage()->getDynamicVariable('linklist.link.log.link.' . $this->action, array(
			'additionalData' => $this->additionalData
		));
	}
}
