__webpack_public_path__ = wpup.dir_url + 'views/assets/js/';

import router from '@router/router'
import store from '@store/store'
import '@directives/directive'
import Mixin from '@helpers/mixin/mixin'
import App from './App.vue'
import '@helpers/common-components'

window.wpupBus = new Vue();

/**
 * Project template render
 */
var WPUP_Vue = {
    el: '#wpup-user-profile',
    store,
 	router,
    render: t => t(App),
}

Vue.mixin(Mixin);

new Vue(WPUP_Vue); 


