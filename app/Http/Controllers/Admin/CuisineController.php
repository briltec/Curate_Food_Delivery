<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cuisine;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;

class CuisineController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $key = explode(' ', $request['search']);
        $cuisine = Cuisine::when(isset($key) ,function ($q) use ($key) {
            foreach ($key as $value) {
                $q->where('name', 'like', "%{$value}%");
            }
        })->latest()->paginate(config('default_pagination'));
        return view('admin-views.cuisine.index',compact('cuisine'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:cuisines|max:100',
            'image' => 'nullable|max:2048',
        ], [
            'name.required' => translate('messages.Name is required!'),
        ]);

        $cuisine = new Cuisine();
        $cuisine->name = $request->name;
        $cuisine->image = $request->has('image') ? Helpers::upload('cuisine/', 'png', $request->file('image')) : 'def.png';
        $cuisine->save();

        Toastr::success(translate('messages.Cuisine_added_successfully'));
        return back();
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:cuisines,name,'.$request->id,
            'image' => 'nullable|max:2048',
        ], [
            'name.required' => translate('messages.Name is required!'),
        ]);
        $cuisine = Cuisine::find($request->id);
        $cuisine->name = $request->name;

        $slug = Str::slug($request->name);
        $cuisine->slug = $cuisine->slug? $cuisine->slug :"{$slug}-{$cuisine->id}";

        $cuisine->image = $request->has('image') ? Helpers::update('cuisine/', $cuisine->image, 'png', $request->file('image')) : $cuisine->image;
        $cuisine->save();

        Toastr::success(translate('messages.Cuisine_updated_successfully'));
        return back();
    }


    public function status(Request $request)
    {
        $cuisine = Cuisine::find($request->id);
        $cuisine->status = $request->status;
        $cuisine->save();
        Toastr::success(translate('messages.Cuisine_status_updated'));
        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cuisine  $cuisine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd(request()->all());
        $cuisine = Cuisine::findOrFail($request->id);
        if (Storage::disk('public')->exists('cuisine/' . $cuisine['image'])) {
            Storage::disk('public')->delete('cuisine/' . $cuisine['image']);
        }
        $cuisine->delete();
        Toastr::success('cuisine removed!');
        return back();
    }
}
