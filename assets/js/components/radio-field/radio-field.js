//component for radio field
Vue.component('wpup-radio-field', {
    props: ['ele', 'index', 'col', 'row'],
    mixins: [WPUP_Mixins], 
    template: "#tmpl-wpup-radio-field",

    methods: {
        //Update Element
        elementUpdate: function( row, col, ele ) {
            this.setHook( 'updateEle', {row: row, col: col, ele: ele} );
        },

        getFieldVal: function( ele, field_val ) {
            return ele.radio_options[field_val];
        }
    }
});