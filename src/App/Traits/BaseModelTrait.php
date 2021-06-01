<?php

namespace Topdot\Admin\App\Traits;

use Str;
use Storage;

trait BaseModelTrait
{
    static public function asOptionArray($field_text = null, $field_value = null, $query = null, Callable $callback = null){
        $query = $query ?? self::activeOnly();

        if(!empty($callback)){
            $query->where($callback);
        }

    	return toOptionArray($query->get(), $field_text, $field_value);
    }

    protected function scopeActiveOnly($query){
        return $query->where('status', 1);
    }

    public function getImageUrl($field = null, $media = null){
        if(!is_null($media)){
            return route('media.show', $media);
        }

        return $this->hasMedia($field) ? route('media.show', $this->getFirstMedia($field)) : null;
    }

    public function getFilesUrl($field){
        return $this->getMedia($field)->map(function($media){
            return route('media.show', $media);
        });
    }

    public function next(Callable $callback = null){
        $query = self::where('id', '>', $this->id);

        if(!empty($callback)){
        	$query->where($callback);
        }

        $query->oldest('id');

        return $query->first();

    }
    
    public  function previous(Callable $callback = null){
        $query = self::where('id', '<', $this->id);

        if(!empty($callback)){
        	$query->where($callback);
        }

        $query->latest('id');

        return $query->first();
    }
}
