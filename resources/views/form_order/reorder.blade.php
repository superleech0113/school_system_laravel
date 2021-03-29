<div class="col-lg-6">
    <h4>{{ __('messages.show') }}</h4>
    <ol class="units-reorder-section visible">
        @foreach($visibleFields as $field)
            <li class="m-1  {{in_array($field->field_name, \App\FormOrders::EXCLUDE_FIELDS[$field->data_model]) ? 'not-moveable' : '' }}  field_{{$field->id}}">
                <a class="btn btn-secondary btn-block text-left text-white">
                    <span>@if ($field->is_custom)
                        @php $custom_field = \App\CustomFields::where('data_model', $field->data_model)->where('field_name', $field->field_name)->first(); @endphp
                        {{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}
                    @else
                        {{ __('messages.'.$field->field_name) }}
                    @endif
                    </span>
                    @if(in_array($field->field_name, \App\FormOrders::EXCLUDE_FIELDS[$field->data_model]))
                        <input type="hidden" name="is_required[{{$field->id}}]" value="1">
                    @else
                    <label class="m-0 pull-right is_required">     
                        <input type="checkbox" name="is_required[{{$field->id}}]" {{ $field->is_required ? 'checked' : '' }}>
                    </label>
                    @endif
                </a>
                <input type="hidden" name="is_visible[{{$field->id}}]" value="1" class="is_visible">
                <input type="hidden" name="field_ids[]" value="{{ $field->id }}">
            </li>
        @endforeach
    </ol>
</div>
<div class="col-lg-6">
    <h4>{{ __('messages.hide') }}</h4>
    <ol class="units-reorder-section in-visible">
        @foreach($invisibleFields as $field)
            <li class="m-1 field_{{$field->id}}">
                <a class="btn btn-secondary btn-block text-left text-white">
                    <span>@if ($field->is_custom)
                        @php $custom_field = \App\CustomFields::where('data_model', $field->data_model)->where('field_name', $field->field_name)->first(); @endphp
                        {{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}
                    @else
                        {{ __('messages.'.$field->field_name) }}
                    @endif
                    </span>
                    <label class="m-0 pull-right hide is_required">     
                        <input type="checkbox" name="is_required[{{$field->id}}]" {{ $field->is_required ? 'checked' : '' }}>
                    </label>
                </a>
                <input type="hidden" name="is_visible[{{$field->id}}]" value="0" class="is_visible">
                <input type="hidden" name="field_ids[]" value="{{ $field->id }}">
            </li>
        @endforeach
    </ol>
</div>
