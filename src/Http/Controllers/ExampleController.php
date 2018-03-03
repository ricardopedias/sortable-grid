<?php

namespace SortableGrid\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends SortableGridController
{
    /** 
     * Campo de ordenação padrão
     * @var string
     */
    protected $initial_field = 'id';

    /**
     * Classificação de ordenação padrão 
     * @var string
     */
    protected $initial_order = 'desc';

    /**
     * Número de páginas padrão 
     * @var string
     */
    protected $initial_perpage = 10;

    /**
     * Todas as colunas a serem renderizadas no grid.
     * @var array 
     */
    protected $fields = [
        'id'         => 'ID',
        'name'       => 'Nome',
        'email'      => 'E-mail',
        'created_at' => 'Criação',
        'Ações'
    ];

    /**
     * Apenas os campos que serão consultados ao efetuar uma busca.
     * @var array 
     */
    protected $searchable_fields = [
        'name',
        'email',
    ];

    /**
     * Apenas os campos que poderão ser ordenados pelo usuário.
     * @var array 
     */
    protected $orderly_fields = [
        'id',
        'name',
        'email',
        'created_at',
    ];

    /**
     * A implementação deste método abstrato deve devolver 
     * o builder que será manipulado pelo mecanismo interno.
     *
     * @see SortableGridController -> searchableView
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getSearchableBuilder()
    {
        return \App\User::query();
    }

    /**
     * Renderiza a view com as informações da grade de dados
     *
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->searchableView('sortablegrid::index');
    }
}
