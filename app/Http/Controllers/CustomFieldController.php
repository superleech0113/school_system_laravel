<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomFieldRequest;
use App\CustomFields;
use App\FormOrders;
use App\User;
use App\Students;

class CustomFieldController extends Controller
{
    public function index()
    {
        $custom_fields = CustomFields::all();

        return view('custom_field.index', compact('custom_fields'));
    }

    public function create()
    {
        return view('custom_field.create');
    }

    public function store(CustomFieldRequest $request)
    {
        try {
            CustomFields::create([
                'field_name' => $request->field_name,
                'field_label_en' => $request->field_label_en,
                'field_label_ja' => $request->field_label_ja,
                'field_type' => $request->field_type,
                'field_required' => $request->field_required,
                'data_model' => $request->data_model
            ]);
            if (in_array($request->data_model, ['Applications', 'Students'])) {
                CustomFields::create([
                    'field_name' => $request->field_name,
                    'field_label_en' => $request->field_label_en,
                    'field_label_ja' => $request->field_label_ja,
                    'field_type' => $request->field_type,
                    'field_required' => $request->field_required,
                    'data_model' => ($request->data_model == 'Applications' ? 'Students' : 'Applications')
                ]);
            }

            // return redirect()->route('custom-field.index')->with('success', __('messages.custom-field-added-successfully'));
            return response()->json(['status' => 1, 'message' => __('messages.custom-field-added-successfully')]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $custom_field = CustomFields::findOrFail($id);

        return view('custom_field.edit', compact('custom_field'));
    }

    public function update(CustomFieldRequest $request, $id)
    {
        try {
            $custom_field = CustomFields::findOrFail($id);

            $custom_field->update([
                'field_name' => $request->field_name,
                'field_label_en' => $request->field_label_en,
                'field_label_ja' => $request->field_label_ja,
                'field_type' => $request->field_type,
                'field_required' => $request->field_required,
                'data_model' => $request->data_model
            ]);

            return redirect()->route('custom-field.index')->with('success', __('messages.custom-field-updated-successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $custom_field = CustomFields::findOrFail($id);

        $custom_field->delete();

        return redirect()->route('custom-field.index')
            ->with('success', __('messages.custom-field-deleted-successfully'));
    }

    public function createReorder()
    {
        return view('form_order.create');
    }

    public function reorderForm($data_model)
    {
        $filesInFolder = \File::files(base_path('resources/views' ). '/' . FormOrders::DATA_MODEL_FOLDER[$data_model].'/fields');  
        $i = 0;   
        foreach($filesInFolder as $path) { 
            if (explode('.', basename($path))[0] != 'custom_field') {
                $field = FormOrders::where(['data_model' => $data_model, 'field_name' => explode('.', basename($path))[0]])->first();
                if (!$field) {
                    $field = new FormOrders;
                    $field->field_name = explode('.', basename($path))[0];
                    $field->data_model = $data_model;
                    $field->sort_order = $i;
                    $field->is_visible = true;
                    $field->is_custom = false;
                    $field->is_required = false;
                    $field->save();
                }
                $i = $i + 1;
            }
        }

        $custom_fields = CustomFields::where('data_model', $data_model)->get();
        
        foreach($custom_fields as $custom_field) { 
            $field = FormOrders::where(['data_model' => $data_model, 'field_name' => $custom_field->field_name])->first();
            if (!$field) {
                $field = new FormOrders;
                $field->field_name = $custom_field->field_name;
                $field->data_model = $data_model;
                $field->sort_order = $i;
                $field->is_visible = true;
                $field->is_custom = true;
                $field->is_required = $custom_field->field_required;
                $field->save();
            }
            $i = $i + 1;
        }
            
        $visibleFields = FormOrders::where('data_model', $data_model)->orderBy('sort_order')->where('is_visible', true)->get();
        $invisibleFields = FormOrders::where('data_model', $data_model)->orderBy('sort_order')->where('is_visible', false)->get();
        return view('form_order.reorder', compact('visibleFields','invisibleFields'));
    }

    public function reorderSave(Request $request)
    {
        $field_ids = (array)$request->field_ids;
        foreach($field_ids as $key => $field_id)
        {
            FormOrders::where('data_model', $request->data_model)->where('id',$field_id)->update(['sort_order' => $key, 'is_visible' => $request->is_visible[$field_id],
            'is_required' => $request->is_visible[$field_id] ? !empty($request->is_required[$field_id]) : false
            ]);
        }

        return redirect()->route('reorder.form.create')
        ->with('success', __('messages.fields-reordered-successfully'));
    }

}
