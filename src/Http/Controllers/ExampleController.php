<?php

namespace SortableGrid\Http\Controllers;

class ExampleController extends SortableGridController
{
    protected $initial_field = 'id';

    protected $initial_order = 'desc';

    protected $initial_perpage = 10;

    protected $fields = [
        'id'         => 'ID',
        'name'       => 'Nome',
        'email'     => 'E-mail',
        'created_at' => 'Criação',
        'Ações'
    ];

    protected $searchable_fields = [
        'name',
        'email',
    ];

    protected $orderly_fields = [
        'id',
        'name',
        'email',
        'created_at',
    ];

    protected $searchable_view = 'sortablegrid::index';

    /**
     * Devolve a coleção que será usada para a busca.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getSearchableCollection()
    {
        return \App\User::query();
    }
}
