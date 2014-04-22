<?php

namespace linklist\system\tagging;

use linklist\data\link\TaggedLinkList;
use wcf\data\tag\Tag;
use wcf\system\tagging\ITaggable;

class TaggableLink implements ITaggable {
	public function getObjectList(Tag $tag) {
		return new TaggedLinkList ( $tag );
	}
	public function getTemplateName() {
		return 'tagLinkList';
	}
	public function getApplication() {
		return 'linklist';
	}
}