

<div class="d-inline-block">

    <div class="dropdown">

        <a class="btn btn-light dropdown-toggle" href="javascript:void(0)" id="table-perpage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exibir {{ $collection->perPage() }} itens
      </a>

        <div class="dropdown-menu bg-light" aria-labelledby="table-perpage">

            @foreach([5,10,25,50,100] as $value)

                @php 
                    $qstring = array_merge(request()->all(), ['perpage' => $value]); 
                @endphp

                <a class="dropdown-item text-dark" 
                   href="{{ route(request()->route()->getName(), $qstring) }}">{{ $value }}</a>

            @endforeach
            
        </div>
    </div>
    
</div>