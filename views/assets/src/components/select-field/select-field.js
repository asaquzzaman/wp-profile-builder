//component for select field
Vue.component('wpup-select-field', {
    props: ['ele', 'index', 'col', 'row'],
    mixins: [WPUP_Mixins], 
    template: "#tmpl-wpup-select-field",

    methods: {
        //Update Element
        elementUpdate: function( row, col, ele ) {
            this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
        },

        getFieldVal: function( ele, field_val ) {
            return ele.select_options[field_val];
        }
    }
});