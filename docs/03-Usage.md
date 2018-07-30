# 3. Como Usar 

## 3.1. Ativando o Sortable Grid

Para ativar o Sortable Grid em qualquer classe PHP, basta usar o Trait HasSortableGrid como no exemplo abaixo:


```php
use SortableGrid\Traits\HasSortableGrid;

class ExampleController extends Controller
{
    use HasSortableGrid;
    
    ...
}
```

### 3.2. Implementando uma grade de dados

Após adicionar o Trait, os métodos de configuração estarão disponiveis para utilização. Abaixo, um exemplo completo da implementaão de uma grade com a lista de usuários do sistema:

```php
use SortableGrid\Traits\HasSortableGrid;

class ExampleController extends Controller
{
    use HasSortableGrid;


    public function index(Request $request)
    {
        $this->setInitials('id', 'desc', 10);

        // Seta os campos que estarão disponíveis na grade de dados
        $this->addGridField('ID', 'id');
        $this->addGridField('Nome', 'name');
        $this->addGridField('Criação', 'created_at');
        $this->addGridField('Ações');

        // Seta os campos usados para consultas de busca
        $this->addSearchField('id');
        $this->addSearchField('name');

        // Seta os campos que poderão ser ordenáveis via clique do mouse
        $this->addOrderlyField('id');
        $this->addOrderlyField('name');
        $this->addOrderlyField('created_at');

        // Seta o query builder para o sortable grid
        $provider = \App\Users::query();
        $this->setDataProvider($provider);

        // Devolve a visão especial da grade de dados
        return $this->gridView('users.index');
    }

}
```


**setInitials()** 

Define o campo que será ordenado por padrão quando a grade de dados for exibida. 
Deve conter o **nome real do campo** na tabela do banco de dados, a **ordenação padrão** e o **limite de registros** para paginação padrão. Caso este método não seja invocado, os valores padrões serão setInitials('id', 'asc', 15);

```php

$this->setInitials('id', 'desc', 10);

```

**addGridField()** 

Define as colunas que deverão ser renderizadas na grade de dados. As colunas podem ser interativas (usuário pode ordenar) ou apenas textuais. As ***Colunas interativas*** são definidas setando os dois parâmetros do método, sendo o segundo correspondente ao **nome real do campo** da tabela no banco de dados:

```php

$this->addGridField('Criação', 'created_at');

```
No código acima, *created_at* é o campo real da tabela no banco de dados e *Criação* é o texto que será exibido como título da coluna. O usuário poderá ordenar esta coluna clicando na célula correspondente do cabeçalho.

As ***Colunas textuais*** são definidas ao setar apenas o primeiro parâmetro do método:

```php

$this->addGridField('Ações');

```

No código acima, *Ações* é uma coluna textual (não ordenável), apenas para exibição.

**addSearchField()** 

Define os campos que serão consultados ao efetuar uma busca. Quando o usuário efetuar a busca, uma pesquisa do tipo 'like' será efetuada no banco de dados usando as colunas especificadas na invocação deste método:


```php

$this->addSearchField('id');
$this->addSearchField('name');
        
```

**addOrderlyField()** 

Define os campos que poderão ser ordenados pelo usuário atravé de um clique no cabeçalho correspondente na grade de dados. Os campos contidos aqui só serão ordenáveis de fato, se também forem campos interativos setados com o método  ***addGridField()***.

```php

$this->addOrderlyField('id');
$this->addOrderlyField('name');
$this->addOrderlyField('created_at');

```

***setDataProvider()***

Disponibiliza para o mecanismo de pesquisa do Sortable Grid um objeto Builder que será usado para gerar a paginação e as buscas. O objeto pode ser um **\Illuminate\Database\Eloquent\Builder** ou um **\Illuminate\Database\Query\Builder**.

Internamente, o Sortable Grid irá adicionar a limitação e os filtros necessários para os dados serem exibidos na grade de dados.

```php

$provider = \App\Users::query(); // Objeto \Illuminate\Database\Eloquent\Builder com os dados do Modelo Users
$this->setDataProvider($provider);

```

### DataProvider com relacionamentos:

Se o Builder setado como provedor de dados possuir **joins**, seus campos podem ser declarados (nos métodos setInitials, addGridField, addSearchField e addOrderlyField) com suas respecivas **tabelas ou aliases**. Observe no exemplo abaixo:

```
$this->addGridField('Categoria', 'categories.label');

$this->addOrderlyField('users.created_at');
```

***gridView()***

Informa ao Sortable Grid qual visão utilizar para desenhar a grade de dados. O mecanismo irá renderizar o código HTML usando uma estrutura preparada com os eventos corretos nos cabeçalhos da tabela, de acordo com as informações setadas pelos métodos acima.

```php

return $this->gridView('users.index');

```

## 3.3. Implementando o template da grade de dados

O método gridView() vai localizar a visão especificada e disponibilizar para ela automaticamente a variável **$collection**, que contémm a lista de registros com as colunas especificadas pelas chamadas ao método **addGridField**.

No caso do exemplo, conterá três colunas: id, name e created_at.


***Diretiva @sg_table***

Na visão, será necessário escrever o código HTML apenas para as linhas com a tag `<tr>`, dentro do loop efetuado com a variável **$collection**.

O invólucro com a tag `<table>` juntamente com a linha contendo os cabeçalhos `<th>` são desenhados automaticamente pela diretiva `@sg_table` e fechados com a diretiva `@end_sg_table`, que devem ser adicionadas envolvendo o loop.

Abaixo, um exemplo de implementação da visão para grade de dados:


```html

    @sg_table

        @foreach($collection as $item)

            <tr>
                <td class="text-center">{{ $item->id }}</td>

                <td>{{ $item->name }}</td>

                <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>

                <td class="text-center">
                    
                    <a href="/usuarios/efetuar-acao/23" class="btn btn-info btn-sm" title="Botão de Ação">
                        <i class="fa fa-plus"></i>
                        <span class="d-none d-lg-inline">Botão de Ação</span>
                    </a>

                </td>
            </tr>

        @endforeach
               
    @end_sg_table

```

Além da grade de dados, o Sortable Grid disponibiliza também outras diretivas, para auxiliar na exibição de informações sobre os registros, filtrar informações, controlar as páginas disponiveis, etc.

***Diretiva @sg_perpage***

Desenha um menu para o usuário selecionar o número de registros por página.

```html
@sg_perpage
```

***Diretiva @sg_search***

Desenha um *input* para entrada de texto onde o usuário pode fornecer uma palavra que será pesquisada nos resultados.

```text
@sg_search
```

***Diretiva @sg_info***

Desenha as informações sobre o estado atual da grade de dados, como o número de resultados encontrados, o número de páginas, etc.

```text
@sg_info
```

***Diretiva @sg_pagination***

Desenha um paginador, para que o usuário possa navegar facilmente entre os resultados da grade de dados.

```text
@sg_pagination
```


## 3.4. Personalizando a grade de dados

Caso seja necesário um refinamento maior na grade de dados (como por exemplo utilizar outro framework diferente do Bootstrap 4) é possível fazê-lo através do arquivo de configuração do Sortable Grid. 

Para usar o arquivo de configuração, basta publicá-lo usando o *artisan*:

```bash
php artisan vendor:publish --tag=sortablegrid-config
```

Após executar este comando, o arquivo de configuração poderá ser encontrado em `config/sortablegrid.php` no seu projeto Laravel.


## Sumário

  1. [Sobre](01-About.md)
  2. [Instalação](02-Installation.md)
  3. [Como Usar](03-Usage.md)
  4. [Extras](04-Extras.md)

...