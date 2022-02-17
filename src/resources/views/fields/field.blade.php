@php
	$field['title'] = title_case($field['title'] ?? $field['name']);
	$field['type'] = $field['type'] ?? 'text';
	$field['enabled'] = isset($field['enabled']) ? $field['enabled'] : true;
	$field['advance'] = isset($field['advance']) ? $field['advance'] : false;
	$field['default_value'] = $field['default'] ?? null;

	if($field['type'] == 'status'){
		$field['title'] = 'Status';
	} 
    
    if($field['type'] == 'select'){
        $field['placeholder'] = $field['placeholder'] ?? 'Select ' . suggest_a_an($field['title']) . ' ' . strtolower($field['title']);
    }else{
        $field['placeholder'] = $field['placeholder'] ?? $field['title'];
    }

	extract($field);
@endphp

@if($enabled)
    <div class="col-{{ $cols ?? 12 }}">
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
    </div>
@endif