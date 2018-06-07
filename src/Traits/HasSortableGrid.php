<?php

namespace SortableGrid\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait HasSortableGrid
{
    /**
     * Instância do builder usado para a busca
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $sg_data_provider = null;

    /**
     * Campo de ordenação padrão
     * @var string
     */
    protected $sg_initial_field = 'id';

    /**
     * Classificação de ordenação padrão
     * @var string
     */
    protected $sg_initial_order = 'desc';

    /**
     * Número de páginas padrão
     * @var string
     */
    protected $sg_initial_perpage = 10;

    /**
     * Todas as colunas a serem renderizadas no grid.
     * @var array
     */
    protected $sg_fields = [];

    /**
     * Apenas os campos que serão consultados ao efetuar uma busca.
     * @var array
     */
    protected $sg_searchable_fields = [];

    /**
     * Apenas os campos que poderão ser ordenados pelo usuário.
     * @var array
     */
    protected $sg_orderly_fields = [];

    /**
     * Especifica a fonte de dados para a busca.
     * O argumento $source pode ser:
     * 1. uma instancia de Illuminate\Database\Eloquent\Builder
     * 2. uma string com o nome da tabela onde estão os dados
     *
     * @param Illuminate\Database\Eloquent\Builder|Illuminate\Database\Query\Builder|string $source
     * @throws \InvalidArgumentException
     */
    protected function setDataProvider($source)
    {
        $eloquent_builder = \Illuminate\Database\Eloquent\Builder::class;
        $database_builder = \Illuminate\Database\Query\Builder::class;

        if($source instanceof $eloquent_builder) {
            $this->sg_data_provider = $source;
        } elseif($source instanceof $database_builder) {
            $this->sg_data_provider = $source;
        } elseif(is_string($source)) {
            $this->sg_data_provider = \DB::table($source)->select();
        } else {
            throw \InvalidArgumentException(
                'Invalid source for data provider. Provide a Eloquent\Builder or the name of a table');
        }
        return $this;
    }

    /**
     * Seta as informações iniciais para a grade de dados.
     *
     * @param string  $field   Campo de ordenação padrão
     * @param string  $order   Classificação de ordenação padrão
     * @param integer $perpage Número de itens por página
     */
    protected function setInitials(string $field, string $order = 'desc', int $perpage = 10)
    {
        $this->sg_initial_field   = $field;
        $this->sg_initial_order   = $order;
        $this->sg_initial_perpage = $perpage;
        return $this;
    }

    /**
     * Adiciona uma coluna ao grid de dados.
     * @param string $label         Nome para exibição no grid
     * @param string|null $db_field Nome do campo no banco de dados
     */
    protected function addGridField(string $label, string $db_field = null)
    {
        if($db_field === null) {
            $this->sg_fields[] = $label;
        } else {
            $this->sg_fields[$db_field] = $label;
        }
        return $this;
    }

    /**
     * Adiciona um campo pesquisável na consulta de uma busca.
     * @param string $db_field Nome do campo no banco de dados
     */
    protected function addSearchField(string $db_field)
    {
        $this->sg_searchable_fields[] = $db_field;
        return $this;
    }

    /**
     * Adiciona um campo que poderá ser ordenado pelo usuário.
     * @param string $db_field Nome do campo no banco de dados
     */
     protected function addOrderlyField(string $db_field)
     {
         $this->sg_orderly_fields[] = $db_field;
         return $this;
     }

    /**
     * A implementação deste método abstrato deve devolver
     * o builder que será manipulado pelo mecanismo interno.
     *
     * @see SortableGridController -> searchableView
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \LogicException
     */
    protected function getDataProvider()
    {
        if($this->sg_data_provider == null) {
            throw new \LogicException(
                'Data Provider was not specified. Please provide it using the setDataProvider method!');
        }

        return $this->sg_data_provider;
    }

    /**
     * Renderiza a view com as informações da grade de dados
     *
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    protected function gridView($view)
    {
        $request = request();

        $fields = $this->sg_searchable_fields;

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

        $order   = strtolower($request->get('order', $this->sg_initial_field));
        $by      = strtolower($request->get('by', $this->sg_initial_order));
        $perpage = $request->get('perpage', $this->sg_initial_perpage);

        $collection = $this->getDataProvider()
            ->where($filters)
            ->orderBy($order, $by)
            ->paginate($perpage)
            ->appends($request->all());

        $view_pagination = config('sortablegrid.views.pagination');

        // Guarda os dados para o blade identificar
        session([
            'sg.fields'          => $this->sg_fields,
            'sg.searchable'      => $this->sg_searchable_fields,
            'sg.orderly'         => $this->sg_orderly_fields,
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
