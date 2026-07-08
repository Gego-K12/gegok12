export function registerWorkpermission(app) {
    app.component('workpermission-create', () =>
        import('./components/workpermission/teacher/Create.vue').then(m => m.default)
    );

    app.component('workpermission-teacher-list', () =>
        import('./components/workpermission/teacher/List.vue').then(m => m.default)
    );

    app.component('workpermission-approve', () =>
        import('./components/workpermission/teacher/ApproveReject.vue').then(m => m.default)
    );

    app.component('workpermission-pending-count', () =>
        import('./components/workpermission/teacher/PendingCount.vue').then(m => m.default)
    );
}
