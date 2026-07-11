export function registerTimetable(app) {
    app.component('create-timetable', () =>
        import('./components/timetable/Create.vue').then(m => m.default)
    );

    app.component('edit-timetable', () =>
        import('./components/timetable/Edit.vue').then(m => m.default)
    );
}
