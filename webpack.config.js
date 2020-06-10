const Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addStyleEntry('app_vendor_style', './assets/css/vendor.scss')
    .addEntry('app', './assets/js/app.js')

    .addEntry('app_home', './assets/js/pages/home')
    .addEntry('app_home_faq', './assets/js/pages/faq')

    .addEntry('dashboard', './assets/js/dashboard/dashboard.js')
    .addEntry('dashboard_form_company', './assets/js/dashboard/form/company/company.js')
    .addEntry('dashboard_form_enterprise', './assets/js/dashboard/form/enterprise/enterprise.js')
    .addEntry('dashboard_form_project', './assets/js/dashboard/form/project/project.js')
    .addEntry('dashboard_form_bond', './assets/js/dashboard/form/bond/bond.js')
    .addEntry('dashboard_form_bond_transfert', './assets/js/dashboard/form/bond/transfert/transfert.js')
    .addEntry('dashboard_form_lot_with_property_share_percent', './assets/js/dashboard/form/lot/lot_with_property_share_percent.js')
    .addEntry('dashboard_form_property_task', './assets/js/dashboard/form/property/task/task.js')
    .addEntry('dashboard_form_property_plot_switch_if_serviced', './assets/js/dashboard/form/property/plot_switch_if_serviced.js')
    .addEntry('dashboard_form_property_date', './assets/js/dashboard/form/property/date.js')
    .addEntry('dashboard_form_entity_existing', './assets/js/dashboard/form/associate/entity-existing.js')



    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    .enableSassLoader((options) => {
        options.sourceComments = !Encore.isProduction();
        options.outputStyle = 'compressed';
    }, {})
    .enablePostCssLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    .addPlugin(new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/))

;

module.exports = Encore.getWebpackConfig();
