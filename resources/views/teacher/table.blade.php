<table class="table table-hover">
    <tbody>
        <tr>
            <th>{{ __('messages.name') }}</th>
            <th>{{ __('messages.nickname') }}</th>
            <th>{{ __('messages.email') }}</th>
            <th>{{ __('messages.zoom-email') }}</th>
            <th>{{ __('messages.address') }}</th>
            <th>{{ __('messages.birthday') }}</th>
            <th>{{ __('messages.profile') }}</th>
            <th>{{ __('messages.actions') }}</th>
        </tr>
        @if(!$teachers->isEmpty())
            @foreach($teachers as $teacher)
                @php        
                    $res = $teacher->canBeDeleted(); 
                    $can_be_deleted = $res['can_be_deleted'];
                @endphp
                <tr>
                    <td><a href="{{ url('/teacher/'.$teacher->id) }}">{{$teacher->name}}({{$teacher->furigana}})</a></td>
                    <td>{{$teacher->nickname}}</td>
                    <td>{{ $teacher->user->email }}</td>
                    <td>{{ $teacher->user->zoom_email }}</td>
                    <td>{{$teacher->birthplace}}</td>
                    <td>{{$teacher->birthday}}</td>
                    <td>{{$teacher->profile}}</td>
                    <td>
                        @can('teacher-edit')
                            <a href="{{ url('/teacher/'.$teacher->id.'/edit') }}" class="btn btn-sm btn-warning mb-1">{{ __('messages.edit') }}</a>
                            @if($teacher->status != 1)
                                <button class="btn btn-danger btn-sm mb-1 btn_archive_teacher" data-teacher_id="{{ $teacher->id }}" data-teacher_name="{{ $teacher->nickname }}">{{ __('messages.archive') }}</button>
                            @endif
                        @endcan

                        @can('teacher-delete')
                            <form class="delete" method="POST" action="{{ route('teacher.destroy', $teacher->id) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    {{ $can_be_deleted == 1 ? '' : 'disabled' }}
                                    class="btn btn-sm btn-danger mb-1" 
                                    type="submit"
                                    >{{ __('messages.delete') }}</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>