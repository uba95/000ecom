/*global $*/

$(function () {
    
    'use strict';


// Ajax


function formAjax(myForm, myMsg, insert) {
    $(myForm).submit(function (e) { 
        $.ajax({
            method      :    'POST',
            url         :    $(this).attr('action'),
            data        :    new FormData( this ),
            processData :    false,
            contentType :    false,
            success     :    function (data) {
                                $(myMsg).fadeOut(100, function () {
                                    $(this).html(data);
                                }).fadeIn();
                                insert && $(data).hasClass('alert-success') ? $( myForm + ' :input').val('') : '';
                            }   
            });
        e.preventDefault();
    });
}

formAjax('#am-form', '#show-m', true);
formAjax('#em-form', '#show-em', false);


function clickAjax(myButton, myMethod, myMsg, $myDo) {

    $(document).on('click', myButton, function (e) {
        var el = this;
        if (confirm('Are You Sure?')) {
        $.ajax({
    
            method  :    myMethod,
            url     :    $(this).attr('href'),
            success :    function (data) {
                            alert('Member Is ' + myMsg);
                            $(el).closest($myDo ? 'tr' : el).fadeOut(800, function () {
                                $(this).remove();
                            });
                        }
        });
    }
        e.preventDefault();
    
    });
}

clickAjax('.delete-m', 'DELETE', 'Deleted', true);
clickAjax('.activate-m', 'POST', 'Activated', false);



// Toggle Plus/Minus

    $('.itog').click(function () {

        $(this).toggleClass('fa-plus fa-minus').parent().next('.panel-body').slideToggle();
    });

    $("select").selectBoxIt({autoWidth: false});

// hide placeholder

    $('[placeholder]').focus(function () {
    
        $(this).attr('phData', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    
    }).blur(function () {
    
        $(this).attr('placeholder', $(this).attr('phData'));
    });

// show password

    $('.show-pass').mousedown(function () {
        
        $(this).prev().attr('type', 'text');
        
    }).mouseup(function () {
        
        $(this).prev().attr('type', 'password');
        
    }).mouseout(function () {
        
        $(this).prev().attr('type', 'password');
    
    });
    
// confirm warning

    // $('.confirm').click(function () {
        
    //     return confirm('Are You Sure?');
    // });

// view options

    $('.cat').click(function () {
        
        $(this).find('.full-view').fadeToggle(200);
        
    });
                     
    $('.view span').click(function () {
        
        $(this).addClass('active').siblings('span').removeClass('active');
        
        if ($(this).data('view') === 'full') {
            
            $('.cat .full-view').fadeIn(200);
            
        } else {
            
            $('.cat .full-view').fadeOut(200);

        }
    });

// Img Input

    $('.up-img input').on('change', function () {

        $(this).prev().text($(this).val());

    });

});





