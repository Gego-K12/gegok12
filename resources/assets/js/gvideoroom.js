import uploader from 'vue-simple-uploader'
import AudioRecorder from 'vue-audio-recorder'

export function registerVideoroom(app) {
    app.use(uploader)
    app.use(AudioRecorder)

    // conference-admin
    app.component('create-conference', () =>
        import('./components/conference/admin/Create.vue').then(m => m.default)
    );

    app.component('edit-conference', () =>
        import('./components/conference/admin/Edit.vue').then(m => m.default)
    );

    app.component('add-invites', () =>
        import('./components/conference/admin/Invites.vue').then(m => m.default)
    );

    // conference-teacher
    app.component('create-teacher-conference', () =>
        import('./components/conference/teacher/Create.vue').then(m => m.default)
    );

    app.component('edit-teacher-conference', () =>
        import('./components/conference/teacher/Edit.vue').then(m => m.default)
    );

    app.component('add-teacher-invites', () =>
        import('./components/conference/teacher/Invites.vue').then(m => m.default)
    );

    // Media Files
    app.component('file-list-tab', () =>
        import('./components/files/listTab.vue').then(m => m.default)
    );

    app.component('add-video', () =>
        import('./components/files/Video.vue').then(m => m.default)
    );

    app.component('add-image', () =>
        import('./components/files/Image.vue').then(m => m.default)
    );

    app.component('create-media', () =>
        import('./components/media/Create.vue').then(m => m.default)
    );
}
