import uploader from 'vue-simple-uploader'
import AudioRecorder from 'vue-audio-recorder'

export function registerVideoroom(app) {
    app.use(uploader)

    // vue-audio-recorder's install() targets Vue 2's global `Vue.prototype`/`new Vue`
    // event-bus pattern (app.use() passes the Vue 3 app instance instead, which has
    // no .prototype), so register its components directly and provide the $eventBus
    // it expects (recorder.vue/player.vue use it internally for start/end-upload and
    // remove-record signals) via globalProperties instead.
    if (!app.config.globalProperties.$eventBus) {
        const listeners = {}
        app.config.globalProperties.$eventBus = {
            $on(event, cb) {
                (listeners[event] = listeners[event] || []).push(cb)
            },
            $off(event, cb) {
                if (!listeners[event]) return
                listeners[event] = cb ? listeners[event].filter(fn => fn !== cb) : []
            },
            $emit(event, ...args) {
                (listeners[event] || []).forEach(cb => cb(...args))
            },
        }
    }
    app.component('audio-player', AudioRecorder.AudioPlayer)
    app.component('audio-recorder', AudioRecorder.AudioRecorder)

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
