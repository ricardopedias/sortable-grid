@php

    $total_registers = session('sg.total_registers');
    $first_item      = session('sg.first_item');
    $last_item       = session('sg.last_item');
    $last_page       = session('sg.last_page');
    $invalid_page    = session('sg.invalid_page');

    // Redireciona se o numero de paginas não for válido
    $script_redirect = '';
    if ($invalid_page) {

        $qstring = array_merge(request()->all(), ['page' => $last_page]);
        if (request()->route()->getName() == null) {
            $url = trim(request()->route()->uri, '/');
            $url = "/" . request()->route()->uri . "?" . http_build_query($qstring);
        }
        else {
            $url = route(request()->route()->getName(), $qstring);
        }

        $script_redirect .= '<script>';
        $script_redirect .= "window.location.href = '{$url}';";
        $script_redirect .= '</script>';
    }

@endphp

    <nav class="sg-info">

        <div class="text-primary p-1">

            @if( $total_registers == 0)
                <i class="fa fa-info-circle"></i> Não há registros a exibir
            @else
                <i class="fa fa-info-circle"></i> Exibindo de {{ $first_item }} a {{ $last_item }}

                @if ($total_registers == 1)
                    de {{ $total_registers }} registro
                @else
                    de {{ $total_registers }} registros
                @endif

                @if($last_page == 1)
                    em uma página
                @else
                    em {{ $last_page }} páginas
                @endif
            @endif

        </div>
    </nav>

    {{-- Redireciona se o numero de paginas não for válido --}}
    {!! $script_redirect !!}
