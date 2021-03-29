<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Courses;
use App\CustomFields;
use App\CustomFieldValue;
use App\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $default['sort_field'] = 'title';
        $default['sort_dir'] = 'asc';

        if($request->sort_field && $request->sort_dir)
        {
            $courses_filter['sort_field'] = $request->sort_field;
            $courses_filter['sort_dir'] = $request->sort_dir;
            session(['courses_filter' => $courses_filter]);
        }

        $session_filter = session('courses_filter');
        if($session_filter['sort_field'] && $session_filter['sort_dir'])
        {
            $filter['sort_field'] = $session_filter['sort_field'];
            $filter['sort_dir'] = $session_filter['sort_dir'];
        }
        else
        {
            $filter['sort_field'] = $default['sort_field'];
            $filter['sort_dir'] = $default['sort_dir'];
        }

        $coursesQuery = Courses::Query();
        $coursesQuery->orderBy($filter['sort_field'],$filter['sort_dir']);
        $courses = $coursesQuery->get();

        return view('course.list', array('courses' => $courses, 'filter' => $filter));
    }

    public function create()
    {
        return view('course.create', [        
            'custom_fields' => CustomFields::where('data_model', 'Courses')->get(), 
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(Courses::get_store_validate_params());

        try {
        	$image_name = null;
            if($request->image) {
                $feature_image = (new File())->setFile($request->image)
                                             ->setPath('course/')
                                             ->setName();
                $image_name = $feature_image->store() ? $feature_image->getName() : null;
            }

        	$course = new Courses([
	            'title' => $request->get('title'),
                'description'=> $request->get('description'),
                'objectives'=> $request->get('objectives'),
	            'thumbnail' => $image_name
	        ]);

	        $course->save();
             
            $custom_fields = CustomFields::where('data_model', 'Courses')->get();
            if ($custom_fields) {
                foreach ($custom_fields as $custom_field) {
                    if ( !empty($request->{'custom_'.$custom_field->field_name}) ) {
                        CustomFieldValue::create([
                            'custom_field_id' => $custom_field->id,
                            'model_id' => $course->id,
                            'field_value' => $request->{'custom_'.$custom_field->field_name}
                        ]);
                    }
                }
            }

	        return redirect('/course/list')->with('success', __('messages.addcoursesuccessfully'));
        } catch(\Exception $e) {
        	return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $course = Courses::find($id);
        $add_lesson_form_html = (string)View::make('course.unit.lesson.edit-fields', [
            'custom_fields' => CustomFields::where('data_model', 'Lessons')->get()
            ]);
        return view('course.details', array(
                'course' => $course,
                'custom_fields' => CustomFields::where('data_model', 'Courses')->get(), 
                'add_lesson_form_html' => $add_lesson_form_html
            ));
    }

    public function edit($id)
    {
        $course = Courses::find($id);

        return view('course.edit', array('course' => $course,
            'custom_fields' => CustomFields::where('data_model', 'Courses')->get(), 
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate(Courses::get_update_validate_params($id));

        try {
        	$image_name = null;
            if($request->update_image == 'true' && $request->image) {
                $feature_image = (new File())->setFile($request->image)
                                             ->setPath('course/')
                                             ->setName();
                $image_name = $feature_image->store() ? $feature_image->getName() : null;
            }

        	$course = Courses::find($id);
	        $course->title = $request->get('title');
            $course->description = $request->get('description');
            $course->objectives = $request->get('objectives');
	        if($image_name) {
	        	$course->thumbnail = $image_name;
	        }

            $course->save();
            
            $custom_fields = CustomFields::where('data_model', 'Courses')->get();
            if ($custom_fields) {
                foreach ($custom_fields as $custom_field) {
                    $field = CustomFieldValue::where('model_id', $course->id)->where('custom_field_id', $custom_field->id)->first();
                    if ($field) {
                        if ( !empty($request->{'custom_'.$custom_field->field_name}) ) {
                            $field->field_value = $request->{'custom_'.$custom_field->field_name};
                            $field->save();
                        } else {
                            $field->delete();
                        }
                    } else {
                        if ( !empty($request->{'custom_'.$custom_field->field_name}) ) {
                            CustomFieldValue::create([
                                'custom_field_id' => $custom_field->id,
                                'model_id' => $course->id,
                                'field_value' => $request->{'custom_'.$custom_field->field_name}
                            ]);
                        }
                    }
                }
            }


	        return redirect('/course/list')->with('success', __('messages.updatecoursesuccessfully'));
        } catch(\Exception $e) {
        	return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $course = Courses::find($id);

        foreach($course->lessons as $lesson) {
            // Delete Lesson Files
            foreach($lesson->files as $lessonFile)
            {
                if ($lessonFile->section != 5)
                    @Storage::disk('public')->delete($lessonFile->file_path);
                $lessonFile->delete();
            }
        }
        $course->delete();

        return redirect('/course/list')->with('success', __('messages.deletecoursesuccessfully'));
    }

    public function units(Request $request)
    {
        $course = Courses::find($request->id);
        $open_sections = (array)$request->open_sections;
        return view('course.units', array(
            'course' => $course,
            'open_sections' => $open_sections,
        ));
    }

    public function reorderUnitsForm($course_id)
    {
        $course = Courses::find($course_id);
        return view('course.units-reorder', compact('course'));
    }

    public function reorderUnitsSave($course_id, Request $request)
    {
        $course = Courses::find($course_id);

        $unit_ids = (array)$request->unit_ids;
        foreach($unit_ids as $key => $unit_id)
        {
            $course->units()->where('id',$unit_id)->update(['position' => $key]);
        }

        $out = array();
        $out['status'] = 1;
        $out['message'] = __('messages.units-reordered-successfully');
        return $out;
    }
}
