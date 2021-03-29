<?php

namespace App;

use Spatie\Permission\Models\Permission as BasePermission;

Class Permission extends BasePermission
{
    protected $table = 'permissions';

    // Add route to this array if only one permission should be checked against a route.
    // e.g. 'permission-name' => ['route-1', 'route-1']
    // access to route-1 will be grated if only user has access to permission 'permission-name'
    public const ROUTE_MATCH = [
        'class-list' => ['class.index'],
        'class-create' => ['class.create', 'class.store'],
        'class-edit' => ['class.edit', 'class.update'],
        'class-delete' => ['class.destroy'],
        'contact-list' => [ 'contact.index', 'contact-store'],
        'contact-delete' => ['contact.destroy'],
        'payment-list' => ['monthly.payment.index'],
        'payment-create' => ['payment.create'],
        'payment-delete' => ['payment.destroy'],
        'payment-plan-list' => ['plan.index'],
        'payment-plan-create' => ['plan.create'],
        'payment-plan-delete' => ['plan.destroy'],
        'schedule-add' => ['schedule.add'],
        'schedule-list' => ['schedule.monthly', 'schedule.cal_data', 'schedule.listbydate'],
        'schedule-details' => [
            'schedule.show',
            'assessment_user.destroy', 'assessment_user.create', 'assessment_user.store', 'assessment_user.show',
            'student-test.destroy', 'schedule.lesson.complete', 'schedule.lesson.destroy',
            'student.paper_test.create', 'student.paper_test.edit', 'student.paper_test.store', 'student.paper_test.destroy',
            'lesson.update_exercise_status', 'lesson.update_homework_status', 'schedule.lesson.comments',
        ],
        'student-list' => ['student.index','student.reconfirm'],
        'student-map' => ['student.map'],
        'student-information' => ['student.information', 'student.informationAjax', 'student.information-update'],
        'student-create' => ['student.create', 'student.store'],
        'student-edit' => ['student.edit', 'student.update', 'student.payement-settings.save', 'student.archive','student.force-verify', 'student.comment', 'student.course-settings.save', 'student.stripe-subscritpion-preference.save'],
        'student-show' => ['student.show'],
        'student-delete' => ['student.destroy'],
        'teacher-list' => ['teacher.index'],
        'teacher-create' => ['teacher.create', 'teacher.store'],
        'teacher-edit' => ['teacher.edit', 'teacher.update', 'teacher.archive'],
        'teacher-show' => ['teacher.show'],
        'teacher-delete' => ['teacher.destroy'],
        'event' => ['event.index', 'event.create', 'event.store', 'event.edit', 'event.update', 'event.show', 'event.destroy'],
        'attendance-add' => ['yoyaku.create', 'yoyaku.store', 'attendance.store'],
        'calendar' => ['schedule.calendar', 'schedule.cal_data_1', 'schedule.class.details'],
        'reservation-list' => ['schedule.list'],
        'school-settings' => ['school-setting.edit', 'school-setting.update', 'custom-domain.add', 'custom-domain.remove'],
        'terminal-settings' => ['terminal-settings.edit', 'terminal-settings.update'],
        'security-settings' => ['security-settings.edit', 'security-settings.update'],
        'application-settings' => ['application-settings.edit', 'application-settings.update'],
        'library' => [
            'book.index', 'book.show', 'book.create', 'book.store', 'book.edit', 'book.update', 'book.destroy',
            'book.checkin.create', 'book.checkin.store', 'book.checkout.create', 'book.checkout.store',
            'library-settings.edit', 'library-settings.update',
            'book.isbn.info'
        ],
        'email-settings' => ['email-settings.edit', 'email-settings.update', 'preview_drr_email', 'mail.send-test-email'],
        'course' => [
            'course.create', 'course.store', 'course.index', 'course.destroy', 'course.edit', 'course.edit.modal', 'course.update', 'course.show', 'course.units','course.reorder_units.form','course.reorder_units.save',
            'unit.create', 'unit.store', 'unit.index', 'unit.destroy', 'unit.edit', 'unit.update', 'unit.show', 'unit.reorder_lessons.form', 'unit.reorder_lessons.save',
            'lesson.create', 'lesson.store', 'lesson.index', 'lesson.destroy', 'lesson.edit', 'lesson.edit.fields', 'lesson.update', 'lesson.show',
            'lessonfile.upload', 'lessonfile.delete', 'lessonfile.update'
        ],
        'test' => [
            'test.create', 'test.store', 'test.index', 'test.destroy', 'test.edit', 'test.update', 'test.show',
            'question.create', 'unit.store', 'unit.index', 'unit.destroy', 'unit.edit', 'unit.update', 'unit.show',
            'answer.create', 'answer.store', 'answer.index', 'answer.destroy', 'answer.edit', 'answer.update', 'answer.show',
            'comment_template.create', 'comment_template.store', 'comment_template.index', 'comment_template.destroy', 'comment_template.edit', 'comment_template.update'
        ],
        'assessment' => [
            'assessment.create', 'assessment.store', 'assessment.index', 'assessment.destroy', 'assessment.edit', 'assessment.update', 'assessment.show', 'assessment.questions', 'assessment.reorder_questions.form', 'assessment.reorder_questions.save',
            'assessment-question.create', 'assessment-question.store', 'assessment-question.destroy', 'assessment-question.edit', 'assessment-question.update', 'assessment-question.edit_fields',
            'assessment.data', 'assessment.assign', 'assessment.responses', 'assessment.preview'
        ],
        'take-test' => [
            'student.online_test.index', 'student.paper_test.index', 'student.online_test.take', 'student.online_test.store_result'
        ],
        'take-assessment' => [
            'user.assessment.index'
        ],
        'Role-Management' => ['roles.create', 'roles.store', 'roles.index', 'roles.destroy', 'roles.edit', 'roles.update', 'roles.show'],
        'User-Management' => ['users.create', 'users.store', 'users.index', 'users.destroy', 'users.edit', 'users.update', 'users.show'],
        'class-category' => ['class-category.create', 'class-category.store', 'class-category.index', 'class-category.destroy', 'class-category.edit', 'class-category.update', 'class-category.show'],
        'student-impersonate' => ['student.start_impersonate'],

        'todo-mytodos' => [ 'mytodos' ],
        'todo-list' => [ 'todo.index' ],
        'todo-create' => [ 'todo.create', 'todo.store'],
        'todo-edit' => [ 'todo.edit', 'todo.update'],
        'todo-delete' => [ 'todo.destroy' ],
        'todo-progress-details' => ['todo.progress_details' ],
        'todo-progress' => ['todo.progress'],

        'student-search' => ['student.search'],
        'class-usage' => ['student.class_usage'],
        'activity-logs' => [ 'activity_logs.index', 'activity_logs.data' ],

        'children' => ['children.index'],
        'st-classes' => [ 'student.classes' ],
        'st-courses' => [ 'student.courses' ],
        'st-class-details' => ['student.class_details' ],
        'st-courses-details' => ['student.courses_details' ],
        'st-assessments' => ['student.assessments', 'student.view_assessment'],
        'stats' => ['daily_stats', 'stats', 'stats_data.non_zero_class', 'stats_data.attendances', 'stats_data.total-amount'],
        'waitlisted-students' => ['waitlisted_students'],
        'manage-tags' => ['tags.index', 'tags.save', 'tags.delete', 'tags.get_settings', 'tags.save_settings'],
        'edit-student-tags' => ['tags.save_student_tags'],
        'control-attendance' => ['delete_reservation'],
        'edit-schedule' => ['schedule.edit.data', 'schedule.update'],
        'manage-availability-timeslots' => ['edit_calendar.index', 'edit_calendar.data', 'edit_calender.save_timeslot', 'edit_calender.timeslot.delete'],
        'manage-availability-selection-calendars' => ['availability_selection_calendars.save', 'availability_selection_calendars.delete'],
        'view-availability-responses' => ['availability_selection_calendars.responses','availability_selection_calendars.responses.initialdata','availability_selection_calendars.responses.data'],
        'payment-settings' => ['payment-settings.edit', 'payment-settings.update'],
        'payment-mark-as-paid' => ['payment.paid', 'markaspaid.data'],
        'payment-edit' => ['payment.monthly.update'],
        'st-payments' => ['payments.index'],
        'manage-monthly-payments' => [
            'manage.monthly.payments.index', 'manage.monthly.payments.data','manage.monthly.payments.generate.records',
            'payment.send.multiple.stripe.invoice'
        ],
        'payment-delete' => ['monthly.payment.destroy'],
        'accounting-payments' => ['accounting.payments', 'accounting.payments.records'],
        'schedule-settings' => ['schedule-settings.edit', 'schedule-settings.update'],
        'lesson-settings' => ['lesson-settings.edit', 'lesson-settings.update'],
        'manage-school-off-days' => ['offday.add', 'offday.delete'],
        'manage-zoom-meetings-for-class' => ['zoom.create.meeting', 'zoom.delete.meeting', 'zoom.send-meeting-reminder', 'zoom.meeting.sync'],
        'line-settings' => ['line-settings.edit', 'line-login-settings.save', 'line-messaging-settings.save'],
        'notification-settings' => ['notification-settings.edit', 'notification-status.save', 'notification-text.save'],
        'adminfiles-list' => ['adminfile.index'],
        'adminfiles-create' => ['adminfile.create', 'adminfile.upload'],
        'adminfiles-delete' => ['adminfile.delete'],
        'customfield-list' => ['custom-field.index'],
        'customfield-create' => ['custom-field.create', 'custom-field.store'],
        'customfield-edit' => ['custom-field.edit', 'custom-field.update'],
        'customfield-delete' => ['custom-field.destroy'],
        'footerlink-list' => ['footer-link.index'],
        'footerlink-create' => ['footer-link.create', 'footer-link.store'],
        'footerlink-edit' => ['footer-link.edit', 'footer-link.update'],
        'footerlink-delete' => ['footer-link.destroy'],
        'application-list' => ['applications.index'],
        'convert-to-student' => ['applications.convert-to-student'],
        'application-create' => ['applications.create', 'applications.store'],
        'application-edit' => ['applications.edit', 'applications.update'],
        'application-list' => ['applications.show'],
        'application-delete' => ['applications.destroy'],
        'student-docs-upload' => ['studentdocs.upload'],
        'student-docs-delete' => ['studentdocs.delete'],
        'reorder-form' => ['reorder.form.create', 'reorder.form.form', 'reorder.form.save'],
        'pwa-settings' => ['tenant.pwa','tenant.pwa.store','tenant.pwa.update','tenant.pwa.delete','tenant.pwa.activate','tenant.pwa.deactivate'],

        'card-list' => ['cards.index', 'cards.records', 'card.set.as.default'],
        'card-create' => ['cards.add'],
        'card-delete' => ['card.delete'],
        'plan-list' => ['plans.index', 'plans.records'],
        'discount-list' => ['discounts.index', 'discounts.records'],
        'stripe-subscription-list' => ['stripe.subscription.index'],
        'stripe-subscription-sd-edit' => ['cancel.stripe.subscription', 'save.invoice.items'],
    ];

    // add route tho this array if access to route should be grated if user has any one of the defined permissions for a route.
    // e.g. 'route-1' => ['permission-1', 'permission-2', 'permission-3]
    // access to route-1 will be grated if user has permission to either 'permission-1', 'permission-2', or 'permission-3'
    public const ANY_ONE_PERMISSION = [
        'tags.records' => ['manage-tags', 'view-student-tags'],
        'availability_selection_calendars.index' => ['manage-availability-selection-calendars', 'manage-availability-timeslots','view-availability-responses'],
        'availability_selection_calendars.records' => ['manage-availability-selection-calendars', 'manage-availability-timeslots','view-availability-responses'],
        'timeslotpicker.data' => [ 'take-assessment', 'assessment', 'edit-assessment-response'],
        'payment.send.stripe.invoice' => [ 'manage-monthly-payments', 'student-show'],
        'user.assessment.take' => ['edit-assessment-response', 'take-assessment'],
        'user.assessment.store_result' => ['edit-assessment-response', 'take-assessment'],
        'comments.add' => ['schedule-details', 'calendar'],
        'files.upload' => ['schedule-details', 'calendar'],
        'student.show' => ['student-info', 'student-contact', 'student-payment-settings', 'student-course', 'student-payment', 'student-monthly-payment', 'student-other-payment', 'student-event-payment', 'student-books', 'student-test-result', 'student-paper-result', 'student-assessment-result', 'student-todo', 'student-class-usage', 'student-add-payment', 'student-add-montly-payment', 'student-add-contact', 'student-show', 'student-docs'],

        'plans.save' => ['plan-create', 'plan-edit'],
        'discounts.save' => ['discount-create', 'discount-edit'],
        'upcomming.invoice' => ['stripe-subscription-sd-list', 'stripe-subscription-list'],
        'stripe.retry.charge' => ['stripe-subscription-sd-list', 'stripe-subscription-list'],
        'stripe.first.invoice.time' => ['stripe-subscription-sd-list', 'stripe-subscription-list'],
        'save.stripe.subscription' => ['stripe-subscription-sd-create', 'stripe-subscription-sd-edit'],
    ];
}
