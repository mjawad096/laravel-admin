<textarea type="text" id="input-{{ $name }}" class="form-control" name="{{ $name }}" placeholder="{{ $placeholder }}">{{ old($name) ?? $item->{$name} ?? $default_value }}</textarea>