<?php
/**
 * @see       https://github.com/rpdesignerfly/sortable-grid
 * @copyright Copyright (c) 2018 Ricardo Pereira Dias (https://rpdesignerfly.github.io)
 * @license   https://github.com/rpdesignerfly/sortable-grid/blob/master/license.md
 */

declare(strict_types=1);

// Este arquivo só é carregado quando o ambiente estiver configurado
// para APP_DEBUG = true ou APP_ENV = local
Route::get('/sortable-grid', 'SortableGrid\Http\Controllers\ExampleController@index');
