<?php

namespace Dotlogics\Admin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $fields = [];

    protected function getFieldGroups()
    {
        if(method_exists($this, 'fields')){
            $fields = $this->fields();
        }else{
            $fields = $this->fields;
        }

        return collect($fields);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('laravel-admin::settings', ['field_groups' => $this->getFieldGroups()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $fields = $this->getFieldGroups()->pluck('fields')->flatten(1);

        $rules = [];
        $names = [];
        foreach ($fields as $field) {
            $field_name = $field['name'];
            $field_label = $field['title'];
            $field_rules = $field['rules'] ?? '';

            $rules[$field_name] = $field_rules;
            $names[$field_name] = strtolower($field_label);
        }

        $request->validate($rules, [], $names);

        if($request->truncate){
            $settings = setting()->all();
            foreach ($settings as $key => $value) {
                setting()->forget($key);
            }
        }        

        $settings = [];
        foreach ($fields as $field) {
            $value = $request->get($field['name']) ?? '';
            $key = str_replace('-', '.', $field['name']);

            $settings[$key] = $value;
        }

        setting($settings)->save();

        return redirect()->back()->with(['status' => 1, 'message' => 'Settings saved successfully']);
    }
}
