<?php
namespace SortableGrid\Http\Controllers;

use Illuminate\Http\Request;
use SortableGrid\Traits\HasSortableGrid;

class ExampleController extends Controller
{
    use HasSortableGrid;

    /**
     * Renderiza a view com as informações da grade de dados
     *
     * @param string $view
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->setInitials('id', 'desc', 10);

        $this->addGridField('ID', 'id');
        $this->addGridField('Nome', 'name');
        $this->addGridField('E-mail', 'email');
        $this->addGridField('Criação', 'created_at');
        $this->addGridField('Ações');

        $this->addSearchField('name');
        $this->addSearchField('email');

        $this->addOrderlyField('id');
        $this->addOrderlyField('name');
        $this->addOrderlyField('email');
        $this->addOrderlyField('created_at');

        $this->setDataProvider(\App\User::query());
        // $this->setDataProvider(\DB::table('users')->select());
        // $this->setDataProvider('users');

        return $this->gridView('sortablegrid::index');
    }
}
