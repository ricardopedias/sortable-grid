@component('sortgrid::document')

    @slot('title') Usuários @endslot

    <div class="row mb-3">

        <div class="col">

            {{-- ... --}}

        </div>

        <div class="col text-right justify-content-end">

            <a href="javascript:void()" class="btn btn-success"
               title="Botão de Ação">
                <i class="fa fa-plus"></i>
                <span class="d-none d-lg-inline">Botão de Ação</span>
            </a>

        </div>
        
    </div>

        @sghead
    
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
               
        @sgfoot

@endcomponent