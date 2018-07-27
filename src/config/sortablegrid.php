<?php
/**
 * @see       https://github.com/rpdesignerfly/sortable-grid
 * @copyright Copyright (c) 2018 Ricardo Pereira Dias (https://rpdesignerfly.github.io)
 * @license   https://github.com/rpdesignerfly/sortable-grid/blob/master/license.md
 */

declare(strict_types=1);

return [

    // Visões
    // Os templates podem ser personalizados, configurando-os nesta seção.
    'views' => [
        'open'       => 'sortablegrid::table-open',
        'close'      => 'sortablegrid::table-close',
        'info'       => 'sortablegrid::table-info',
        'pagination' => 'sortablegrid::table-pagination',
        'perpage'    => 'sortablegrid::table-perpage',
        'search'     => 'sortablegrid::table-search',
    ],
];
