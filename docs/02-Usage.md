# 2. Como Usar

## Configuração

O Sortable Grid não necessita de nenhuma configuração para funcionar, a menos que se queira personalizar a sua aparência padrão. Para isso, veja a seção "Personalizando" neste mesmo documento.

## Preparando o Controlador

### Extendendo o Controlador

Normalmente, um controlador é implementado extendendo a classe \App\Http\Controllers\Controller. 

No entanto, os controladores que usarem o Sortable Grid, deverão implementar a classe abstrata \SortableGrid\Http\Controllers\SortableGridController que possui rotinas internas para gerar automaticamente as informações necessárias para a grade de dados (como paginação, itens por páginas, termos pesquisados, etc).

```php
use SortableGrid\Http\Controllers\SortableGridController;

class ExampleController extends SortableGridController
{
    ...
}
```

### As propriedades de configuração

O segundo passo será setar as propriedades de configuração para dizer ao Sortable Grid como os campos deverão se comportar:

```php
class ExampleController extends SortableGridController
{
    protected $initial_field = 'id';

    protected $initial_order = 'desc';

    protected $initial_perpage = 10;

    protected $fields = [
        'id'         => 'ID',
        'name'       => 'Nome',
        'email'      => 'E-mail',
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

**$initial_field** 

Define o campo que será ordenado por padrão quando a grade de dados for exibida. Deve conter o nome real do campo na tabela do banco de dados. Caso não seja setado, o valor será 'id';

**$initial_order** 

Define a classificação de ordenação padrão. Deve ser 'asc' ou 'desc'; Caso não seja setado, o valor será 'desc';

**$initial_perpage** 

Define o número de itens por página padrão. Deve conter um valor inteiro; Caso não seja setado, o valor será 10;

**$fields** 

Define as colunas que deverão ser renderizadas na grade de dados. As colunas podem ser interativas (usuário pode ordenar) ou apenas textuais.

***Colunas interativas*** são definidas setando em suas chaves o campo real do banco de dados:

```php
protected $fields = [
    'name' => 'Nome',
];
```
No código acima, 'name' é o campo real da tabela no banco de dados e 'Nome' é o texto que será exibido como título da coluna. O usuário poderá ordenar esta coluna clicando em seu título.

***Colunas textuais*** são definidas omitindo suas chaves:

```php
protected $fields = [
    'name' => 'Nome',
    'Ações'
];
```

No código acima, 'name'=>'Nome' é uma coluna interativa e 'Ações' é uma coluna textual (não ordenável), apenas para exibição.

**$searchable_fields** 

Define os campos que serão consultados ao efetuar uma busca. Quando o usuário efetuar a busca, uma pesquisa do tipo 'like' será efetuada no banco de dados usando as colunas especificadas aqui.

Deve ser um array simples, apenas com os nomes reais dos campos no banco de dados.

```php
protected $searchable_fields = [
    'name',
    'email',
];
```

**$orderly_fields** 

Define os campos que poderão ser ordenados pelo usuário. Deve ser um array simples, apenas com os nomes reais dos campos no banco de dados. Os campos contidos aqui só serão invocados de fato, se também forem campos interativos da propriedade ***$fields***.

```php
protected $orderly_fields = [
   'id',
   'name',
   'email',
   'created_at',
];
```


### Implementação da Lista de Registros


```php
protected function getSearchableBuilder()
{
    return \App\User::query();
}
```

### Renderizando a View

```php
public function index(Request $request)
{
    return $this->searchableView('usuarios.index');
}
```

## Diretivas para layout no Blade

O Sortable Grid possui directivas especias para exibir as informações e os widgets que compõem a grade de dados na implementação de templates do blade.

### Seletor de Itens por Página

```text
@sg_perpage
```

### Input para Pesquisa

```text
@sg_search
```

### Display de Informações

```text
@sg_info
```

### Paginador de Registros

```text
@sg_pagination
```

### Invólucro da Tabela

```text
@sg_table

   <tr>
      <td></td>
      ...
   </tr>

@end_sg_table

```


## Personalizando

Para personalizar o Sortable Grid, será preciso usar o arquivo de configuração, que deve ser publicado através do seguinte comando:

```bash
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