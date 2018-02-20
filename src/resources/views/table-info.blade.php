

<button class="btn text-primary" style="background: transparent;">

    <i class="fa fa-info-circle"></i>

    Exibindo de {{ $collection->firstItem() }} a {{ $collection->lastItem() }} 

    @if($collection->total() == 1)
        de {{ $collection->total() }} registro
    @else
        de {{ $collection->total() }}  registros
    @endif

    @if($collection->lastPage() == 1)
        em uma página
    @else
        em {{ $collection->lastPage() }} páginas
    @endif

</button>

{{-- Redireciona se o numero de paginas não for válido --}}
@if($collection->currentPage() > $collection->lastPage())

    @php 
        $qstring = array_merge(request()->all(), ['page' => $collection->lastPage()]); 
    @endphp

    <script>
        $(location).attr({ href : '{!! route(request()->route()->getName(), $qstring) !!}' });
    </script>

@endif
