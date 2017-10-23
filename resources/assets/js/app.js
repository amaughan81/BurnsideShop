
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*
Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});
*/

$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content')
        }
    });
    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('click', '.del-cat-btn', function() { deleteCategory($(this).data('cat-del')); });
    $(document).on('click', '.del-prod-btn', function() { deleteProduct($(this).data('prod-del')); });
    $(document).on('click', '.del-photo-btn', function() { deleteProductPhoto($(this).data('photo-id')); });
    $(document).on('click' ,'#applyVoucherCodeBtn', function() { applyVoucherCode(); });
    $(document).on('click', '.remove-coupon-btn', function() { removeVoucher($(this).data('coupon-code')); });
});

function deleteCategory(id) {
    var conf = window.confirm("Are you sure you wish to delete this category");
    if(conf) {
        var formData = $("#del_cat_"+id+"_form").serialize();
        $.ajax({
            url: '/categories/'+id,
            dataType: 'json',
            type: 'POST',
            data: formData,
            success: function(data) {
                if(data.result) {
                    $("#cat_"+id).remove();
                }
            }
        });
    }
}

function deleteProduct(id) {
    var conf = window.confirm("Are you sure you wish to delete this product?");
    if(conf) {
        var formData = $("#del_prod_"+id+"_form").serialize();
        $.ajax({
            url: '/products/'+id,
            dataType: 'json',
            type: 'POST',
            data: formData,
            success: function(data) {
                if(data.result) {
                    $("#prod_"+id).remove();
                }
            }
        });
    }
}

function deleteProductPhoto(photoID) {
    var conf = window.confirm("Are you sure you wish to delete this photo?")
    if(conf) {
        $.ajax({
            url: '/photos/'+photoID,
            dataType: 'json',
            type: 'DELETE',
            success: function(data) {
                if(data.result) {
                    $("#photo_"+photoID).remove();
                }
            }
        });
    }
}

function applyVoucherCode() {
    if($("#claimCode").val() == "" || $("#claimCode").val().length < 10) {
        alert("Please enter a valid code");
        return ;
    }

    $.ajax({
        url: "/cart/apply-coupon",
        dataType: 'json',
        type: 'POST',
        data: {
            'code': $("#claimCode").val()
        },
        success: function(data) {
            if(data.status == 'success') {
                $("#applied-coupons").prepend('<div class="alert alert-success" id="coupon-alert">'+data.message+'</div>');
                $("#applied-coupons table").append(
                    '<tr id="cc_'+data.code+'">'+
                    '<th>'+data.code+': </th>'+
                    '<td>'+data.value+' BM</td>'+
                    '<td>'+
                    '<button type="button" class="btn btn-danger btn-xs remove-coupon-btn" data-coupon-code="'+data.code+'">'+
                    '<span class="glyphicon glyphicon-trash"></span>'+
                    '</button>'+
                    '</td>'+
                    '</tr>'
                );
                $("#discount-value").html(data.discount+" BM");
                $("#grand-total").html(data.totalPrice - data.discount+" BM");

                $("#coupon-alert").fadeOut(2000);
                $("#claimCode").val("");
            } else {
                alert(data.message);
            }
        }
    });


}


function removeVoucher(code) {
    var conf = confirm("Are you sure you wish to remove this coupon?")
    if(conf) {
        $.ajax({
            url: '/cart/remove-coupon',
            dataType: 'json',
            type: 'DELETE',
            data: {
                'coupon': code
            },
            success: function(data) {
                if(data.result) {
                    $("#cc_"+code).remove();
                    $("#discount-value").html(data.discount+" BM");
                    $("#grand-total").html(data.totalPrice - data.discount+" BM");

                }
            }
        });
    }
}