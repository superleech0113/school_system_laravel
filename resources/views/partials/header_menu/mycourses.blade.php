@can('st-courses')
    <li class="{{ app()->request->route()->getName() == 'student.courses' ? 'active' : '' }}">
        <a aria-expanded="false" role="button" href="{{ route('student.courses') }}" class="nav-link">{{ __('messages.mycourses') }}</a>
    </li>
@endcan
