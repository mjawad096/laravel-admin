@extends('admin.layouts.list_base', [
    'menu_active' => $entery_plural->lower(),
    'page_title' => 'Project Management',
    'breadcrumbs' => [
        'Projects' => route('admin.projects.index'),
        (string)$entery_plural => route("{$route_base}.index"),
    ],
])