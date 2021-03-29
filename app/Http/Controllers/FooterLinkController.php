<?php

namespace App\Http\Controllers;

use App\FooterLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FooterLinkController extends Controller
{
    public function index()
    {
        return view('footer_links.list', [
            'footer_links' => FooterLinks::all()
        ]);
    }

    public function create()
    {
        return view('footer_links.create');
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'label_en' => 'required|string|min:3|max:50',
                'label_ja' => 'required|string|min:3|max:50',
                'link' => 'required',
                'display_order' => 'required|integer|min:0'
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withInput()->withErrors($validation->errors());
            }
            FooterLinks::create([
                'label_en' => $request->label_en,
                'label_ja' => $request->label_ja,
                'link' => $request->link,
                'display_order' => $request->display_order
            ]);

            return redirect('/footer-links')->with('success', __('messages.addfooterlinksuccessfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        return view('footer_links.edit', [
            'footer_link' => FooterLinks::find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'label_en' => 'required|min:3|max:50',
                'label_ja' => 'required|min:3|max:50',
                'link' => 'required',
                'display_order' => 'required|min:0|max:5'
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withInput()->withErrors($validation->errors());
            }
            FooterLinks::find($id)->update([
                'label_en' => $request->label_en,
                'label_ja' => $request->label_ja,
                'link' => $request->link,
                'display_order' => $request->display_order
            ]);

            return redirect('/footer-links')->with('success', __('messages.updatefooterlinksuccessfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $footer_link = FooterLinks::find($id);
        $footer_link->delete();

        return redirect()->back()->with('success', __('messages.deletefooterlinksuccessfully'));
    }
}
