@can('course')
<li class=" 
    {{ (request()->is('course/list') 
        || request()->is('course/add') 
        || request()->is('unit/list')
        || request()->is('unit/add')
        || request()->is('lesson/list')
        || request()->is('lesson/add')) ? ' active' : '' }}">
    <a  href="{{ url('/course/list') }}" >{{ __('messages.course') }}</a>
</li>
@endcan
