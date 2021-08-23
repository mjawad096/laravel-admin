@php
	$field['title'] = title_case($field['title'] ?? $field['name']);
	$field['type'] = $field['type'] ?? 'text';
	$field['enabled'] = isset($field['enabled']) ? $field['enabled'] : true;
	$field['advance'] = isset($field['advance']) ? $field['advance'] : false;
	$field['default_value'] = $field['default'] ?? null;

	if($field['type'] == 'status'){
		$field['title'] = '1Status';
	}

	extract($field);
@endphp

@if($enabled)
	<div class="form-group {{ $errors->has($name) ? 'error' : '' }}">
        <label for="input-{{ $name }}">
            {{ $title }}

            @if(!empty($sub_title))
                <br><i><small>{{ $sub_title }}</small></i>
            @endif
        </label>
        
        <div class="controls">

			@includeFirst(["laravel-admin::fields.{$type}", 'laravel-admin::fields.text'])
        	
        	@error($name)
                <div class="help-block">
                    <ul role="alert">
                        <li>{{ $message }}</li>
                    </ul>
                </div>
            @enderror
        </div>
    </div>
@endif