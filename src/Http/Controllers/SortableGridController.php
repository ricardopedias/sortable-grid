<?php

namespace SortableGrid\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class SortableGridController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
    protected $fields = [];

    /**
     * Apenas os campos que serão consultados ao efetuar uma busca.
     * @var array 
     */
    protected $searchable_fields = [];

    /**
     * Apenas os campos que poderão ser ordenados pelo usuário.
     * @var array 
     */
    protected $orderly_fields = [];

    /**
     * A implementação deste método abstrato deve devolver 
     * o builder que será manipulado pelo mecanismo interno.
     *
     * @see SortableGridController -> searchableView
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract protected function getSearchableBuilder();

    /**
     * Renderiza a view com as informações da grade de dados
     *
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    protected function searchableView($view)
    {
        $request = request();

        $fields = $this->searchable_fields;

        $filters = function ($query) use ($request, $fields) {

            $q = $request->get('q', NULL);
            if ($q !== NULL) {
                foreach ($fields as $index => $name) {
                    if ($index == 0) {
                        $query->where($name, 'like', "%{$q}%");
                    }
                    else {
                        $query->orWhere($name, 'like', "%{$q}%");
                    }
                }
            }
        };
            
        $order   = strtolower($request->get('order', $this->initial_field));
        $by      = strtolower($request->get('by', $this->initial_order));
        $perpage = $request->get('perpage', $this->initial_perpage);

        $collection = $this->getSearchableBuilder()
            ->where($filters)
            ->orderBy($order, $by)
            ->paginate($perpage)
            ->appends($request->all());

        $view_pagination = config('sortablegrid.views.pagination');

        // Guarda os dados para o blade identificar
        session([
            'sg.fields'          => $this->fields,
            'sg.searchable'      => $this->searchable_fields,
            'sg.orderly'         => $this->orderly_fields,
            'sg.order_field'     => $order,
            'sg.order_direction' => $by,
            'sg.total_registers' => $collection->total(),
            'sg.first_item'      => $collection->firstItem(),
            'sg.last_item'       => $collection->lastItem(),
            'sg.last_page'       => $collection->lastPage(),
            'sg.invalid_page'    => ($collection->currentPage() > $collection->lastPage()),
            'sg.pagination'      => $collection->links($view_pagination),
            'sg.perpage'         => $perpage,
        ]);

        return view($view)->with('collection', $collection);
    }
}
