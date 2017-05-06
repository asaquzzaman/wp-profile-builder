//component for text field
Vue.component('wpup-email-field', {
    props: ['ele', 'index', 'col', 'row'],
    mixins: [WPUP_Mixins], 
    template: "#tmpl-wpup-email-field",

    methods: {
        //Update Element
        elementUpdate: function( row, col, ele ) {
            this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
        },
    }
});