export function registerAlumni(app) {
    // alumni-adminside
    app.component('create-alumni', () =>
        import('./components/alumni/Create.vue').then(m => m.default)
    );

    app.component('alumni-list', () =>
        import('./components/alumni/List.vue').then(m => m.default)
    );

    app.component('alumni-profile', () =>
        import('./components/alumni/Profile.vue').then(m => m.default)
    );

    app.component('alumni-batch-filter', () =>
        import('./components/alumni/Filter.vue').then(m => m.default)
    );

    // alumni-login-profile
    app.component('add-alumni', () =>
        import('./components/alumni/AlumniTab.vue').then(m => m.default)
    );

    app.component('alumni-personal', () =>
        import('./components/alumni/AlumniPersonal.vue').then(m => m.default)
    );

    app.component('alumni-education', () =>
        import('./components/alumni/AlumniEducation.vue').then(m => m.default)
    );

    app.component('alumni-job', () =>
        import('./components/alumni/AlumniJob.vue').then(m => m.default)
    );

    app.component('alumni-contact', () =>
        import('./components/alumni/AlumniContact.vue').then(m => m.default)
    );

    app.component('edit-alumni', () =>
        import('./components/alumni/Edit.vue').then(m => m.default)
    );

    app.component('alumni-profile-list', () =>
        import('./components/alumni/profile/List.vue').then(m => m.default)
    );

    app.component('alumni-profile-details', () =>
        import('./components/alumni/profile/Profile.vue').then(m => m.default)
    );

    app.component('alumni-profile-batch-filter', () =>
        import('./components/alumni/profile/Filter.vue').then(m => m.default)
    );
}
