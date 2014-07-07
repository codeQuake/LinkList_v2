<?php
namespace linklist\data\link;

use wcf\data\like\object\ILikeObject;
use wcf\data\like\ILikeObjectTypeProvider;
use wcf\data\object\type\AbstractObjectTypeProvider;

class LikeableLinkProvider extends AbstractObjectTypeProvider implements ILikeObjectTypeProvider {

	public $className = 'linklist\data\link\Link';

	public $decoratorClassName = 'linklist\data\link\LikeableLink';

	public $listClassName = 'linklist\data\link\LinkList';

	public function checkPermissions(ILikeObject $object) {
		return $object->isVisible();
	}
}
