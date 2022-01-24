@extends('laravel-admin::fields.select')

@php
    $options = $options ?? [];
    $multiple = $multiple ?? false;
    $placeholder = $placeholder ?? null;

    $ajax = $ajax ?? null;
    $ajax = is_string($ajax) ? ['url' => $ajax] : $ajax;

    $allowClear = $clearable ?? false;
@endphp

@pushonce('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpushonce

@pushonce('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpushonce

@push('js')
    <script>
        jQuery(document).ready(function($) {
            let input = $('#input-{{ $name }}');
            let placeholder = '{{ $placeholder }}' || input.find('option').eq(0).text() || '';

            let options = {
                closeOnSelect: {{ $multiple ? 'false' : 'true' }},
                allowClear: placeholder && {{ $allowClear ? 'true' : 'false' }},
                placeholder,
            };

            @if (!empty($ajax))
                options.ajax = {
                    url: "{{ $ajax['url'] }}",
                    delay: "{{ $ajax['delay'] ?? 250 }}", // wait x milliseconds before triggering the request
                };
            @endif

            input.select2(options);
        });
    </script>
@endpush