<?php
/**
 * @see       https://github.com/rpdesignerfly/sortable-grid
 * @copyright Copyright (c) 2018 Ricardo Pereira Dias (https://rpdesignerfly.github.io)
 * @license   https://github.com/rpdesignerfly/sortable-grid/blob/master/license.md
 */

declare(strict_types=1);

namespace SortableGrid;

class Accessor
{
    /**
     * Carrega e registra as diretivas para o blade
     *
     * @return void
     */
    public function loadBladeDirectives()
    {
        include('directives.php');
    }
}
