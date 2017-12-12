var btn_add_new_relational_block = '#btn_add_new_relational_block';
var realtional_field_container = '#realtional_field_container';
var relational_field = '.relational_field';

var schema = '.schema';
var schema_attr = '.schema_attr';
var related_schema = '.related_schema';
var related_schema_attr = '.related_schema_attr';
var related_schema_attr_label = '.related_schema_attr_label';
var btn_add_relational_data = '#btn_add_relational_data';
var btn_remove_block = ".remove-relational_field_block";
var msg_wrapper = "#etm_msg_wrapper";

var relational_options = '';
jQuery(document).ready(function () {
    jQuery('body').on('click', btn_add_new_relational_block, function() {
        var cloned_elem = jQuery(relational_field).first().clone();
        cloned_elem.find(schema).val([]);
        cloned_elem.find(schema_attr).empty();
        cloned_elem.find(related_schema).val([]);
        cloned_elem.find(related_schema_attr).empty();
        cloned_elem.find(related_schema_attr_label).empty();
        jQuery(realtional_field_container).append(cloned_elem);
    });

    jQuery('body').on('change', schema, function() {
        var elem = jQuery(this);
        var schema = elem.val();
        jQuery.ajax({
            url: easy_table_manager_options.ajaxurl,
            method : "GET",
            async: false,
            data: 'schema=' + schema + '&action=etmo_generateSchemaAttributesOptions&security=' + smart_crud.ajax_nonce,
            beforeSend : function () {
                //jQuery("#spinner_container").show();
            },
            success : function(response) {
                elem.closest(relational_field).find(schema_attr).html(response);
            },
            error : function(errorThrown) {
                console.log(errorThrown);
            },
            complete : function() {
                // jQuery("#spinner_container").slideUp();
            }
        });
    });

    jQuery('body').on('change', related_schema, function() {
        var elem = jQuery(this);
        var schema = elem.val();
        jQuery.ajax({
            url: easy_table_manager_options.ajaxurl,
            method : "GET",
            async: false,
            data: 'schema=' + schema + '&action=etmo_generateSchemaAttributesOptions&security=' + smart_crud.ajax_nonce,
            beforeSend : function () {
                //jQuery("#spinner_container").show();
            },
            success : function(response) {
                elem.closest(relational_field).find(related_schema_attr).html(response);
                elem.closest(relational_field).find(related_schema_attr_label).html(response);
            },
            error : function(errorThrown) {
                console.log(errorThrown);
            },
            complete : function() {

            }
        });
    });

    jQuery('body').on('click', btn_add_relational_data, function() {
        var r = confirm('Are you sure?');
        if( r == false ) {
            return;
        }
        var relational_fields_arr = {};
        rf_arr = {};
        jQuery(relational_field).each( function () {
            var s_val = jQuery(this).find(schema).val();
            var s_attr_val = jQuery(this).find(schema_attr).val();
            var rs_val = jQuery(this).find(related_schema).val();
            var rs_attr_val = jQuery(this).find(related_schema_attr).val();
            var rs_attr_label = jQuery(this).find(related_schema_attr_label).val();

            var rf = {};
            if(s_attr_val !== '' && rs_val !== '' && rs_attr_label !='') {
                rf[s_attr_val] = [rs_val, rs_attr_val, rs_attr_label, false];
                console.log(rf_arr[s_val]);
                if (s_val in rf_arr) {
                    rf_arr[s_val][s_attr_val] = [rs_val, rs_attr_val, rs_attr_label, false];
                } else {
                    rf_arr[s_val] = rf;
                }
            } else {
                alert("Fill Up all The Required Fields");
            }
        });
        // console.log(rf_arr);
        console.log(JSON.stringify(rf_arr));
        var related_fields = JSON.stringify(rf_arr);

        jQuery.ajax({
            url: easy_table_manager_options.ajaxurl,
            method : "GET",
            async: true,
            data: 'related_fields=' + related_fields + '&action=etmo_saveRelatedFields&security=' + smart_crud.ajax_nonce,
            beforeSend : function () {
                //jQuery("#spinner_container").show();
            },
            success : function(response) {
                jQuery(msg_wrapper).html("Relational fields were saved successfully");
                jQuery(msg_wrapper).removeClass('alert-danger').addClass('alert-success');

                jQuery('html, body').animate({
                    scrollTop: jQuery(msg_wrapper).parent().offset().top - parseInt(100)
                }, 1000);
                setTimeout(function() {jQuery(msg_wrapper).slideUp();}, 5000);
                jQuery(msg_wrapper).show();
            },
            error : function(errorThrown) {
                console.log(errorThrown);
            },
            complete : function() {
                // jQuery("#spinner_container").slideUp();
            }
        });
    });

    jQuery('body').on('click', btn_remove_block, function() {
        var elem = jQuery(this);
        elem.closest(relational_field).remove();
    });
});
