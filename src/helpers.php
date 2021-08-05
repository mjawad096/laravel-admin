<?php

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

function title_case($value = '')
{
	return Str::title(str_replace(['-',  '_'], ' ', $value));
}

function suggest_a_an($value = ''){
	return preg_match('/^[aeiou]/i', $value) ? 'an' : 'a';
}

function toOptionArray(iterable $collection, $field_text = null, $field_value = null){
	if(!($collection instanceof Collection)){
		$collection = collect($collection);
	}

	return $collection->map(function($item) use ($field_text, $field_value){
		if(!is_object($item)){
			if(!is_array($item)){
				$item = ['value' => $item];
			}

			$item = (object)$item;
		}

		if(!empty($field_text)){
			$text = $item->{$field_text} ?? null;
		}else{
			$text = $item->name ?? $item->title ?? $item->text ?? $item->location ?? null;
		}

		if(!empty($field_value)){
			$value = $item->{$field_value} ?? null;
		}else{
			$value = $item->id ?? $item->value ?? null;
		}


		return [
			'text' => $text ?? $value,
			'value' => $value,
		];
	})->filter(function($item){
		return !empty($item['value']);
	});
}

function strip_tags_and_html_entries($text){
	return html_entity_decode(strip_tags($text));
}

function truncate_text($text, $length = 100, $end = '...'){
    return Str::limit(strip_tags_and_html_entries($text), $length, $end);
}

function render_table_cell_image($url){
    if(empty($url)) return '';

    return "
        <a href=\"{$url}\" target=\"_blank\">
            <img width=\"50px\" height=\"50px\" src=\"{$url}\">
        </a>
    ";
}

function toString($value){
	if(empty($value)) return '';

	if(is_string($value)) return $value;

	return json_encode($value);
}