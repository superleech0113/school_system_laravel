<?php

namespace App\Http\Controllers;

use App\CustomFields;
use App\CustomFieldValue;
use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
use DB;
use App\Applications;
use App\ApplicationFile;
use App\File;
use App\FormOrders;
use App\Http\Requests\ApplicationRequest;
use App\Role;
use App\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class ApplicationController extends Controller
{
    public function application()
    {
        return view('applications.create', [
            'class_student_levels' => explode(',', Settings::get_value('class_student_levels')),
            'fields' => FormOrders::where('data_model', 'Applications')->where('is_visible', true)->orderBy('sort_order')->get()
        ]);
      
    }

    public function applicationSave(ApplicationRequest $request)
    {
        try {
            $application = $this->saveApplication($request);
            if (!empty($request->get('exit'))) {
                return redirect('/')->with('success', __('messages.application-has-been-added'));
            } else {
                return redirect()->route('application.docs',['application_no' => base64_encode($application->application_no)]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $date = Carbon::now(CommonHelper::getSchoolTimezone())->format('Y-m-d');

        $default['sort_field'] = 'application_no';
        $default['sort_dir'] = 'desc';
        $default['is_student'] = 'false';

        $applications_filter = session('applications_filter');
        if($request->sort_field && $request->sort_dir)
        {
            $applications_filter['sort_field'] = $request->sort_field;
            $applications_filter['sort_dir'] = $request->sort_dir;
            session(['applications_filter' => $applications_filter]);
        }
        if($request->is_student)
        {
            $applications_filter['is_student'] = $request->is_student;
            session(['applications_filter' => $applications_filter]);
        }

        $session_filter = session('applications_filter');
        if(isset($session_filter['sort_field']) && isset($session_filter['sort_dir']) && $session_filter['sort_field'] && $session_filter['sort_dir']) {
            $filter['sort_field'] = $session_filter['sort_field'];
            $filter['sort_dir'] = $session_filter['sort_dir'];
        } else {
            $filter['sort_field'] = $default['sort_field'];
            $filter['sort_dir'] = $default['sort_dir'];
        }
      
        if(isset($session_filter['is_student']) && $session_filter['is_student']) {
            $filter['is_student'] = $session_filter['is_student'];
        } else {
            $filter['is_student'] = $default['is_student'];
        }

        $applications = Applications::selectRaw("*, CONCAT(firstname,' ',lastname) as fullname")->orderBy($filter['sort_field'], $filter['sort_dir']);
        if($filter['is_student'] == 'true') {
            $applications->whereNotNull('student_id');
        } else if($filter['is_student'] == 'false') {
            $applications->whereNull('student_id');
        } 
        $applications = $applications->get();
        
        return view('applications.list', array(
            'applications' => $applications,
            'filter' => $filter,
            'date' => $date,
        ));
    }

    public function create()
    {
        return view('applications.create', [
            'class_student_levels' => explode(',', Settings::get_value('class_student_levels')),
            'custom_fields' => CustomFields::where('data_model', 'Applications')->get()
        ]);
    }

    public function store(ApplicationRequest $request)
    {
        try {
            $application = $this->saveApplication($request);
            if (!empty($request->get('exit'))) {
                return redirect('/')->with('success', __('messages.application-has-been-added'));
            } else {
                return redirect()->route('application.docs',['application_no' => base64_encode($application->application_no)]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function saveApplication($request)
    {
        $imageName = null;
        if ($request->image) {
            $applicationImage = (new File())->setFile($request->image)
            ->setPath('application/')
            ->setName(pathinfo($request->image->getClientOriginalName(), PATHINFO_FILENAME));
            $imageName = $applicationImage->store() ? $applicationImage->getName() : null;
        }

        $app_no = Applications::orderBy('id', 'desc')->first(); 
        $application = Applications::create([
            'application_no' => empty($app_no) ? Settings::get_value('application_series') : ($app_no->application_no+1),
            'lastname' => $request->lastname ?? '',
            'firstname' => $request->firstname ?? '',
            'lastname_kanji' => $request->lastname_kanji ?? ($request->lastname ?? ''),
            'firstname_kanji' => $request->firstname_kanji ?? ($request->firstname ?? ''),
            'lastname_furigana' => $request->lastname_furigana ?? ($request->lastname ?? ''),
            'firstname_furigana' => $request->firstname_furigana ?? ($request->firstname ?? ''),
            'home_phone' => $request->home_phone ?? '',
            'mobile_phone' => $request->mobile_phone ?? '',
            'email' => $request->email,
            'status' => 1,
            'join_date' => !empty($request->join_date) ? date('Y-m-d', strtotime($request->join_date)) : null,
            'address' => $request->address ?? '',
            'toiawase_referral' => $request->toiawase_referral ? $request->toiawase_referral : '',
            'toiawase_houhou' => $request->toiawase_houhou ? $request->toiawase_houhou : '',
            'toiawase_date' => Carbon::now(CommonHelper::getSchoolTimezone())->format('Y-m-d'),
            'birthday' => !empty($request->birthday) ? date('Y-m-d', strtotime($request->birthday)) : null,
            'comment' => $request->comment ?? '',
            'levels' => !empty($request->levels) ? implode(",",$request->levels) : '',
            'office_name' => $request->office_name ?? '',
            'office_address' => $request->office_address ?? '',
            'office_phone' => $request->office_phone ?? '',
            'school_name' => $request->school_name ?? '',
            'school_address' => $request->school_address ?? '',
            'school_phone' => $request->school_phone ?? '',
            'lang' => \App::getLocale(),
            'image' => $imageName
        ]);
        
        $custom_fields = CustomFields::where('data_model', 'Applications')->get();
        if ($custom_fields) {
            foreach ($custom_fields as $custom_field) {
                if ( !empty($request->{'custom_'.$custom_field->field_name}) ) {
                    CustomFieldValue::create([
                        'custom_field_id' => $custom_field->id,
                        'model_id' => $application->id,
                        'field_value' => $request->{'custom_'.$custom_field->field_name}
                    ]);
                }
            }
        }
        return $application;
    }

    public function applicationDocs($application_no)
    {
        $application_no = base64_decode($application_no);
        $application = Applications::where('application_no', $application_no)->first();
        if (!empty($application->student)) {
            return redirect()->route('login');
        }
        if (!empty($application->docs) && count($application->docs) > 0) {
            return view('applications.thanks',['application' => $application]);
        }
        return view('applications.docs', [
            'application' => $application
        ]);
    }
    
    public function completeApplication($application_no)
    {
        $application_no = base64_decode($application_no);
        $application = Applications::where('application_no', $application_no)->first();
        if (!empty($application->student_id)) {
            return redirect()->route('login');
        }
        NotificationHelper::sendNewApplicationNotification($application);
            
        return view('applications.thanks', array(
            'application' => $application, 
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = Applications::find($id);
 
        return view('applications.details', array(
            'application' => $application, 
            'custom_fields' => CustomFields::where('data_model', 'Applications')->get(), 
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('applications.edit', array(
            'application' => Applications::find($id), 
            'class_student_levels' => explode(',', Settings::get_value('class_student_levels')),
            'fields' => FormOrders::where('data_model', 'Applications')->where('is_visible', true)->orderBy('sort_order')->get()
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ApplicationRequest $request, $id)
    {
        try {
            $application = Applications::find($id);
            $application->lastname = $request->get('lastname') ?? '';
            $application->firstname = $request->get('firstname') ?? '';
            $application->lastname_kanji = $request->get('lastname_kanji') ?? ($request->get('lastname') ?? '');
            $application->firstname_kanji = $request->get('firstname_kanji') ?? ($request->get('firstname') ?? '');
            $application->lastname_furigana = $request->get('lastname_furigana') ?? ($request->get('lastname') ?? '');
            $application->firstname_furigana = $request->get('firstname_furigana') ?? ($request->get('firstname') ?? '');
            $application->home_phone = $request->get('home_phone');
            $application->mobile_phone = $request->get('mobile_phone');
            $application->levels = !empty($request->levels) ? implode(",",$request->levels) : '';
            
            $application->office_name = $request->office_name;
            $application->office_address = $request->office_address;
            $application->office_phone = $request->office_phone;
            $application->school_name = $request->school_name;
            $application->school_address = $request->school_address;
            $application->school_phone = $request->school_phone;
          
            if(!empty($request->get('address'))) {
                if($application->address != $request->get('address')) {
                    $application->address = $request->get('address');
                }
            }
            if(!empty($request->get('join_date'))) {
                $application->join_date = $request->get('join_date');
            }

            if(!empty($request->get('birthday'))) {   
                $application->birthday = $request->get('birthday');
            }

            if(!empty($request->get('toiawase_referral'))) {
                $application->toiawase_referral = $request->get('toiawase_referral');
            }
            if(!empty($request->get('toiawase_memo'))) {
                $application->toiawase_memo = $request->get('toiawase_memo');
            }
            $application->email = $request->get('email');
           
            $application->save();

            $custom_fields = CustomFields::where('data_model', 'Applications')->get();
            if ($custom_fields) {
                foreach ($custom_fields as $custom_field) {
                    $field = CustomFieldValue::where('model_id', $application->id)->where('custom_field_id', $custom_field->id)->first();
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
                                'model_id' => $application->id,
                                'field_value' => $request->{'custom_'.$custom_field->field_name}
                            ]);
                        }
                    }
                }
            }

            return redirect('/applications/'.$id)->with('success', __('messages.application-has-been-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $application = Applications::find($id);
        
        CustomFieldValue::where('model_id', $application->id)
                        ->whereHas('field', function($query){
                            $query->where('data_model', 'Applications');
                        })->delete();

        $application->delete();
        
        return redirect('/applications')->with('success', __('messages.application-has-been-deleted-successfully'));
    }

   
    public function search(Request $request)
    {
        $applicationsQyuery = Applications::Query();

        $applicationsQyuery->where(function($query) use ($request){
            $query->where('firstname', 'LIKE', "%{$request->search}%");
            $query->orWhere('lastname', 'LIKE', "%{$request->search}%");
            $query->orWhere('home_phone', 'LIKE', "%{$request->search}%");
            $query->orWhere('mobile_phone', 'LIKE', "%{$request->search}%");
            $query->orWhere('email', 'LIKE', "%{$request->search}%");
        });

        $applications = $applicationsQyuery->get();

        return view('applications.search', array(
            'applications' => $applications,
        ));
    }

   
    public function convertToStudent(Request $request, $id)
    {
        $application = Applications::findOrFail($id);
        if(!empty($application->student_id))
        {
            $message = __('messages.application-is-already-converted');
            return redirect()->back()->with('error', $message);
        }
        $teachers = DB::table('teachers')->where('status','=',0)->get();
        return view('student.application', array(
            'application' => $application, 'teachers' => $teachers,
            'class_student_levels' => explode(',', Settings::get_value('class_student_levels')),
            'custom_fields' => CustomFields::where('data_model', 'Students')->get(), 
            'student_roles' => Role::get_student_roles()
        ));
    }

    public function uploadFile(Request $request, $application_id)
    {
        try {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file_path = Storage::disk('public')->putFileAs('application_files', $file, (time().'__').$fileName);
            $applicationFile = new ApplicationFile();
            $applicationFile->file_path = $file_path;
            $applicationFile->application_id = $application_id;
            $applicationFile->file_name = $fileName;
            $applicationFile->save();
            return response()->json(['name' => $fileName, 'id' => $applicationFile->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function deleteFile(Request $request, $id)
    {
        $applicationFile = ApplicationFile::find($id);
        if($applicationFile)
        {
            @Storage::disk('public')->delete($applicationFile->file_path);
            $applicationFile->delete();
        }
        return response()->json(['success' => __('messages.deletefilesuccessfully')]);
    }
}
