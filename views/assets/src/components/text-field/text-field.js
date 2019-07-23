//component for text field
Vue.component('wpup-text-field', {
    props: ['ele', 'index', 'col', 'row'],
    mixins: [WPUP_Mixins], 
    template: "#tmpl-wpup-text-field",

    methods: {
        //Update Element
        elementUpdate: function( row, col, ele ) {
            this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
        }
    }
});