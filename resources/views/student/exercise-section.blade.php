<div style="background: #fefefe;padding:15px;border:2px solid #e9ecef;" class="col-md-9">
    @foreach($lesson->lessonExercises as $lessonExercise)
        <div class="row mt-1 lesson_exercise_row">
            @php
                $is_complete = 0;
                $status_line = '';
                if(isset($masterLessonExerciseStatus[$lessonExercise->id]))
                {
                    $lessonExerciseStatus = $masterLessonExerciseStatus[$lessonExercise->id];
                    if($lessonExerciseStatus->is_complete == 1)
                    {
                        $is_complete = 1;
                    }
                    $status_line = $lessonExerciseStatus->getStatusLine();
                }
            @endphp
            <div class="col-sm-12 pl-3">
                <input type="text" class="form-control ml-0 my-1" value="{{ $lessonExercise->title }}" disabled>
            </div>
            <div class="col-sm-12 pl-0 text-right">
                <label style="font-style: italic;" class="status_line_text">{{ $status_line }}</label>
            </div>
        </div>
    @endforeach
</div>
