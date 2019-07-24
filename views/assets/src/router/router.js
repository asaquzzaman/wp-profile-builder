
import '@components/profile-builder/router';
import Empty from '@components/root/init.vue';

wpSpearWPUPRouters.push({
	path: '/',
    component:  Empty,
    name: 'wpup_root',

	children: wpSpearWPUPGetRegisterChildrenRoute('wpup_root')
});

var router = new VueRouter({
	routes: wpSpearWPUPRouters,
});


export default router;
