# 2. Como Usar

## Configuração

O Sortable Grid não necessita de nenhuma configuração para funcionar, a menos que se queira personalizar a sua aparência padrão. Para isso, veja a seção "Personalizando" neste mesmo documento.

## Preparando o Controlador

### Extendendo o Controlador

Normalmente, um controlador é implementado extendendo a classe \App\Http\Controllers\Controller. 

No entanto, os controladores que usarem o Sortable Grid, deverão implementar a classe abstrata \SortableGrid\Http\Controllers\SortableGridController que possui rotinas internas para gerar automaticamente as informações necessárias para a grade de dados como paginação, itens por páginas, termos pesquisados, etc.

```
use SortableGrid\Http\Controllers\SortableGridController;

class ExampleController extends SortableGridController
{
    ...
}
```

### As propriedades de configuração

O segundo passo será setar as propriedades de configuração para dizer ao Sortable Grid como os campos deverão se comportar:

```
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
```

### Implementação da Lista de Registros


```
protected function getSearchableBuilder()
{
    return \App\User::query();
}
```

### Renderizando a View

```
public function index(Request $request)
{
    return $this->searchableView('usuarios.index');
}
```

## Diretivas para layout no Blade

O Sortable Grid possui directivas especias para exibir as informação e os widgets da grade de dados na implementação de templates do blade.

### Seletor de Itens por Página

```
@sg_perpage
```

### Input para Pesquisa

```
@sg_search
```

### Display de Informações

```
@sg_info
```

### Paginador de Resgistros

```
@sg_pagination
```

### Invólucro da Tabela

```
@sg_table

   <tr>
      <td></td>
      ...
   </tr>

@end_sg_table

```


## Personalizando

Para personalizar o Sortable Grid, será preciso usar o arquivo de configuração, que deve ser publicado através do seguinte comando:

```
php artisan vendor:publish --tag=sortablegrid-config
```

Após executar este comando, o arquivo de configuração poderá ser encontrado em config/sortablegrid.php.


## Sumário

1. [Sobre](00-Home.md)
2. [Instalação](01-Installation.md)
3. [Como Usar](02-Usage.md)
4. [Exemplos](03-Examples.md)
5. [Extras](04-Extras.md)

...