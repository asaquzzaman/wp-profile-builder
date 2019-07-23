//component for text field
Vue.component('wpup-password-field', {
    props: ['ele', 'index', 'col', 'row'],
    mixins: [WPUP_Mixins], 
    template: "#tmpl-wpup-password-field",
    data: function() {
        return {
            generate_password: false
        }
    },

    methods: {
        //Update Element
        elementUpdate: function( row, col, ele ) {
            this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
        },

        activePasswordField: function() {
            this.generate_password = this.generate_password ? false : true;
        },
    }
});