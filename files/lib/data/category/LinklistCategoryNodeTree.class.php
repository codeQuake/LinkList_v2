<?php

namespace linklist\data\category;

use wcf\data\category\CategoryNodeTree;

/**
 * List of category nodes
 *
 * @author Jens Krumsieck
 * @copyright 2013 codequake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 */
class LinklistCategoryNodeTree extends CategoryNodeTree {
	protected $nodeClassName = 'linklist\data\category\LinklistCategoryNode';
}