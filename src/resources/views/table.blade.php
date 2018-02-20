

<thead>
    <tr>

        @php 
            $index        = !isset($index) ? 'id' : $index;
            $order_except = isset($order_except) ? array_flip(explode(',', $order_except)) : [];
        @endphp
    
        @foreach(json_decode($slot) as $field => $label)

            @if( isset($order_except[$field]) )

                <th class="no-order">
                    {{ $label }}
                </th>

            @else 


                @php 
                    $by = (request()->query('order') == $field && request()->query('by') == 'asc') 
                        ? 'desc' : 'asc';
                    $qstring = array_merge(request()->all(), ['order' => $field, 'by' => $by]);
                @endphp

                @if($field == $index)

                    <th style="width: 50px;" class="text-center index-field">
                        <a href="{{ route(request()->route()->getName(), $qstring) }}" class="d-block">
                        {{ $label }}
                        </a>
                    </th>

                @else

                    <th class="order-{{ $by=='asc' ? 'desc' : 'asc' }} {{ request()->query('order') == $field ? 'active' : '' }}">
                        <a href="{{ route(request()->route()->getName(), $qstring) }}" class="d-block">
                        {{ $label }}
                        </a>
                    </th>

                @endif

            @endif

        @endforeach 
        
        @if(!isset($show_actions))

            <th class="text-center table-head-actions no-order">Ações</th>

        @elseif($show_actions != 'none')

            <th class="text-center table-head-actions no-order">{{ $show_actions}}</th>

        @endif

    </tr>
</thead>
