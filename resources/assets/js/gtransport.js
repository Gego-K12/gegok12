export function registerTransport(app) {
    app.component('stoppage-list', () =>
        import('./components/transport/stoppage/List.vue').then(m => m.default)
    );

    app.component('vehicle-list', () =>
        import('./components/transport/vehicle/List.vue').then(m => m.default)
    );

    app.component('add-vehicle', () =>
        import('./components/transport/vehicle/Create.vue').then(m => m.default)
    );

    app.component('edit-vehicle', () =>
        import('./components/transport/vehicle/Edit.vue').then(m => m.default)
    );

    app.component('vehicle-tab', () =>
        import('./components/transport/show/VehicleTab.vue').then(m => m.default)
    );

    app.component('route-list', () =>
        import('./components/transport/routes/List.vue').then(m => m.default)
    );

    app.component('add-route', () =>
        import('./components/transport/routes/Create.vue').then(m => m.default)
    );

    app.component('edit-route', () =>
        import('./components/transport/routes/Edit.vue').then(m => m.default)
    );

    app.component('create-transport', () =>
        import('./components/transport/detail/Create.vue').then(m => m.default)
    );

    app.component('route-tab', () =>
        import('./components/transport/routeshow/RouteTab.vue').then(m => m.default)
    );

    app.component('create-service', () =>
        import('./components/transport/service/Create.vue').then(m => m.default)
    );
}
