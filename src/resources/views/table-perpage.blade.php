
@php

    $perpage = session('sg.perpage');

@endphp

<div class="sg-perpage d-inline-block">

    <div class="dropdown">

        <a class="btn btn-light dropdown-toggle" href="javascript:void(0)" id="table-perpage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Exibir {{ $perpage }} itens 
      </a>

        <div class="dropdown-menu bg-light" aria-labelledby="table-perpage">

            @foreach([5,10,25,50,100] as $value)

                @php 
                    $qstring = array_merge(request()->all(), ['perpage' => $value]); 
                    if (request()->route()->getName() == null) {
                        $url = trim(request()->route()->uri, '/');
                        $url = "/" . $url . "?" . http_build_query($qstring);
                    }
                    else {
                        $url = route(request()->route()->getName(), $qstring);
                    }

                @endphp

                <a class="dropdown-item text-dark" 
                   href="{{ $url }}">{{ $value }}</a>

            @endforeach
            
        </div>
    </div>
    
</div>