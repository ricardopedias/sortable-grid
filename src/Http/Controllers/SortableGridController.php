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

    protected $initial_field = 'id';

    protected $initial_order = 'desc';

    protected $initial_perpage = 10;

    protected $fields = [];

    protected $searchable_fields = [];

    protected $orderly_fields = [];

    protected $searchable_view = 'sortablegrid::index';

    /**
     * Devolve a coleção que será usada para a busca.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    abstract protected function getSearchableCollection();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
            
        $order   = $request->get('order', $this->initial_field);
        $by      = $request->get('by', $this->initial_order);
        $perpage = $request->get('perpage', $this->initial_perpage);

        $collection = $this->getSearchableCollection()
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
            'sg.total_registers' => $collection->total(),
            'sg.first_item'      => $collection->firstItem(),
            'sg.last_item'       => $collection->lastItem(),
            'sg.last_page'       => $collection->lastPage(),
            'sg.invalid_page'    => ($collection->currentPage() > $collection->lastPage()),
            'sg.pagination'      => $collection->links($view_pagination),
            'sg.perpage'         => $perpage,
        ]);

        return view($this->searchable_view)->with('collection', $collection);
    }
}
