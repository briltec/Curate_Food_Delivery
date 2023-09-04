@foreach($zones as $key=>$zone)
    <tr>
        <td>{{$key+1}}</td>
        <td class="text-center">
            <span class="move-left">
                {{$zone->id}}
            </span>
        </td>
        <td class="pl-5">
            <span class="d-block font-size-sm text-body">
                {{$zone['name']}}
            </span>
        </td>
        <td class="text-center">
            <span class="move-left">
                {{$zone->restaurants_count}}
            </span>
        </td>
        <td class="text-center">
            <span class="move-left">
                {{$zone->deliverymen_count}}
            </span>
        </td>
        <td>
            <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$zone->id}}">
                <input type="checkbox" onclick="status_form_alert('status-{{$zone['id']}}','All the restaurants & delivery men under this zone will not be shown in the website or app', event)" class="toggle-switch-input" id="stocksCheckbox{{$zone->id}}" {{$zone->status?'checked':''}}>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
            <form action="{{route('admin.zone.status',[$zone['id'],$zone->status?0:1])}}" method="get" id="status-{{$zone['id']}}">
            </form>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn action-btn btn-light btn-outline-dark"
                href="{{route('admin.zone.settings',['id'=>$zone['id']])}}" title="{{translate('messages.zone_settings')}}"><i class="tio-settings"></i>
                </a>
                <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                    href="{{route('admin.zone.edit',[$zone['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.zone')}}"><i class="tio-edit"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach
