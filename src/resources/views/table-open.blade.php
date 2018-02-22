
@php

    $fields          = session('sg.fields');
    $searchable      = session('sg.searchable');
    $orderly         = array_flip(session('sg.orderly'));
    
    $order_field     = session('sg.order_field');
    $order_direction = session('sg.order_direction');

    $cols = [];

    foreach( $fields as $field => $label) {

        if (is_numeric($field)) {
            $field = \Illuminate\Support\Str::slug($label);
        }

        $highlight = $field == $order_field 
            ? 'sg-ordered sg-ordered-' . $order_direction
            : '';

        if (isset($orderly[$field])) {

            $by      = (request()->query('order') == $field && request()->query('by') == 'asc') ? 'desc' : 'asc';
            $qstring = array_merge(request()->all(), ['order' => $field, 'by' => $by]);

            if (request()->route()->getName() == null) {
                $url = trim(request()->route()->uri, '/');
                $url = "/" . request()->route()->uri . "?" . http_build_query($qstring);
            }
            else {
                $url = route(request()->route()->getName(), $qstring);
            }

            $cols[] = (object) [
                'url'   => $url,
                'class' => "sg-orderly-field sg-{$field}-field {$highlight}",
                'label' => ($label != '-' ? $label : '')
            ];
        }
        else {

            $cols[] = (object) [
                'url'   => false,
                'class' => "sg-unorderly-field sg-{$field}-field",
                'label' => ($label != '-' ? $label : '')
            ];
        }
    }

@endphp

<style>

    .sg-orderly-field {
        cursor: pointer;
    }

    .sg-orderly-field:hover {
        background: rgba(0,0,0,0.1);
    }

    .sg-ordered {
        background: rgba(0,0,0,0.025);
    }

    {{-- https://www.w3schools.com/charsets/ref_utf_arrows.asp --}}
    .sg-ordered:after {
        display: inline-block;
        float: right;
        /*content: "\2193";*/
        content: "\1F817";
    }
    
    .sg-ordered-desc:after {
        /*content: "\2191";*/
        content: "\1F815";
    }

</style>

<div class="table-responsive">

    <table class="table table-striped table-bordered sg-sortable-table">

        <thead>

            <tr>

                @foreach( $cols as $item)

                    @if ($item->url != false)

                        <th class="text-center {{ $item->class }}" onclick="location.href='{{ $item->url }}';">
                        <span>{{ $item->label }}</span>
                        </th>

                    @else

                        <th class="text-center {{ $item->class }}">
                        <span>{{ $item->label }}</span>
                        </th>

                    @endif

                @endforeach

            </tr>

        </thead>

        <tbody>