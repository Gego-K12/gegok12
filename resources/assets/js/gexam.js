export function registerExam(app) {

    // mark
    app.component('create-mark', () =>
        import('./components/mark/Create.vue')
    );

    app.component('show-mark', () =>
        import('./components/mark/Show.vue')
    );

    // exammark
    app.component('create-exammark', () =>
        import('./components/exammark/Create.vue')
    );

    app.component('show-exammark', () =>
        import('./components/exammark/Show.vue')
    );

    // examrules
    app.component('create-examrules', () =>
        import('./components/examrules/Create.vue')
    );

    // exam
    app.component('create-exam', () =>
        import('./components/exam/Create.vue')
    );

    app.component('edit-exam', () =>
        import('./components/exam/Edit.vue')
    );

    app.component('list-exam', () =>
        import('./components/exam/List.vue')
    );

    // Grade
    app.component('list-grade', () =>
        import('./components/examrules/grade/List.vue')
    );

    // Teacher-Exam
    app.component('list-exam-teacher', () =>
        import('./components/teacher/exam/List.vue')
    );

    app.component('create-exam-mark', () =>
        import('./components/teacher/exam/mark/Create.vue')
    );

    // exam-schedule
    app.component('create-examschedule', () =>
        import('./components/examschedule/Create1.vue')
    );

    app.component('edit-examschedule', () =>
        import('./components/examschedule/Edit.vue')
    );

    app.component('list-examschedule', () =>
        import('./components/examschedule/List.vue')
    );
}