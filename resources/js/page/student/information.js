window.addEventListener('DOMContentLoaded', function () {
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('.js-example-basic-multiple').select2({
            width: '20%',
            placeholder: 'Choose Fields'
        }).on('change', function (e) {
            let columnsList = [];
            var options = $(".js-example-basic-multiple option:selected");
            for (let i = 0; i < options.length; i++) {
                columnsList.push(options[i].value)
            }

            $.ajax({
                url: 'information-ajax',
                method: 'post',
                data: {data: columnsList},
                success: function (res) {
                    let levels = res.class_student_levels;
                    let teachers = res.teachers;

                    let table = '<table class="table table-hover table-responsive">';
                    table += '<thead> <tr>';
                    for (let i = 0; i < res.headerValues.length; i++) {
                        if (res.headerValues[i] != 'id') {
                            table += `<th> ${res.headerValues[i]} </th>`
                        }
                    }

                    table += '</tr></thead>';

                    for (let i = 0; i < res.students.length; i++) {
                        let student = res.students[i];

                        let studentLevels = student.levels ? student.levels.split(',') : [];

                        let isFull = 1;


                        for (let j = 0; j < res.headerValues.length; j++) {
                            if (student[res.headerValues[j]] == null || student[res.headerValues[j]] == '' || res.headerValues[j] == 'levels') {
                                isFull = 0;
                            }
                        }

                        if (!isFull) {
                            table += '<tr>';

                            for (let j = 0; j < res.headerValues.length; j++) {
                                let val = student[res.headerValues[j]];
                                let column = res.headerValues[j];

                                if ((column != 'id')) {

                                    if (val == null || val == '' || column == 'levels') {
                                        table += '<td>';
                                        if (column == 'birthday' || column == 'join_date') {
                                            table += `<input type="date" data-id='${student['id']}' data-column='${column}' data-full="${isFull}" class="form-control input">`
                                        } else if (column == 'levels') {
                                            table += `<select class="levels" data-id='${student['id']}' data-column='${column}' data-full="${isFull}" class="form-control" multiple>`;
                                            for (let k = 0; k < levels.length; k++) {
                                                let isMatching = studentLevels.includes(levels[k]);
                                                table += `<option value="${levels[k]}" ${isMatching ? 'selected' : ''}>${levels[k]}</option>`
                                            }
                                            table += `</select>`;
                                        } else if (column == 'teacher_id') {
                                            table += `<select class="teachers form-control" data-id='${student['id']}' data-column='${column}' data-full="${isFull}" class="form-control">`;
                                            table += `<option value="">Select Advisor</option>`;
                                            for (let k = 0; k < teachers.length; k++) {
                                                let isMatching = teachers[k].id == student[column];
                                                table += `<option value="${teachers[k].id}">${teachers[k].name}</option>`
                                            }
                                            table += `</select>`;
                                        } else {
                                            table += `<input type="text" data-id='${student['id']}' data-column='${column}' data-full="${isFull}" class="form-control input">`
                                        }
                                        table += '</td>';
                                    } else {
                                        if (column == 'firstname' || column == 'lastname') {
                                            table += `<td>${val}</td>`;
                                        } else {
                                            table += `<td></td>`;
                                        }
                                    }
                                }
                            }

                            table += '</tr>';
                        }
                    }

                    table += '</table>';

                    let p = $('.table-content').html(table);

                    $('.levels').select2({
                        width: '100%',
                        placeholder: trans('messages.please-select-level-s')
                    }).on('change', (e) => {
                        let value = $(e.target).val();
                        console.log(value);
                        let id = $(e.target).data('id');
                        let column = $(e.target).data('column');
                        $.ajax({
                            url: 'update-student-information-column',
                            method: 'post',
                            data: {
                                id,
                                column,
                                value
                            },
                            success: (res) => {
                                toastr.options = {
                                    "positionClass": "toast-bottom-right",
                                };

                                let message = column.charAt(0).toUpperCase() + column.substr(1, column.length - 1) + ' Updated';
                                toastr.success(message, 'Notification')
                                console.log(res);
                            }
                        })
                    });
                    p.find('.input[type=text]').bind('keypress', function (e) {
                        if (e.keyCode == 13) {
                            let elem = $(e.currentTarget);
                            let column = elem.data('column');
                            let id = elem.data('id');
                            let full = elem.data('full');
                            let value = elem.val();
                            $.ajax({
                                url: 'update-student-information-column',
                                method: 'post',
                                data: {
                                    column,
                                    id,
                                    value
                                },
                                success: function (res) {
                                    if (res.message == 'success') {
                                        let td = elem.closest('td').html('');

                                        let tr = td.closest('tr');
                                        let inputs = tr.find('input');

                                        if (!inputs.length) {
                                            tr.remove();
                                        }
                                        toastr.options = {
                                            "positionClass": "toast-bottom-right",
                                        };

                                        let message = column.charAt(0).toUpperCase() + column.substr(1, column.length - 1) + ' Updated';
                                        toastr.success(message, 'Notification')
                                    }
                                },
                                error: function (err) {
                                    console.log(err);
                                }
                            })
                        }
                    });


                    function handleChange(e){
                        let elem = $(e.currentTarget);
                        let column = elem.data('column');
                        let id = elem.data('id');
                        let full = elem.data('full');
                        let value = elem.val();
                        console.log(value);

                        $.ajax({
                            url: 'update-student-information-column',
                            method: 'post',
                            data: {
                                column,
                                id,
                                value
                            },
                            success: function (res) {
                                if (res.message == 'success') {
                                    let td = elem.closest('td').html('')

                                    let tr = td.closest('tr');
                                    let inputs = tr.find('input');

                                    if (!inputs.length) {
                                        tr.remove();
                                    }
                                    toastr.options = {
                                        "positionClass": "toast-bottom-right",
                                    };
                                    let message = column.charAt(0).toUpperCase() + column.substr(1, column.length - 1) + ' Updated'
                                    toastr.success(message, 'Notification')
                                }
                            },
                            error: function (err) {
                                console.log(err);
                            }
                        })
                    }
                    p.find('.input[type=date]').bind('change', handleChange);
                    p.find('.teachers').bind('change', handleChange);
                }
            })
        });

    })
})
