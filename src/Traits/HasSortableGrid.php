<?php

namespace SortableGrid\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait HasSortableGrid
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = function ($query) use ($request) {

            $q = $request->get('q', NULL);
            if ($q !== NULL) {
                $query->where('name', 'like', "%{$q}%");
                $query->orWhere('username', 'like', "%{$q}%");
                $query->orWhere('email', 'like', "%{$q}%");
            }
        };
            
        $order   = $request->get('order', 'id');
        $by      = $request->get('by', 'asc');
        $perpage = $request->get('perpage', 10);

        $collection = Lot::where($filters)
            ->where('collect_prevision_setted', '!=', null)
            ->where('collect_perform_setted', '!=', null)
            ->where('collect_receive_setted', null)
            ->orderBy($order, $by)
            ->paginate($perpage)
            ->appends($request->all());

        return view('collect.receive.index')->with('collection', $collection);
    }

    
}