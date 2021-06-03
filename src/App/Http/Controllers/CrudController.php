<?php

namespace Topdot\Admin\App\Http\Controllers;

use Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Topdot\Media\App\Models\TempMedia;

class CrudController extends Controller
{
	protected $view_base;
	protected $route_base;
	protected $entery;
	protected $model;
	protected $request;
	protected $table_columns = [];
	protected $raw_columns = [];
	protected $form_fields = [];

	protected function resolveItem($id)
	{
		return app($this->model)->resolveRouteBinding($id);
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

    public function editImageColumn($item, $name = 'image'){
    	return render_table_cell_image($item->getImageUrl($name));
    }

	protected function datatables($raw_columns = [])
	{
		$raw_columns = array_unique(array_merge($raw_columns, $this->raw_columns ?? [], ['actions']));

		$data = datatables()->of($this->model::query());

        foreach ($this->table_columns as $column) {
        	$name = $column['name'] ?? $column['data'];
        	$methodName = Str::studly($name);
        	$methodName = "edit{$methodName}Column";

        	if(method_exists($this, $methodName)){
        		$data->editColumn($name, [$this, $methodName]);
        	}

        	if($column['raw'] ?? false){
        		$raw_columns[] = $name;
        	}
        }

        return $data->rawColumns(array_unique($raw_columns))->make(true);
	}

	protected function getFormFields($item){
		return method_exists($this, 'form_fields') ? $this->form_fields($item) : ($this->form_fields ?? []);
	}

	protected function getTableColumns(){
		$table_columns = method_exists($this, 'table_columns') ? $this->table_columns() : ($this->table_columns ?? []);

		foreach ($table_columns as &$column) {
			if($column['name'] == 'actions' || $column['data'] == 'actions'){
				$column['searchable'] = false;
			}
		}

		return $table_columns;
	}

	protected function view_data($extra = [])
	{
		$item = $extra['item'] ?? null;
		$entery =  Str::of($this->entery);
		$data = [
			'view_base' => $this->view_base,
			'route_base' => $this->route_base,
			'entery' => $entery,
			'entery_plural' => $entery->plural(),
			'model' => $this->model,
			'request' => $this->request,
			'table_columns' => $this->getTableColumns($item),
			'raw_columns' => $this->raw_columns,
			'form_fields' => $this->getFormFields($item),
		];

		return array_merge($data, $extra ?? []);
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

	    return view("{$this->view_base}.list", $this->view_data());
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Category  $category
	 * @return \Illuminate\Http\Response
	 */
	public function show($item)
	{
		$item = $this->resolveItem($item);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	    return view("{$this->view_base}.form", $this->view_data(['editing_form' => false]));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Category  $category
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
	 * @param  int  $item
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
	 * @param  \App\Models\Category  $category
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

	    $item->fill($this->data($request));

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
}
