wpSpearWPUPRegisterModule('users', 'users');

import Users from './users.vue'
import SingleUser from './single-user-view.vue'
//import UserLists from './user-lists.vue'

wpSpearWPUPRegisterChildrenRoute('wpup_root', 
    [   
        {
            path: 'users', 
            component: Users,
            name: 'users',
            children: [
                // { 
                // 	path: '/users/:user_id', 
                // 	component: SingleUser, 
                // 	name: 'single_user' 
                // },
                 // { 
                 // 	path: ':page_number/page', 
                 // 	component: WPUP_Member_Init, 
                 // 	name: 'pagination' 
                 // },
            ] 

        },
        { 
            path: 'users/:user_id', 
            component: SingleUser, 
            name: 'single_user' 
        }
        
    ]
);