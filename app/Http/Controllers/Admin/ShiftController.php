<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use Brian2694\Toastr\Facades\Toastr;

class ShiftController extends Controller
{
    public function list()
    {
        $shifts= Shift::latest()->paginate(25);
        $total=$shifts->total();
        // dd($shifts);
        return view('admin-views.shift.list',[
            'shifts'=>$shifts,
            'total'=>$total,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'start_time'=>'required|date_format:H:i',
            'end_time'=>'required|date_format:H:i|after:start_time',
            'name'=>'required|max:191',
        ],[
            'end_time.after'=>translate('messages.End time must be after the start time')
        ]);

        $temp = Shift::where(function ($q) use ($request) {
            return $q->where(function ($query) use ($request) {
                return $query->where('start_time', '<=', $request->start_time)->where('end_time', '>=', $request->start_time);
            })->orWhere(function ($query) use ($request) {
                return $query->where('start_time', '<=', $request->end_time)->where('end_time', '>=', $request->end_time);
            });
        })
        ->first();

    if (isset($temp)) {
        return response()->json(['errors' => [
            ['code' => 'overlaped', 'message' => translate('messages.Shift_overlaped')]
        ]]);
    }
        $shift = new Shift();
        $shift->name = $request->name;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();

        Toastr::success(translate('messages.shift_added_successfully'));
        return response()->json(['success'=>true]);

    }
    public function status(Request $request)
    {
        $shift = Shift::findOrFail($request->id);
        $shift->status = $request->status;
        $shift->save();
        Toastr::success(translate('messages.shift_status_updated'));
        return back();
    }
    public function update(Request $request )
    {
        // dd($request->all());
        $id=$request->id;
        $request->validate([
            'start_time'=>'required',
            'end_time'=>'required|after:start_time',
            'name'=>'required|max:191',
        ],[
            'end_time.after'=>translate('messages.End time must be after the start time')
        ]);
        $temp = Shift::where('id' ,'!=', $id)->where(function ($q) use ($request) {
            return $q->where(function ($query) use ($request) {
                return $query->where('start_time', '<=', $request->start_time)->where('end_time', '>=', $request->start_time);
            })->orWhere(function ($query) use ($request) {
                return $query->where('start_time', '<=', $request->end_time)->where('end_time', '>=', $request->end_time);
            });
        })
        ->first();

    if (isset($temp)) {
        return response()->json(['errors' => [
            ['code' => 'overlaped', 'message' => translate('messages.Shift_overlaped')]
        ]]);
    }
        Shift::find($id)->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        Toastr::success(translate('messages.shift_updated_successfully'));
        return response()->json(['success'=>true]);
    }
    public function destroy(Shift $shift)
    {
        $shift->delete();
        Toastr::success(translate('messages.shift_deleted_successfully'));
        return back();
    }
    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $shifts=Shift::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })
        ->latest()->paginate(50);
        return response()->json([
            'view'=>view('admin-views.shift.partials._table',compact('shifts'))->render(),
            'total'=>$shifts->count()
        ]);
    }
}
