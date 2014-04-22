<?php

namespace linklist\system\log\modification;

use linklist\data\link\Link;
use wcf\system\log\modification\ModificationLogHandler;

class LinkModificationLogHandler extends ModificationLogHandler {
	public function delete(Link $link) {
		$this->add ( $link, 'delete', array (
				'time' => $link->time,
				'username' => $link->username 
		) );
	}
	public function edit(Link $link, $reason = '') {
		$this->add ( $link, 'edit', array (
				'reason' => $reason 
		) );
	}
	public function restore(Link $link) {
		$this->add ( $link, 'restore' );
	}
	public function trash(Link $link, $reason = '') {
		$this->add ( $link, 'trash', array (
				'reason' => $reason 
		) );
	}
	public function enable(Link $link) {
		$this->add ( $link, 'enable' );
	}
	public function disable(Link $link) {
		$this->add ( $link, 'disable' );
	}
	public function setOffline(Link $link) {
		$this->add ( $link, 'offline' );
	}
	public function add(Link $link, $action, array $additionalData = array()) {
		parent::_add ( 'de.codequake.linklist.link', $link->linkID, $action, $additionalData );
	}
}
