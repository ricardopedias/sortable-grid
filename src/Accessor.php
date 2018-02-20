<?php 

namespace SortableGrid;

/**
 * ...
 */
class Accessor
{
    /**
     * Carrega e inclui os helpers do pacote
     * 
     * @return void
     */
    public function loadHelpers()
    {
        include('helpers.php');
    }

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
