<?php

namespace Topdot\Admin\App\Http\Controllers;

use Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Topdot\Media\App\Models\TempMedia;

class CrudController extends Controller
{
	protected $view_base = 'laravel-admin::crud';
	protected $route_base;

	protected $entery;
	protected $menu_active;
	protected $page_title;
	protected $breadcrumbs = [];

	protected $model;
	protected $request;

	protected $table_columns = [];
	protected $raw_columns = [];
	protected $form_fields = [];

	protected function resolveItem($id)
	{
		$item = app($this->model)->resolveRouteBinding($id);

		request()->merge([
			'_item' => $item,
		]);

		return $item;
	}

	public function editCategoryIdColumn($item)
	{
	    return $item->category->name ?? '';
	}

	public function editStatusColumn($item)
	{
	    return $item->status ? 'Active' : 'Inactive';
	}

	public function editActionsColumn($item)
	{
        $edit = '<a href="'. route("{$this->route_base}.edit", $item->id) .'" class="edit btn btn-primary btn-sm mt-1">&nbsp; Edit &nbsp;</a>';
        $delete = '<a href="javascript:void(0)" data-action_button data-action="remove" data-action_url="'. route("{$this->route_base}.destroy", $item->id) .'" class="btn btn-danger btn-sm mt-1">Delete</a>';
    	
    	return $this->extraActions([$edit, $delete], $item);
    }

    protected function extraActions($actions, $item){
    	return implode(' ', $actions);
    }

    public function imageColumn($item, $name = 'image'){
    	return render_table_cell_image($item->getImageUrl($name));
    }

	protected function datatables($raw_columns = [])
	{
		$raw_columns = array_merge($raw_columns, $this->raw_columns ?? []);

		$datatable = datatables()->of($this->model::query());

        foreach ($this->table_columns as $column) {
        	$type = $column['type'] ?? 'text';

        	$name = $column['name'] ?? $column['data'];
        	$methodName = Str::studly($name);
        	$methodName = "edit{$methodName}Column";

        	if(method_exists($this, $methodName)){
        		$methodName = $methodName;
        	}else if($type == 'image'){
        		$methodName = 'imageColumn';
        		$column['raw'] = true;
        	}else{
        		$methodName = null;
        	}

        	$datatable->editColumn($name, function($item) use ($name, $column, $methodName){
	        	if($methodName){
	        		$value = $this->{$methodName}($item, $name, $column);
	        	}else{
	        		$value = $item->{$name};

	        		if(!empty($limit = ($column['character_limit'] ?? null)) && is_numeric($limit)){
	        			$value = truncate_text($value, $limit);
	        		}
	        	}

	        	return $value;
        	});

        	if($column['raw'] ?? false){
        		$raw_columns[] = $name;
        	}
        }
        $raw_columns[] = 'actions';

        $datatable->rawColumns(array_unique($raw_columns));

        return $datatable->toJson();
	}

	protected function getFormFields($item){
		return method_exists($this, 'form_fields') ? $this->form_fields($item) : ($this->form_fields ?? []);
	}

	protected function getTableColumns(){
		$table_columns = method_exists($this, 'table_columns') ? $this->table_columns() : ($this->table_columns ?? []);

		foreach ($table_columns as &$column) {
			if($column['name'] == 'actions' || $column['data'] == 'actions' || ($column['type'] ?? 'text') == 'image'){
				$column['searchable'] = false;
			}
		}

		return $table_columns;
	}

	protected function getBreadcrumbs($item, $options = []){
		extract($options);

		$baseBreadcrumbs = method_exists($this, 'baseBreadcrumbs') ? $this->baseBreadcrumbs($item, $options) : ($this->baseBreadcrumbs ?? []);

		if(method_exists($this, 'breadcrumbs')){
			$breadcrumbs = $this->breadcrumbs($item, $options);
		}else if(!empty($this->breadcrumbs) && is_array($this->breadcrumbs)){
			$breadcrumbs = $this->breadcrumbs;
		}else{
			$breadcrumbs = [
				(string)$entery_plural => route("{$this->route_base}.index"),
			];

			if($type == 'form'){
				$breadcrumbs[$editing_form ? 'Modify' : 'Add new'] = null;
			}
		}

		return array_merge($baseBreadcrumbs, $breadcrumbs);
	}

	protected function view_data($extra = [])
	{
		$item = $extra['item'] ?? null;
		$type = $extra['type'] ?? 'form';

		$editing_form = $extra['editing_form'] ?? false;

		$entery =  Str::of($this->entery);
		$entery_plural = $entery->plural();

		$create_link = $this->getCreateLinkData();

		$data = [
			'view_base' => $this->view_base,
			'route_base' => $this->route_base,

			'entery' => $entery,
			'entery_plural' => $entery_plural,

			'model' => $this->model,
			'request' => $this->request,

			'menu_active' => $this->menu_active ?? $entery_plural->snake(),
			'page_title' => $this->page_title ?? $entery,
			'breadcrumbs' => $this->getBreadcrumbs($item, compact('type', 'editing_form', 'entery', 'entery_plural')),

			'type' => $type,

			'links' => (object)[
				'create' => $create_link ? (object)$create_link : null,
			],
		];

		if($type == 'details'){

		}else if($type == 'form'){
			$specific_data = [
				'editing_form' => $editing_form,
				'form_fields' => $this->getFormFields($item),
			];
		}else{
			$specific_data = [
				'table_columns' => $this->getTableColumns($item),
				'raw_columns' => $this->raw_columns,
			];
		}

		return array_merge($data, $specific_data, $extra ?? []);
	}

	protected function getCreateLinkData(){
		return [
			'text' => 'Add new',
			'link' => route("{$this->route_base}.create"),
		];
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
	    if ($request->ajax()) {
	        return $this->datatables();
	    }

	    return view("{$this->view_base}.list", $this->view_data(['type' => 'listing']));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  string|int  $item
	 * @return \Illuminate\Http\Response
	 */
	public function show($item)
	{
		$item = $this->resolveItem($item);
		return view("{$this->view_base}.details", $this->view_data(['item' => $item, 'type' => 'details']));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	    return view("{$this->view_base}.form", $this->view_data());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param   string|int  $item
	 * @return \Illuminate\Http\Response
	 */
	public function edit($item)
	{
		$item = $this->resolveItem($item);

	    return view("{$this->view_base}.form", $this->view_data(['item' => $item, 'editing_form' => true]));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
	    return $this->fill_and_save(app($this->request), app($this->model));
	}

	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param   string|int  $item
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $item)
	{		
		$item = $this->resolveItem($item);
	    return $this->fill_and_save(app($this->request), $item);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param   string|int  $item
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($item)
	{
	    $this->resolveItem($item)->delete();
	    return redirect()->back()->with(['status' => 1, 'message' => "{$this->entery} deleted successfully"]);
	}

	protected function setFilesField($item, $name){
		$tempMediaToBeDel = [];

		foreach (TempMedia::find(request($name, [])) as $tempMedia) {
		    $tempMedia->getFirstMedia('default')->move($item, $name);

		    $tempMediaToBeDel[] = $tempMedia->id;
		}

		if(count($tempMediaToBeDel)){
			TempMedia::whereIn('id', $tempMediaToBeDel)->delete();
		}
	}


	protected function fill_and_save(Request $request, $item, $save = true, $redirect = true){
	    // dd($this->data($request));

		if(method_exists($this, 'beforeFill')){
			$this->beforeFill($item);
		}

		$dataMethod = method_exists($this, 'data') ? 'data' : 'dataFromFields';

	    $item->fill($this->{$dataMethod}($request, $item));

	    if(method_exists($this, 'afterFill')){
			$this->afterFill($item);
		}

	    if($save){
		    if(method_exists($this, 'beforeSave')){
				$this->beforeSave($item);
			}

	        $saved = $item->save();

	        foreach ($this->getFormFields($item) as $field) {
	        	$type = $field['type'] ?? 'text';
	        	$name = $field['name'] ?? 'image';

	        	$mehtod = $name;
	        	$mehtod = Str::studly($mehtod);
	        	$mehtod = "set{$mehtod}FieldData";

	        	if(method_exists($this, $mehtod)){
	        		$this->{$mehtod}($item, $name);
	        	}else if($type == 'file' || $type == 'image'){
	        		$this->setFilesField($item, $name);
	        	}
	        }

			if(method_exists($this, 'afterSave')){
				$this->afterSave($item);
			}

	        if($redirect){
	            $route = $item->wasRecentlyCreated  ? 'index' : 'edit';

	            return redirect()->route("{$this->route_base}.{$route}", $item->id)->with(['status' => 1, 'message' => "{$this->entery} Saved successfully"]);
	        }

	        return $saved;
	    }

	    return true;
	}


	protected function dataFromFields(Request $request, $item){
		$fields = $this->getFormFields($item);

		$data = [];

		foreach ($fields as $key => $field) {
			$name = $field['name'];
			$type = $field['type'] ?? 'text';

			if($type != 'image'){
				$data[$name] = $request->{$name};

				if($type == 'status'){
					$data[$name] = !!$data[$name];
				}
			}

		}

		return $data;
	}
}
