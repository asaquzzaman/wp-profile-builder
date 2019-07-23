//component for social fields
Vue.component('wpup-social-field', {
    mixins: [WPUP_Mixins],
    props: ['ele', 'col', 'row'],
    template: "#tmpl-wpup-social-field",
    methods: {
        //Update Element
        elementUpdate: function( row, col, ele ) {
            this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
        },
    },

});