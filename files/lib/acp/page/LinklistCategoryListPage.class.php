<?php
namespace linklist\acp\page;

use wcf\acp\page\AbstractCategoryListPage;

/**
 * category list.
 *
 * @author Jens Krumsieck
 * @copyright 2013 codeQuake
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package de.codequake.linklist
 *
 */
class LinklistCategoryListPage extends AbstractCategoryListPage {

	public $activeMenuItem = 'linklist.acp.menu.link.linklist.category.list';

	public $objectTypeName = 'de.codequake.linklist.category';
}
