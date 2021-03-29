@push('styles')
<style>
.collapsing {
    -webkit-transition: none;
    transition: none;
    display: none;
}
.cp-lesson-row {
    cursor: pointer;
}
</style>
@endpush

@php
    $_today_date = \Carbon\carbon::now(\App\Helpers\CommonHelper::getSchoolTimezone())->format('Y-m-d');
@endphp

@if($course)
    <h3>{{ __('messages.coursedetails') }}</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover ">
            <tbody>
            <tr>
                <th style="width:15%">{{ __('messages.name')}}</th>
                <td>{{ $course->title }}</td>
            </tr>
            @if($course->description)
                <tr>
                    <th>{{ __('messages.description')}}</th>
                    <td>{{ $course->description }}</td>
                </tr>
            @endif
            @if($course->objectives)
                <tr>
                    <th>{{ __('messages.objectives')}}</th>
                    <td>{{ $course->objectives }}</td>
                </tr>
            @endif
            @if($course->thumbnail)
                <tr>
                    <th>{{ __('messages.thumbnail')}}</th>
                    <td>{!! $course->the_image() !!}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    @if($course->units->count() > 0)
        <div class="card card-body course-progress">
            @foreach($course->units as $unit_index => $unit)
                <div class="unit-progress">
                    <div class="unit-button">
                        <a class="btn btn-secondary" data-toggle="collapse" href="#unit{{ $unit->id }}" role="button" aria-expanded="false" aria-controls="unit{{ $unit->id }}">
                            {{ __('messages.unitnumber', ['number' => $unit_index+1]) }}: {{ $unit->name }}
                        </a>
                    </div>
                    <div class="collapse show" id="unit{{ $unit->id }}">

                        <table class="table table-striped table-bordered table-hover ">
                            <tbody>
                            <tr>
                                <th style="width:15%">{{ __('messages.objectives')}}</th>
                                <td>{{ $unit->objectives }}</td>
                            </tr>
                            </tbody>
                        </table>

                        @if($unit->lessons->count() > 0)
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.lesson') }}</th>
                                    <th>{{ __('messages.test') }}</th>
                                    <th>{{ __('messages.papertest') }}</th>
                                    <th>{{ __('messages.assessment') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($unit->lessons as $lesson)
                                        @php
                                            $scheduledLesson = $schedule->getScheduledLesson($lesson->id);
                                            $is_completed = $scheduledLesson && $scheduledLesson->complete ? true : false;
                                        @endphp
                                        <tr class="cp-lesson-row lesson {{  $is_completed ? 'complete' : '' }}" data-toggle="collapse" href="#cp-lesson{{ $lesson->id }}" role="button" aria-expanded="false" aria-controls="cp-lesson{{ $lesson->id }}">
                                            <td>{{ $lesson->title }}</td>
                                            <td>
                                                @if($lesson->tests->count() > 0)
                                                    @foreach($lesson->tests as $test)
                                                       {{ $test->name }}<br>
                                                    @endforeach
                                                @else
                                                    {{ __('messages.notest') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($lesson->paper_tests->count() > 0)
                                                    @foreach($lesson->paper_tests as $paper_test)
                                                        {{ $paper_test->name }}<br>
                                                    @endforeach
                                                @else
                                                    {{ __('messages.notest') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($lesson->assessments->count() > 0)
                                                    @foreach($lesson->assessments as $assessment)
                                                        {{ $assessment->name }}<br>
                                                    @endforeach
                                                @else
                                                    {{ __('messages.noassessment') }}
                                                @endif
                                            </td>
                                                <td>
                                                    {{ $is_completed ? $scheduledLesson->date : '' }}
                                                </td>
                                        </tr>
                                        <tr class="collapse" id="cp-lesson{{ $lesson->id }}">
                                            <td colspan="6">
                                                <table class="table table-hover">
                                                    <tbody>
                                                        @if($lesson->description)
                                                            <tr>
                                                                <th style="width:15%">{{ __('messages.description') }}</th>
                                                                <td>{!! nl2br($lesson->description) !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->objectives)
                                                            <tr>
                                                                <th>{{ __('messages.objectives') }}</th>
                                                                <td>{!! nl2br($lesson->objectives) !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->full_text)
                                                            <tr>
                                                                <th>{{ __('messages.fulltext') }}</th>
                                                                <td>{!! nl2br($lesson->full_text) !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->thumbnail)
                                                            <tr>
                                                                <th>{{ __('messages.thumbnail') }}</th>
                                                                <td>{!! $lesson->the_image() !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->lessonExercises->count() > 0)
                                                            <tr>
                                                                <th>{{ __('messages.exercises') }}</th>
                                                                <td>
                                                                    @include('student.exercise-section')
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->student_lesson_prep)
                                                            <tr>
                                                                <th>{{ __('messages.student-lesson-prep') }}</th>
                                                                <td>{!! nl2br($lesson->student_lesson_prep) !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->vocab_list)
                                                            <tr>
                                                                <th>{{ __('messages.vocab-list') }}</th>
                                                                <td>{!! nl2br($lesson->vocab_list) !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->lessonHomeworks->count() > 0)
                                                            <tr>
                                                                <th>{{ __('messages.homework') }}</th>
                                                                <td>
                                                                    @include('course.unit.lesson.homework-section')
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->extra_materials_text || $lesson->extraMaterialFiles->count() > 0)
                                                            <tr>
                                                                <th>{{ __('messages.extra-materials') }}</th>
                                                                <td>
                                                                    @if($lesson->extra_materials_text)
                                                                        {!! nl2br($lesson->extra_materials_text) !!} <br><br>
                                                                    @endif
                                                                    {!! $lesson->the_extramaterial_files_url() !!}
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->downloadableFiles->count() > 0)
                                                            <tr>
                                                                <th>{{ __('messages.downloadablefiles') }}</th>
                                                                <td>{!! $lesson->the_downloadable_files_url() !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->pdfFiles->count() > 0)
                                                            <tr>
                                                                <th>{{ __('messages.pdffiles') }}</th>
                                                                <td>{!! $lesson->the_pdf_files_url() !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->audioFiles->count() > 0)
                                                            <tr>
                                                                <th>{{ __('messages.audiofiles') }}</th>
                                                                <td>{!! $lesson->the_audio_files_url() !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->videoFiles->count() > 0)
                                                            <tr>
                                                                <th>{{ __('messages.video') }}</th>
                                                                <td>{!! $lesson->the_video_url() !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->teachers_notes)
                                                            <tr>
                                                                <th>{{ __('messages.teachers-notes') }}</th>
                                                                <td>{!! nl2br( $lesson->teachers_notes) !!}</td>
                                                            </tr>
                                                        @endif

                                                        @if($lesson->teachers_prep)
                                                            <tr>
                                                                <th>{{ __('messages.teachers-prep') }}</th>
                                                                <td>{!! nl2br($lesson->teachers_prep) !!}</td>
                                                            </tr>
                                                        @endif

                                                        <tr>
                                                            <th>{{ __('messages.comments') }}</th>
                                                            <td>
                                                               {{ $scheduledLesson ? $scheduledLesson->comments : '' }}
                                                                <div>
                                                                    <div class="pull-right m-1" style="font-style:italic;">
                                                                        <span class="comments_status_line">{{ $scheduledLesson && $scheduledLesson->comment_updated_at ? $scheduledLesson->getCommentStatusLine() : '' }}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endif

@push('scripts')
<script src="{{ mix('js/page/schedule/details/tabs/course-progress.js') }}"></script>
@endpush
