<template>
	<div>
	    <router-view></router-view>
    </div>
</template>

<script>
	export default {
		created () {
			this.registerModule();
		},
		methods: {
			registerModule () {
                let self = this;

                wpSpearWPUPModules.forEach(function(module) {
                    let store = require('./components/'+module.path+'/store.js');
                    self.registerStore(module.name, store.default );
                });
            }
		}
	}
</script>