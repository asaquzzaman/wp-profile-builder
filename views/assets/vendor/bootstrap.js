window.wpup = {};
const wpupMixin = {};
const wpupProMixin = {};
const wpupProComment = {};
const wpSpearWPUPRouters = [];
const wpSpearWPUPComponents = [];
const wpSpearWPUPChildrenRouter = {};
const wpSpearWPUPModules = [];
const wpSpearWPUPProModules = [];
const wpSpearWPUPProAddonModules = [];
const wpSpearfilters = {};
var wpupProjects = [];

function wpSpearWPUPRegisterChildrenRoute (parentRouteName, routes) {

	routes.forEach(function(route) {
		if (wpSpearWPUPChildrenRouter.hasOwnProperty(parentRouteName)  ) {
			wpSpearWPUPChildrenRouter[parentRouteName].push(route);
		} else {
			wpSpearWPUPChildrenRouter[parentRouteName] = [route];
		}
	});
};

function wpSpearWPUPGetRegisterChildrenRoute(parentRouteName, prevRoute) {
	var prevRoute = prevRoute || [];

	if (wpSpearWPUPChildrenRouter.hasOwnProperty(parentRouteName)  ) {
		return prevRoute.concat(wpSpearWPUPChildrenRouter[parentRouteName]);
	}

	return prevRoute;
}

function wpSpearWPUPRegisterModule(module, path) {
	wpSpearWPUPModules.push(
		{
			'name': module,
			'path': path
		}
	);
}

function wpSpearWPUPProRegisterModule(module, path) {
	wpSpearWPUPProModules.push(
		{
			'name': module,
			'path': path
		}
	);
}

function wpSpearWPUPProAddonRegisterModule(module, path) {
	wpSpearWPUPProAddonModules.push(
		{
			'name': module,
			'path': path
		}
	);
}

/**
 * Add a new Filter callback to Hooks.filters
 *
 * @param tag The tag specified by apply_filters()
 * @param callback The callback function to call when apply_filters() is called
 * @param priority Priority of filter to apply. Default: 10 (like WordPress)
 */
function wpup_add_filter( tag, callback, priority ) {
    let ref = [];

    if( typeof priority === "undefined" ) {
        priority = 10;
    }

    if (jQuery.isArray(callback)) {
        ref = [ callback[0].$options.name, callback[1]];
        callback = callback[0][callback[1]];
    }

    // If the tag doesn't exist, create it.
    wpSpearfilters[tag] = wpSpearfilters[ tag ] || [];
    wpSpearfilters[tag].push( { priority: priority, callback: callback, ref: ref } );
}

/**
 * Calls filters that are stored in Hooks.filters for a specific tag or return
 * original value if no filters exist.
 *
 * @param tag A registered tag in Hook.filters
 * @options Optional JavaScript object to pass to the callbacks
 */
function wpup_apply_filters ( tag, value, options ) {

    var filters = [];

    if( typeof wpSpearfilters[tag] !== "undefined" && wpSpearfilters[tag].length > 0 ) {

        wpSpearfilters[tag].forEach( function( hook ) {

            filters[hook.priority] = filters[hook.priority] || [];
            filters[hook.priority].push( hook.callback );
        } );

        filters.forEach( function( hooks ) {

            hooks.forEach( function( callback ) {
                value = callback( value, options );
            } );

        } );
    }

    return value;
}

/**
 * Remove a Filter callback from Hooks.filters
 *
 * Must be the exact same callback signature.
 * Warning: Anonymous functions can not be removed.
 * @param tag The tag specified by apply_filters()
 * @param callback The callback function to remove
 */
function wpup_remove_filter( tag, callback ) {
    if(typeof wpSpearfilters[ tag ] === 'undefined' ) {
        return;
    }
    wpSpearfilters[ tag ].forEach( function( filter, i ) {
        if( ! jQuery.isArray(callback) && filter.callback.name === callback ) {
            wpSpearfilters[ tag ].splice(i, 1);
        } else if ( jQuery.isArray(callback) && filter.ref.length ) {
            if ( filter.ref[0] === callback[0].$options.name && filter.ref[1] === callback[1] ) {
                wpSpearfilters[ tag ].splice(i, 1);
            }
        }
    } );
}

function wpupGetIndex( itemList, id, slug) {
    var index = false;

    jQuery.each(itemList, function(key, item) {

        if (item[slug] == id) {
            index = key;
        }
    });

    return index;
}
function wpupUserCan(cap, project, user) {
    user    = user || wpup_Vars.current_user;

    if ( wpupHasManageCapability() ) {
        return true;
    }

    if ( ! wpupIsUserInProject(project, user) ) {
        return false;
    }

    if( wpupIsManager(project, user) ) {
        return true;
    }

    var role = wpupGetRole(project, user);

    if ( !role ) {
        return false;
    }

    var role_caps = wpupGetRoleCaps( project, role );

    if ( !Object.keys(role_caps).length  ) {
        return true;
    }

    if (
        role_caps.hasOwnProperty(cap)
        &&
        (
            role_caps[cap] === true
            ||
            role_caps[cap] === 'true'
        )
    ) {
        return true;
    }

    return false;

}

function wpupGetRoleCaps (project, role) {
    var default_project = {
        capabilities: {}
    },
    project = jQuery.extend(true, default_project, project );

    if( project.capabilities.hasOwnProperty(role) ) {
        return project.capabilities[role];
    } else {
        return [];
    }
}

function wpupGetRole (project, user) {
    user    = user || wpup_Vars.current_user;

    var default_project = {
        assignees: {
            data: []
        }
    },
    project = jQuery.extend(true, default_project, project );

    var index = wpupGetIndex( project.assignees.data, user.ID, 'id' );

    if ( index === false ) {
        return false;
    }

    var project_user = project.assignees.data[index];

    return project_user.roles.data.length ? project_user.roles.data[0].slug : false;
}

function wpupIsUserInProject (project, user) {
    var user    = user || wpup_Vars.current_user;
    var user_id = user.ID;
    var default_project = {
        assignees: {
            data: []
        }
    },
    project = jQuery.extend(true, default_project, project );

    var index = wpupGetIndex(project.assignees.data, user_id, 'id');

    if ( index === false ) {
        return false;
    }

    return true;
}
function wpupIsManager (project, user) {
    user    = user || wpup_Vars.current_user;

    if (wpupHasManageCapability()){
        return true;
    }
    if ( !project ){
        return false;
    }
    var default_project = {
        assignees: {
            data: []
        }
    },
    project = jQuery.extend(true, default_project, project );

    var index = wpupGetIndex( project.assignees.data, user.ID, 'id' );
    ( project.assignees.data, user.ID, 'id' );

    if ( index === false ) {
        return false;
    }

    var project_user = project.assignees.data[index];
    var role_index   = wpupGetIndex( project_user.roles.data, 'manager', 'slug' );

    if ( role_index !== false ) {
        return true;
    }

    return false;
}

function wpupHasManageCapability () {
    if ( wpup_Vars.manage_capability === '1' ){
        return true;
    }
    return false;
}
function wpupHasCreateCapability () {
    if ( wpup_Vars.manage_capability === '1' ){
        return true;
    }
    if ( wpup_Vars.create_capability === '1' ){
        return true;
    }
    return false;
}
