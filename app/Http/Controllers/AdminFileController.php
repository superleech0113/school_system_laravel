<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AdminFile;
use App\AdminFileCategory;
use Illuminate\Support\Facades\Storage;

class AdminFileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin_files.index', ['files' => AdminFileCategory::all()]);
    }

    public function create()
    {
        return view('admin_files.create');
    }

    public function uploadFile(Request $request, $category_id)
    {
        try {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $file_path = Storage::disk('public')->putFileAs('admin_files', $file, (\Auth::user()->id.time().'__').$fileName);
            $adminFile = new AdminFile();
            $adminFile->file_path = $file_path;
            $adminFile->category_id = $category_id;
            $adminFile->file_name = $fileName;
            $adminFile->save();

            return response()->json(['name' => $fileName]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function deleteFile(Request $request, $id)
    {
        $adminFile = AdminFile::find($id);
        if($adminFile)
        {
            @Storage::disk('public')->delete($adminFile->file_path);
            $adminFile->delete();
        }
        if ($request->ajax()) {
            return response()->json(['success' => __('messages.deletefilesuccessfully')]);
        } else {
            return redirect('/files')->with('success', __('messages.deletefilesuccessfully'));
        }
    }

    public function saveCategory(Request $request)
    {
        try {
            $id = $request->get('category_id');
            if (!empty($id)) {
                $adminFileCategory = AdminFileCategory::find($id);
            } else {
                $adminFileCategory = new AdminFileCategory();
            }
            $adminFileCategory->name = $request->get('category_name');
            $adminFileCategory->save();

            return response()->json(['status' => 1, 'message' =>  __('messages.addfilecategorysuccessfully'), 'category' => $adminFileCategory]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getCategoryFiles($id)
    {
        return AdminFileCategory::find($id)->the_files_url();
    }

}
