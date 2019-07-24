wpSpearWPUPRegisterModule('profileBuilder', 'profile-builder');

import ProfileBuilder from './profile-builder.vue'

wpSpearWPUPRegisterChildrenRoute('wpup_root', 
    [   
        {
            path: 'profile-builder', 
            component: ProfileBuilder,
            name: 'profile_builder',

        }
        
    ]
);