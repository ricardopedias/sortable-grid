
@php

    $fields     = session('sg.fields');
    $searchable = session('sg.searchable');
    $orderly   = array_flip(session('sg.orderly'));

    $cols = [];

    foreach( $fields as $field => $label) {

        if (is_numeric($field)) {
            $field = \Illuminate\Support\Str::slug($label);
        }

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
                'class' => "sg-orderly-field sg-{$field}-field",
                'label' => $label
            ];
        }
        else {

            $cols[] = (object) [
                'url'   => false,
                'class' => "sg-unorderly-field sg-{$field}-field",
                'label' => $label
            ];
        }
    }

@endphp

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