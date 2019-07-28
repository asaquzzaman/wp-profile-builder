wpSpearWPUPRegisterModule('myProfile', 'my-profile');

import MyProfile from './my-profile.vue'

wpSpearWPUPRegisterChildrenRoute('wpup_root', 
    [   
        {
            path: 'my-profile', 
            component: MyProfile,
            name: 'my_profile',

        }
        
    ]
);