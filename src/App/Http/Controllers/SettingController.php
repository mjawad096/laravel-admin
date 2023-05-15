<?php

namespace Dotlogics\Admin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    protected $fields = [];

    protected function getFieldGroups()
    {
        if (method_exists($this, 'fields')) {
            $fields = $this->fields();
        } else {
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $fields = $this->getFieldGroups()->pluck('fields')->flatten(1);

        $rules = [];
        $names = [];
        $messages = [];
        foreach ($fields as $field) {
            $field_name = $field['name'];
            $field_label = $field['title'];
            $field_rules = $field['rules'] ?? '';
            $field_rule_messages = $field['rule_messages'] ?? [];

            $rules[$field_name] = $field_rules;
            $names[$field_name] = strtolower($field_label);

            collect($field_rule_messages)
                ->each(function ($message, $key) use ($field_name, &$messages) {
                    $messages["{$field_name}.{$key}"] = $message;
                });
        }

        $request->validate($rules, $messages, $names);

        if ($request->truncate) {
            $settings = setting()->all();
            foreach ($settings as $key => $value) {
                setting()->forget($key);
            }
        }

        $settings = [];
        foreach ($fields as $field) {
            $name = $field['name'];

            $value = $request->get($name) ?? '';
            $key = str_replace('-', '.', $name);

            $mehtod = Str::studly($name);
            $mehtod = "set{$mehtod}FieldData";

            if (method_exists($this, $mehtod)) {
                $value = $this->{$mehtod}($field, $key, $value);
            }

            $settings[$key] = $value;
        }

        setting($settings)->save();

        return redirect()->back()->with(['status' => 1, 'message' => 'Settings saved successfully']);
    }
}
