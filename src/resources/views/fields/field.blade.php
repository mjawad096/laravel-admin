@php
	$field['title'] = title_case($field['title'] ?? $field['name']);
	$field['type'] = $field['type'] ?? 'text';
	$field['enabled'] = isset($field['enabled']) ? $field['enabled'] : true;
	$field['advance'] = isset($field['advance']) ? $field['advance'] : false;
	$field['default_value'] = $field['default'] ?? null;
@endphp

@if($field['enabled'])
	@includeFirst(["laravel-admin::fields.{$field['type']}", 'laravel-admin::fields.text'], $field)
@endif