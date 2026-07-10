export function registerFees(app) {

    // =====================
    // Fees
    // =====================
    app.component('fee-list', () =>
        import('./components/fee/List.vue')
    );

    app.component('fee-list-tab', () =>
        import('./components/fee/Tab.vue')
    );

    app.component('add-fee-tab', () =>
        import('./components/fee/addTab.vue')
    );

    app.component('edit-fee-non-structural', () =>
        import('./components/fee/nonStructuralEdit.vue')
    );

    app.component('edit-fee-structural', () =>
        import('./components/fee/structuralEdit.vue')
    );

    app.component('show-fee-details', () =>
        import('./components/fee/FeePaymentList.vue')
    );


    // =====================
    // Fee Group
    // =====================
    app.component('fee-group-list', () =>
        import('./components/feegroup/List.vue')
    );

    app.component('create-fee-group', () =>
        import('./components/feegroup/Create.vue')
    );

    app.component('edit-fee-group', () =>
        import('./components/feegroup/Edit.vue')
    );


    // =====================
    // Dashboard Fees
    // =====================
    app.component('unpaid-fees', () =>
        import('./components/dashboard/FeeList.vue')
    );

    app.component('unpaid-fee-details', () =>
        import('./components/dashboard/UnpaidList.vue')
    );
}