@forelse ($shifts as $key => $shift)
<tr>
    <td>{{$key+$shifts->firstItem()}}</td>

    <td>
    <span class="d-block font-size-sm text-body">
        {{$shift['name']}}
    </span>
    </td>
    <td>
        {{ Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
    </td>
    <td>
        {{date('h:i A', strtotime($shift['end_time']))}}
    <td>
        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$shift->id}}" >
            <input class="toggle-switch-input" type="checkbox"  onclick="status_change_alert('{{route('admin.shift.status',[$shift['id'],$shift->status?0:1])}}','{{ translate('Want to change status for this shift ?') }}', event)"
            id="stocksCheckbox{{$shift->id}}" {{$shift->status?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label>

        {{-- <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$dm->id}}">
            <input type="checkbox" onclick="status_change_alert('{{route('admin.restaurant.status',[$dm->id,$dm->status?0:1])}}', '{{translate('messages.you_want_to_change_this_restaurant_status')}}', event)" class="toggle-switch-input" id="stocksCheckbox{{$dm->id}}" {{$dm->status?'checked':''}}>
            <span class="toggle-switch-label">
                <span class="toggle-switch-indicator"></span>
            </span>
        </label> --}}

        <form action="{{route('admin.shift.status',[$shift['id'],$shift->status?0:1])}}" method="get" id="stocksCheckbox-{{$shift['id']}}">
        </form>
    </td>
    <td >
        <div class="btn--container justify-content-center">
            <button onClick="javascript:showMyModal(`{{ $shift['id'] }}`, `{{ $shift->name }}` , `{{ $shift->start_time }}`,`{{ $shift->end_time }}` )" title="{{translate('messages.edit')}} {{translate('messages.shift')}}" class="btn btn-sm btn--primary btn-outline-primary action-btn">
                <i class="tio-edit"></i>
            </button>
            <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
            onclick="form_alert('shift-{{$shift['id']}}','{{ translate('Want to delete this shift data. All of data related to this shift will be gone !!!') }}')" title="{{translate('messages.delete')}} {{translate('messages.shift')}}">
            <i class="tio-delete-outlined"></i>
            </a>
            <form action="{{route('admin.shift.delete',[$shift['id']])}}" method="post" id="shift-{{$shift['id']}}">
                @csrf @method('delete')
            </form>
        </div>
    </td>
</tr>
@empty

@endforelse
