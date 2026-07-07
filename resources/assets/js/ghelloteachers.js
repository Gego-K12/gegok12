export function registerHelloteachers(app) {
    app.component('hello-teachers-quote', () =>
        import('./components/helloteachers/teacher/Quote.vue').then(m => m.default)
    );
}
