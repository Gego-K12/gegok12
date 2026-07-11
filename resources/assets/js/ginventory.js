import Paginate from 'vuejs-paginate-next'
import VueHtmlToPaper from 'vue-html-to-paper'

export function registerInventory(app) {
    // vuejs-paginate-next is Vue-3-native, safe to register as a plain component.
    app.component('paginate', Paginate)

    // vue-html-to-paper's install() targets Vue 2's global `Vue.prototype`
    // (app.use() passes the Vue 3 app instance instead, which has no
    // .prototype), so it would crash the same way vue-audio-recorder did
    // for videoroom. Its install() only assigns a plain, self-contained
    // function onto the prototype (no other Vue-2 dependency), so passing
    // a shim object whose .prototype is the app's globalProperties gets
    // the exact same effect ($htmlToPaper available on every component)
    // without needing to reimplement the library.
    VueHtmlToPaper.install({ prototype: app.config.globalProperties })

    // stock
    app.component('product-list', () =>
        import('./components/stock/product/ProductList.vue').then(m => m.default)
    );

    app.component('add-stock-product', () =>
        import('./components/stock/product/Create.vue').then(m => m.default)
    );

    app.component('edit-stock-product', () =>
        import('./components/stock/product/Edit.vue').then(m => m.default)
    );

    // purchase
    app.component('add-purchase', () =>
        import('./components/stock/purchase/Create.vue').then(m => m.default)
    );

    app.component('list-purchase', () =>
        import('./components/stock/purchase/List.vue').then(m => m.default)
    );

    app.component('edit-purchase', () =>
        import('./components/stock/purchase/Edit.vue').then(m => m.default)
    );

    // sales
    app.component('show-sales', () =>
        import('./components/stock/sales/List.vue').then(m => m.default)
    );

    app.component('add-sales', () =>
        import('./components/stock/sales/Create.vue').then(m => m.default)
    );

    app.component('edit-sales', () =>
        import('./components/stock/sales/Edit.vue').then(m => m.default)
    );

    // return order
    app.component('show-return-order', () =>
        import('./components/stock/returnorder/List.vue').then(m => m.default)
    );

    app.component('add-return-order', () =>
        import('./components/stock/returnorder/Create.vue').then(m => m.default)
    );

    app.component('edit-return-order', () =>
        import('./components/stock/returnorder/Edit.vue').then(m => m.default)
    );

    // inventory
    app.component('showcategory', () =>
        import('./components/inventory/category/List.vue').then(m => m.default)
    );

    app.component('showsubcategory', () =>
        import('./components/inventory/category/subcategory/List.vue').then(m => m.default)
    );

    app.component('categoryvendor', () =>
        import('./components/inventory/category/vendor/List.vue').then(m => m.default)
    );

    app.component('addcategoryvendor', () =>
        import('./components/inventory/category/vendor/Create.vue').then(m => m.default)
    );

    app.component('showvendor', () =>
        import('./components/inventory/vendor/List.vue').then(m => m.default)
    );

    app.component('addvendor', () =>
        import('./components/inventory/vendor/Create.vue').then(m => m.default)
    );

    app.component('editvendor', () =>
        import('./components/inventory/vendor/Edit.vue').then(m => m.default)
    );

    app.component('showlocation', () =>
        import('./components/inventory/location/List.vue').then(m => m.default)
    );

    app.component('showproduct', () =>
        import('./components/inventory/product/List.vue').then(m => m.default)
    );

    app.component('addproduct', () =>
        import('./components/inventory/product/Create.vue').then(m => m.default)
    );

    app.component('editproduct', () =>
        import('./components/inventory/product/Edit.vue').then(m => m.default)
    );

    app.component('product-tab', () =>
        import('./components/inventory/productdetail/ProductTab.vue').then(m => m.default)
    );

    app.component('product-qrcode', () =>
        import('./components/inventory/productdetail/productqrcode.vue').then(m => m.default)
    );

    app.component('product-detail', () =>
        import('./components/inventory/productdetail/productdetails.vue').then(m => m.default)
    );
}
