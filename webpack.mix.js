const mix = require("laravel-mix");
const path = require("path");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 | Tailwind 3 runs via PostCSS (postcss.config.js); no Mix Tailwind/PurgeCSS.
 */

// Resolve 'vue' to a default-export shim for Vue 2 packages that use "import Vue from 'vue'"
mix.webpackConfig({
    plugins: [
        new (class VueCompatPlugin {
            apply(compiler) {
                compiler.hooks.normalModuleFactory.tap("VueCompatPlugin", (nmf) => {
                    nmf.hooks.beforeResolve.tap("VueCompatPlugin", (resolveData) => {
                        if (
                            resolveData.request === "vue" &&
                            resolveData.contextInfo.issuer &&
                            (resolveData.contextInfo.issuer.includes("vue-image-lightbox-carousel") ||
                                (resolveData.contextInfo.issuer.includes("@fullcalendar/vue") &&
                                    !resolveData.contextInfo.issuer.includes("@fullcalendar/vue3")))
                        ) {
                            resolveData.request = path.resolve(
                                __dirname,
                                "resources/assets/js/vue-default-shim.js"
                            );
                        }
                    });
                });
            }
        })(),
    ],
});

mix.js("resources/assets/js/app.js", "public/js")
    .vue({ version: 3 })
    .extract()
    .sass("resources/assets/sass/app.scss", "public/css");

// Font Awesome is linked directly from the layout <head> (not imported via
// app.js) so icons render on first paint instead of popping in after the JS
// bundle executes and injects the styles.
mix.copy("node_modules/@fortawesome/fontawesome-free/css/all.min.css", "public/vendor/fontawesome/css/all.min.css")
    .copyDirectory("node_modules/@fortawesome/fontawesome-free/webfonts", "public/vendor/fontawesome/webfonts");

if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}
