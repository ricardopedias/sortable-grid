
    @php

        $total_registers = session('sg.total_registers');
        $first_item      = session('sg.first_item');
        $last_item       = session('sg.last_item');
        $last_page       = session('sg.last_page');
        $invalid_page    = session('sg.invalid_page');
        $pagination      = session('sg.pagination');

        // Redireciona se o numero de paginas não for válido
        $script_redirect = '';
        if ($invalid_page) {

            $qstring = array_merge(request()->all(), ['page' => $collection->lastPage()]); 
            if (request()->route()->getName() == null) {
                $url = trim(request()->route()->uri, '/');
                $url = "/" . request()->route()->uri . "?" . http_build_query($qstring);
            }
            else {
                $url = route(request()->route()->getName(), $qstring);
            }

            $script_redirect .= '<script>';
            $script_redirect .= "$(location).attr({ href : '{$url}' });";
            $script_redirect .= '</script>';
        }

    @endphp


        </tbody>

    </table>

</div> {{-- /table-responsive --}}

<div class="row">

    <div class="col">

        <button class="btn text-primary" style="background: transparent; box-shadow: none !important;">
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
                            
        </button>

        {{-- Redireciona se o numero de paginas não for válido --}}
        {!! $script_redirect !!}
            
    </div>

    <div class="col">
        {{ $pagination }}
    </div>
        
</div>
