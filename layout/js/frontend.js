/*global $, confirm*/

$("select").selectBoxIt({
    
    autoWidth: false
    
});

$(function () {
    
    'use strict';

// Ajax

$('#add_comment_submit').click(function () {

    $.ajax({

        method  :    'POST',
        url     :    $('#add_comment_form').attr('action'),
        data    :    $('#add_comment_form :input').serializeArray(),
        success :    function (data) {
                        $('#show_comment').html(data);
                        $(data).is(':contains("Added")') ? $('#add_comment_form :input').val('') : ''
                    }
    });
});

$('#add_comment_form').submit(function (e) { 

    e.preventDefault();
    // $(this).children(':input').val('');

});



// Login/Sign Up

    //    $('.upper-bar .sign').click(function () {
    //
    //        $($(this).find('[data-class="${.signup}"]')).addClass('selected').siblings().removeClass('selected');
    //        $('.login-page form').hide();
    ////        $($(this).find('[data-class="${.signup}"]')).fadeIn(200);
    //        console.log($($(this).find('[data-class="${.signup}"]')));
    //
    //
    //    });
        // $('.login-page h1 span').click(function () {

        //     $(this).addClass('selected').siblings().removeClass('selected');
        //     $('.login-page form').hide();
        //     $($(this).data('class')).fadeIn(200);
        // });


// Hide Upper Bar

$(window).scroll(function () {

    if ($(this).scrollTop() < 40) {

        $('.navbar').css({

            'z-index' : '1',
            'top' : '60px'
        });

        $('.upper-bar').fadeIn(600);

    } else {

        $('.upper-bar').fadeOut(600);
        $('.navbar').css({

            'z-index' : '2',
            'top' : '0px'

        });

    }

});

    // var prev = $(window).scrollTop();

    // $(window).scroll(function () {

    //     var current = $(this).scrollTop();
    //     if (current < prev) {

    //         $('.upper-bar').animate({
                
    //             top : "0px"
            
    //         }, 100);
    //     } else {

    //         $('.upper-bar').animate({
                
    //             top : "-200px"
            
    //         }, 100);
    //     }
    //     prev = current;
    // });


// Toggle Plus/Minus

        $('.itog').click(function () {

            $(this).toggleClass('fa-plus fa-minus').parent().next('p').slideToggle();
        });


$('.pend i').hover(
    function () {
        $(this).prev().fadeIn();
    }, function () {
        $(this).prev().fadeOut();
    }

    );

// Ad Live Preview
    
    $('.live').on('change', function () {
            
        $($(this).data('live')).text($(this).val());
        
    });

// Live Img
    
    $('.up-img input').change(function () {
        
        $(this).prev().text($(this).val());

        $('.live-prev img').attr('src', window.URL.createObjectURL(this.files[0]));
        
    });

// hide placeholder

    $('[placeholder]').focus(function () {
    
        $(this).attr('phData', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    
    }).blur(function () {
    
        $(this).attr('placeholder', $(this).attr('phData'));
    });
    
// confirm warning

    $('.confirm').click(function () {
        
        return confirm('Are You Sure?');
    });

});

$(function(){
    function is_touch_device() {
        return 'ontouchstart' in window        // works on most browsers 
        || navigator.maxTouchPoints;       // works on IE10/11 and Surface
    };

    if(!is_touch_device() && $('.navbar-toggle:hidden')){
      $('.dropdown-menu', this).css('margin-top',0);
      $('.dropdown').hover(function(){ 
          $('.dropdown-toggle', this).trigger('click');
          //uncomment below to make the parent item clickable
          $('.dropdown-toggle', this).toggleClass("disabled"); 
      },);			
    }
});





