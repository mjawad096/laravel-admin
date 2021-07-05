<div class="form-group {{ $errors->has($name) ? 'error' : '' }}">
    <label for="input-{{ $name }}">{{ $title }}</label>
    <div class="controls">
        <textarea type="text" id="input-{{ $name }}" class="form-control rich-editor{{ $advance ? '-advance' : '' }}" name="{{ $name }}" placeholder="{{ $title }}">{{ old($name) ?? $item->{$name} ?? $default_value }}</textarea>
        
        @error($name)
            <div class="help-block">
                <ul role="alert">
                    <li>{{ $message }}</li>
                </ul>
            </div>
        @enderror
    </div>
</div>

@pushonce('js')
    <script src="https://cdn.tiny.cloud/1/plvfgap34uuvzkaly2pp6oeweszgvb15ra0q9ff7jqvwrmdv/tinymce/4/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            tinymce.init({
                selector: '.rich-editor',
                branding: false,
                plugins: 'code link',
                menubar: false,
                toolbar: 'undo redo | styleselect link | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | code',
            });

            tinymce.init({
                selector: '.rich-editor-advance',
                branding: false,
                plugins: 'code link image lists advlist',
                menubar: false,
                toolbar: 'undo redo | styleselect | link image | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | code',
                convert_urls: false,
                images_upload_url: '/media',
            });
        });
    </script>
@endpushonce