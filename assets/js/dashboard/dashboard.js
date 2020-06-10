// ------ style ------
import '../../css/dashboard/dashboard.scss';

// ------ jquery and bootstrap basics ------
import $ from 'jquery'
// make jQuery global
global.$ = global.jQuery = $;

import 'bootstrap/js/dist/modal';
import 'bootstrap/js/dist/collapse';
import 'moment/locale/fr';
import 'daterangepicker/daterangepicker';
import 'select2/dist/js/select2.full.min';

$.fn.select2.defaults.set('theme', 'bootstrap');
$.fn.select2.defaults.set('placeholder', '-- Sélectionnez une valeur');
$.fn.select2.defaults.set('allowClear', true);
$.fn.select2.defaults.set('containerCssClass', 'form-control');

// ------ dashboard ------
import './layout/menu';
import './components/autocomplete-fields';
import './components/button-choice-group';
import './components/date';
import './components/google-autocomplete';
import './components/formatted-number';
import './components/resize-tabs';
import './../components/uploadFiles';
import './components/vat-number';
