<?php

use Illuminate\Support\Facades\Route;

// Sections moved here from the admin portal, one at a time -- see each
// block's own comment for why.

// Master data (Countries/States/Cities) -- global reference tables, not
// scoped to any school, so they moved here from admin's Settings section
// (school admins have no use for them). Same Livewire components as
// before (App\Livewire\Admin\Setting\*), which have no admin-specific
// authorization or school_id scoping of their own -- purely route-gated.

// City
Route::get('setting/cities', function () {
    return view('admin.setting.cities');
})->name('superadmin.setting.cities');

Route::get('setting/city/create', function () {
    return view('admin.setting.cityform');
})->name('admin.setting.cities.create');

Route::get('setting/city/update/{id}', function ($id) {
    return view('admin.setting.cityform', compact('id'));
})->name('admin.setting.cities.update');

Route::get('setting/city/detail/{id}', function ($id) {
    return view('admin.setting.citydetail', compact('id'));
})->name('admin.setting.city.detail');

// Country
Route::get('setting/countries', function () {
    return view('admin.setting.countries');
})->name('admin.setting.countries');

Route::get('setting/country/create', function () {
    $id = '';

    return view('admin.setting.countryform', compact('id'));
})->name('admin.setting.countries.create');

Route::get('setting/country/update/{id}', function ($id) {
    return view('admin.setting.countryform', compact('id'));
})->name('admin.setting.countries.update');

Route::get('setting/country/detail/{id}', function ($id) {
    return view('admin.setting.countrydetail', compact('id'));
})->name('admin.setting.countries.detail');

// State
Route::get('setting/states', function () {
    return view('admin.setting.states');
})->name('admin.setting.states');

Route::get('setting/state/create', function () {
    $id = '';

    return view('admin.setting.stateform', compact('id'));
})->name('admin.setting.states.create');

Route::get('setting/state/update/{id}', function ($id) {
    return view('admin.setting.stateform', compact('id'));
})->name('admin.setting.states.update');

Route::get('setting/state/detail/{id}', function ($id) {
    return view('admin.setting.statedetail', compact('id'));
})->name('admin.setting.states.detail');

// Addons (moved from admin's "Upgrades" -- Purchase Modules catalog +
// Purchase History). Note: PurchaseHistory's Livewire component queries an
// external API keyed by the logged-in user's own email/domain, not a
// school_id column, so a site admin viewing this sees data tied to their
// own account, not a specific school's -- moved anyway per explicit
// instruction, despite that mismatch.
Route::get('/addon', function () {
    return view('admin.addon.index');
});

Route::get('/addon/{slug}/detail', function ($slug) {
    return view('admin.addon.detail', compact('slug'));
});

Route::get('/payment/razorpay/checkout', function () {
    return view('admin.addon.razorpay');
});

Route::get('/purchase/addon/histories', function () {
    return view('admin.addon.purchase-history');
});
