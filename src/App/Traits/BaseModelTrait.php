<?php

namespace Dotlogics\Admin\App\Traits;

trait BaseModelTrait
{
    public static function asOptionArray($field_text = null, $field_value = null, $query = null, callable $callback = null)
    {
        $query = $query ?? self::activeOnly();

        if (! empty($callback)) {
            $query->where($callback);
        }

        return toOptionArray($query->get(), $field_text, $field_value);
    }

    protected function scopeActiveOnly($query)
    {
        return $query->where('status', 1);
    }

    public function getImageUrl($field = null, $media = null)
    {
        if (is_null($media)) {
            $media = $this->getFirstMedia($field);
        }

        if (empty($media)) {
            return null;
        }

        return $media->getFullUrl();
    }

    public function getFilesUrl($field)
    {
        return $this->getMedia($field)->map(function ($media) {
            return $media->getFullUrl();
        });
    }

    public function next(callable $callback = null)
    {
        $query = self::where('id', '>', $this->id);

        if (! empty($callback)) {
            $query->where($callback);
        }

        $query->oldest('id');

        return $query->first();
    }

    public function previous(callable $callback = null)
    {
        $query = self::where('id', '<', $this->id);

        if (! empty($callback)) {
            $query->where($callback);
        }

        $query->latest('id');

        return $query->first();
    }
}
