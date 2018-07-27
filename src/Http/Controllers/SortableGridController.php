<?php
/**
 * @see       https://github.com/rpdesignerfly/sortable-grid
 * @copyright Copyright (c) 2018 Ricardo Pereira Dias (https://rpdesignerfly.github.io)
 * @license   https://github.com/rpdesignerfly/sortable-grid/blob/master/license.md
 */

declare(strict_types=1);

namespace SortableGrid\Http\Controllers;

use SortableGrid\Traits\HasSortableGrid;

class SortableGridController extends Controller
{
    use HasSortableGrid;
}
