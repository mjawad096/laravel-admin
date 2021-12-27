@php
    $total_files = $editing_form ? $item->getMedia($name)->count() - count(old("deleted_files.{$name}", [])) : null;
@endphp

<livewire:media::temp-file-upload-component :name="$name" :config="['accept' => ($accept ?? ($type == 'image' ? 'image/*' : '*'))]" :maxFiles="$maxFiles ?? 10" :totalFiles="$total_files"/>

@if($editing_form)
    <livewire:media::file-preview-component :model="$item" :collection="$name" />
@endif