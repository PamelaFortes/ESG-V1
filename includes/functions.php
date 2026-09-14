<?php

function h($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


function ico($name, $size = 16, $cls = '')
{
    $icons = [

        'shield' =>
            'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',

        'alert' =>
            'M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z|M12 9v4|M12 17h.01',

        'logout' =>
            'M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4|M16 17l5-5-5-5|M21 12H9',

        'chevron' =>
            'M9 18l6-6-6-6',

        'clock' =>
            'M12 22a10 10 0 100-20 10 10 0 000 20z|M12 6v6l4 2',

        'plus' =>
            'M12 5v14|M5 12h14',

        'search' =>
            'M11 19a8 8 0 100-16 8 8 0 000 16z|M21 21l-4.35-4.35',

        'eye' =>
            'M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z|M12 15a3 3 0 100-6 3 3 0 000 6z',

        'edit' =>
            'M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7|M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z',

        'trash' =>
            'M3 6h18|M8 6V4h8v2|M19 6l-1 14H6L5 6|M10 11v5|M14 11v5',

        'bell' =>
            'M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9|M13.73 21a2 2 0 01-3.46 0',

        'upload' =>
            'M12 3v12|M7 8l5-5 5 5|M5 21h14',

        'x' =>
            'M6 6l12 12|M18 6L6 18'
    ];

    $d = $icons[$name] ?? '';

    $paths = '';

    foreach (explode('|', $d) as $path) {
        if ($path !== '') {
            $paths .= "<path d=\"$path\"/>";
        }
    }

    $class = $cls
        ? " class=\"$cls\""
        : '';

    return "
        <svg
            width=\"$size\"
            height=\"$size\"
            viewBox=\"0 0 24 24\"
            fill=\"none\"
            stroke=\"currentColor\"
            stroke-width=\"2\"
            stroke-linecap=\"round\"
            stroke-linejoin=\"round\"
            $class
            aria-hidden=\"true\"
        >
            $paths
        </svg>
    ";
}

function initials($name)
{
    $parts = array_filter(explode(' ', $name));
    $out = '';

    foreach ($parts as $p) {
        if (strlen($out) < 2) {
            $out .= strtoupper($p[0]);
        }
    }

    return $out;
}


function url($params)
{
    return '?' . http_build_query($params);
}


function status_badge($status)
{
    $map = [
        'valid' => ['Válido', 'bdg-valid'],
        'expiring' => ['Próximo do vencimento', 'bdg-expiring'],
        'expired' => ['Vencido', 'bdg-expired']
    ];

    [$lbl, $cls] = $map[$status] ?? ['—', ''];

    return "<span class=\"bdg $cls\">$lbl</span>";
}


function emp_badge($status)
{
    return $status === 'active'
        ? '<span class="bdg bdg-active">Ativo</span>'
        : '<span class="bdg bdg-inactive">Inativo</span>';
}


function find_emp($employees, $id)
{
    foreach ($employees as $e) {
        if ((int)$e['id'] === (int)$id) {
            return $e;
        }
    }

    return $employees[0] ?? null;
}


function active_nav($page)
{
    if (
        in_array(
            $page,
            ['employees', 'employee-form', 'employee-profile']
        )
    ) {
        return 'employees';
    }

    if (
        in_array(
            $page,
            ['trainings', 'training-form', 'register-training']
        )
    ) {
        return 'trainings';
    }

    return $page;
}