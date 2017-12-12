var btn_create_save = "#btn-create-save";
var btn_save_cancel = "#btn-save-cancel";
var create_form = "#smart-crud-create-form";

var btn_edit = ".item-edit-btn";
var btn_delete = ".item-delete-btn";
var btn_edit_cancel = "#btn-edit-cancel";
var btn_edit_save = "#btn-edit-save";
var crud_container = "#smart_crud_container";
var edit_form = "#smart-crud-edit-form";
var add_btn = "#item-add-btn";

var crud_err_msg = "#crud_msg_error";
jQuery(document).ready(function () {
    jQuery('body').on('click', add_btn, function() {
        var schema = jQuery(crud_container).attr('data-schema');
        jQuery.ajax({
            url: smart_crud.ajaxurl,
            method : "GET",
            async: false,
            data: 'schema=' + schema + '&action=generateCreatePage&security=' + smart_crud.ajax_nonce,
            beforeSend : function () {
                //jQuery("#spinner_container").show();
            },
            success : function(response) {
                jQuery(crud_container).html(response);
            },
            error : function(errorThrown) {
                console.log(errorThrown);
            },
            complete : function() {
                // jQuery("#spinner_container").slideUp();
            }
        });
    });

    jQuery('body').on('click', btn_save_cancel, function() {
        loadListingPage();
    });

    jQuery('body').on('click', btn_create_save, function() {
        var schema = jQuery(crud_container).attr('data-schema');
        jQuery.ajax({
            url: smart_crud.ajaxurl,
            method : "POST",
            type : "json",
            async: false,
            data: jQuery(create_form).serialize() + '&schema='+schema+'&action=save&security=' + smart_crud.ajax_nonce,
            beforeSend : function () {
                //jQuery("#spinner_container").show();
            },
            success : function(response) {
                response = extractJsonContainingSpecificKey ( response );
                if(response.status == "success") {
                    loadListingPage();
                } else {
                    jQuery(crud_err_msg).html(response.msg);
                    jQuery(crud_err_msg).slideDown();
                    jQuery('html, body').animate({
                        scrollTop: jQuery(crud_err_msg).parent().offset().top
                    }, 1000);
                }
            },
            error : function(errorThrown) {
                console.log(errorThrown);
            },
            complete : function() {
                // jQuery("#spinner_container").slideUp();
            }
        });
    });

    jQuery('body').on('click', btn_edit, function() {
        var item_id = jQuery(this).attr('data-item-id');
        var schema = jQuery(crud_container).attr('data-schema');
        jQuery.ajax({
            url: smart_crud.ajaxurl,
            method : "GET",
            async: false,
            data: 'schema='+schema+'&id='+item_id+'&action=generateEditPage&security=' + smart_crud.ajax_nonce,
            beforeSend : function () {
                //jQuery("#spinner_container").show();
            },
            success : function(response) {
                jQuery(crud_container).html(response);
            },
            error : function(errorThrown) {
                console.log(errorThrown);
            },
            complete : function() {
               // jQuery("#spinner_container").slideUp();
            }
        });
    });

    jQuery('body').on('click', btn_edit_cancel, function() {
        loadListingPage();
    });

    jQuery('body').on('click', btn_edit_save, function() {
        var item_id = jQuery(this).attr('data-item-id');
        var schema = jQuery(crud_container).attr('data-schema');
        jQuery.ajax({
            url: smart_crud.ajaxurl,
            method : "POST",
            type : "json",
            async: false,
            data: jQuery(edit_form).serialize() + '&schema='+schema+'&action=update&security=' + smart_crud.ajax_nonce,
            beforeSend : function () {
                //jQuery("#spinner_container").show();
            },
            success : function(response) {
                response = extractJsonContainingSpecificKey ( response );
                if(response.status == "success") {
                    loadListingPage();
                } else {
                    jQuery(crud_err_msg).html(response.msg);
                    jQuery(crud_err_msg).slideDown();
                    jQuery('html, body').animate({
                        scrollTop: jQuery(crud_err_msg).parent().offset().top
                    }, 1000);
                }
            },
            error : function(errorThrown) {
                console.log(errorThrown);
            },
            complete : function() {
                // jQuery("#spinner_container").slideUp();
            }
        });
    });

    jQuery('body').on('click', btn_delete, function() {
        var item_id = jQuery(this).attr('data-item-id');
        var schema = jQuery(crud_container).attr('data-schema');
        var r = confirm('Are you sure?');
        if( r ==true ) {
            jQuery.ajax({
                url: smart_crud.ajaxurl,
                method : "POST",
                type : "json",
                async: false,
                data: '&schema=' + schema + '&id=' + item_id + '&action=delete&security=' + smart_crud.ajax_nonce,
                beforeSend : function () {
                    //jQuery("#spinner_container").show();
                },
                success : function(response) {
                    loadListingPage();
                },
                error : function(errorThrown) {
                    console.log(errorThrown);
                },
                complete : function() {
                    // jQuery("#spinner_container").slideUp();
                }
            });
        } else {
            return false;
        }
    });

    function loadListingPage() {
        jQuery(crud_err_msg).slideUp();
        var schema = jQuery(crud_container).attr('data-schema');
        jQuery.ajax({
            url: smart_crud.ajaxurl,
            method : "GET",
            async: false,
            data: 'schema='+schema+'&action=generateListingPage&security=' + smart_crud.ajax_nonce,
            beforeSend : function () {
                //jQuery("#spinner_container").show();
            },
            success : function(response) {
                jQuery(crud_container).html(response);
            },
            error : function(errorThrown) {
                console.log(errorThrown);
            },
            complete : function() {
                // jQuery("#spinner_container").slideUp();
            }
        });
    }

    function extractJsonContainingSpecificKey ( myString ) {
        var myRegexp = /.*({"status".*})/g;
        var match = myRegexp.exec( myString );
        var json = false;
        if ( match !== null ) {
            json = JSON.parse(match[1]);
        }
        return json;
    }
});


jQuery(document).ready(function () {
    jQuery(function () {
        jQuery("#date_from").datepicker();
        jQuery("#date_from").datepicker("option", "dateFormat", "yy-mm-dd");
        jQuery("#date_to").datepicker();
        jQuery("#date_to").datepicker("option", "dateFormat", "yy-mm-dd");
    });
});
