@component('sortablegrid::document')

    @slot('title') Grade de Dados @endslot

    <hr>

    <div class="row">

        <div class="col">

            @sg_perpage

        </div>

        <div class="col text-right justify-content-end">

            <a href="javascript:void()" class="btn btn-success"
               title="Botão de Ação">
                <i class="fa fa-plus"></i>
                <span class="d-none d-lg-inline">Botão de Ação</span>
            </a>

            @sg_search

        </div>
        
    </div>

    @sg_table
    
        @foreach($collection as $item)

            <tr>
                <td class="text-center">{{ $item->id }}</td>

                <td>{{ $item->name }}</td>

                <td>{!! str_replace(['@', '.'], ['<wbr>@', '<wbr>.'], $item->email) !!}</td>

                <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>

                <td class="text-center">
                    
                    <a href="javascript:void()" class="btn btn-info btn-sm"
                       title="Botão de Ação">
                        <i class="fa fa-plus"></i>
                        <span class="d-none d-lg-inline">Botão de Ação</span>
                    </a>

                </td>
            </tr>

        @endforeach
               
    @end_sg_table

    <div class="row">

        <div class="col">

            @sg_info
                
        </div>

        <div class="col">

            @sg_pagination

        </div>
        
    </div>

@endcomponent