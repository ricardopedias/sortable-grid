
@php

    $url = (request()->route()->getName() == null)
        ? "/" . trim(request()->route()->uri, '/')
        : route(request()->route()->getName());

@endphp

<div class="d-inline-block">

    <form action="{{ $url }}">

        <div class="input-group mb-3 table-search">

            <input type="text" name="q" class="form-control" placeholder="Digite...">
            
            @foreach(request()->all() as $param => $value)
                @if($param !== 'q')
                <input type="hidden" name="{{ $param }}" value="{{ $param=='page' ? 1 : $value }}">
                @endif
            @endforeach

            <div class="input-group-append">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
            </div>

        </div>

    </form>

</div>