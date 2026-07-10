export function registerChat(app) {
    app.component('add-roommember', () =>
        import('./components/chat/room/AddMember.vue').then(m => m.default)
    );

    app.component('create-room', () =>
        import('./components/chat/room/Create.vue').then(m => m.default)
    );

    app.component('edit-room', () =>
        import('./components/chat/room/Edit.vue').then(m => m.default)
    );

    app.component('join-chat', () =>
        import('./components/chat/Joinnotification.vue').then(m => m.default)
    );
}
