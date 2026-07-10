export function registerCertificate(app) {
    app.component('create-certificate', () =>
        import('./components/certificate/Create.vue').then(m => m.default)
    );

    app.component('list-certificate', () =>
        import('./components/certificate/List.vue').then(m => m.default)
    );

    app.component('edit-certificate', () =>
        import('./components/certificate/Edit.vue').then(m => m.default)
    );
}
