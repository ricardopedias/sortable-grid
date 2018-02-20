<?php

use Illuminate\Support\Str;

/**
 * Para Mais informações sobre a criação de diretivas:
 * 
 * https://laravel.com/docs/5.5/blade#extending-blade
 * https://scotch.io/tutorials/all-about-writing-custom-blade-directives
 */

// Em modo de desenvolvimento, as views são sempre apagadas
if (env('APP_DEBUG') || env('APP_ENV') === 'local') {
    // php artisan view:clear
    \Artisan::call('view:clear');
}

    /*
    |--------------------------------------------------------------------------
    | Bloqueio de conteudo restrito
    |--------------------------------------------------------------------------
    |
    | Se o usuário não possuir credenciais, exibe uma mensagem de 
    | acesso não autorizado no lugar do conteudo.
    |
    | @aclock('role', 'callback opcional')
    |
    |  ... conteudo html
    |
    | @endaclock('view opcional')
    */

    \Blade::directive('sghead', function ($expression) {

        $fields     = session('sg.fields');
        $searchable = session('sg.searchable');
        $orderly   = array_flip(session('sg.orderly'));

        session()->forget('sg.fields');
        session()->forget('sg.searchable');
        session()->forget('sg.orderly');

        $code  = '<div class="table-responsive">';
        $code .= '<table class="table table-striped table-bordered sg-sortable-table">';
        $code .= '<thead>';
        $code .= '<tr>';

        foreach( $fields as $field => $label) {

            if (is_numeric($field)) {
                $field = Str::slug($label);
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

                $code .= "<th class=\"text-center sg-orderly-field sg-{$field}-field\" onclick=\"location.href=\'{$url}\';\">";
                $code .= "<span>{$label}</span>";
                $code .= "</th>";
            }
            else {
                $code .= "<th class=\"text-center sg-unorderly-field sg-{$field}-field\">";
                $code .= "<span>{$label}</span>";
                $code .= "</th>";
            }
        }

        $code .= '</tr>';
        $code .= '</thead>';
        $code .= '<tbody>';

        $open = "?php";
        $close = "?";

        return "<{$open} echo '$code'; {$close}>";
    });

    \Blade::directive('sgfoot', function ($expression) {

        $total_registers = session('sg.total_registers');
        $first_item      = session('sg.first_item');
        $last_item       = session('sg.last_item');
        $last_page       = session('sg.last_page');
        $invalid_page    = session('sg.invalid_page');
        $pagination      = session('sg.pagination');

        session()->forget('sg.total_registers');
        session()->forget('sg.first_item');
        session()->forget('sg.last_item');
        session()->forget('sg.last_page');
        session()->forget('sg.invalid_page');
        session()->forget('sg.pagination');

        $code  = "</tbody>";
        $code .= "</table>";
        $code .= "</div>";

        $code .= '<div class="row">';

            $code .= '<div class="col">';

                $code .= '<button class="btn text-primary" style="background: transparent; box-shadow: none !important;">';
                $code .= '<i class="fa fa-info-circle"></i> ';

                $code .= "Exibindo de $first_item a $last_item ";

                if ($total_registers == 1) {
                    $code .= "de $total_registers registro ";
                }
                else {
                    $code .= "de $total_registers registros ";
                }

                if ($last_page == 1) {
                    $code .= 'em uma página ';
                }
                else {
                    $code .= "em $last_page páginas ";
                }
                            
                $code .= '</button>';

                // Redireciona se o numero de paginas não for válido
                if ($invalid_page) {

                    $qstring = array_merge(request()->all(), ['page' => $collection->lastPage()]); 
                    if (request()->route()->getName() == null) {
                        $url = trim(request()->route()->uri, '/');
                        $url = "/" . request()->route()->uri . "?" . http_build_query($qstring);
                    }
                    else {
                        $url = route(request()->route()->getName(), $qstring);
                    }

                    $code .= '<script>';
                    $code .= "$(location).attr({ href : '{$url}' });";
                    $code .= '</script>';
                }
            
            $code .= '</div>';

            $code .= '<div class="col">';
            $code .= $pagination;
            $code .= '</div>';
        
        $code .= '</div>';

        $open = "?php";
        $close = "?";

        return "<{$open} echo '$code'; {$close}>";
    });
