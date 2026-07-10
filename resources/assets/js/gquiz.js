import VueCountdownTimer from 'vuejs-countdown-timer'

export function registerQuiz(app) {
    app.use(VueCountdownTimer)

    // quiz
    app.component('quiz-list', () =>
        import('./components/quiz/List.vue').then(m => m.default)
    );

    app.component('create-question', () =>
        import('./components/quiz/question/Create.vue').then(m => m.default)
    );

    app.component('edit-question', () =>
        import('./components/quiz/question/Edit.vue').then(m => m.default)
    );

    app.component('create-participant', () =>
        import('./components/quiz/participant/Create.vue').then(m => m.default)
    );

    app.component('quiztest-list', () =>
        import('./components/quiz/student/List.vue').then(m => m.default)
    );

    app.component('quiz-question', () =>
        import('./components/quiz/student/Create.vue').then(m => m.default)
    );

    app.component('test-review', () =>
        import('./components/quiz/student/Show.vue').then(m => m.default)
    );

    app.component('quiz-tab', () =>
        import('./components/quiz/teacher/QuizTab.vue').then(m => m.default)
    );

    app.component('test-details', () =>
        import('./components/quiz/participant/Show.vue').then(m => m.default)
    );

    // chapter
    app.component('list-chapter', () =>
        import('./components/test/chapter/List.vue').then(m => m.default)
    );

    app.component('add-chapter', () =>
        import('./components/test/chapter/Create.vue').then(m => m.default)
    );

    app.component('show-chapter', () =>
        import('./components/test/question/Show.vue').then(m => m.default)
    );

    app.component('show-subject', () =>
        import('./components/test/chapter/Show.vue').then(m => m.default)
    );

    app.component('create-test-question', () =>
        import('./components/test/question/Create.vue').then(m => m.default)
    );

    app.component('edit-test-question', () =>
        import('./components/test/question/Edit.vue').then(m => m.default)
    );

    app.component('create-test-pattern', () =>
        import('./components/test/pattern/Create.vue').then(m => m.default)
    );

    app.component('list-test', () =>
        import('./components/test/test/List.vue').then(m => m.default)
    );

    app.component('show-test', () =>
        import('./components/test/test/Show.vue').then(m => m.default)
    );

    app.component('import-question', () =>
        import('./components/test/question/Import.vue').then(m => m.default)
    );
}
