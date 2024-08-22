/* -------------------------------PreLoader-------------------------------------   */
$(window).on("load",function(){
    $('.loader-container').fadeOut(3500,function(){
        $(this).remove();
    });
});
$(document).ready(function() {
/* -------------------------------mobile menu-------------------------------------   */
    $('.mobile-menu').click(function(){
        $('.header-3-container').animate({left: '0px'},300);
        $('.mobile-close').animate({left: '90%'},400);
        $('.header-3').animate({left: '0px'},500);
    });
    $('.mobile-close').click(function(){
        $('.header-3-container').animate({left: '-800px'},500);
        $('.mobile-close').animate({left: '-300px'},400);
        $('.header-3').animate({left: '-800px'},300);
    });
/* -------------------------------for sticky navbar-------------------------------------   */
    $('#category-section').waypoint(function(direction) {

        if(direction == 'down') {
            $('.header-2').addClass('fixed');
        } else {
            $('.header-2').removeClass('fixed');
        }

    },{
        offset: '150px'
    });
/* -------------------------------banner slider-------------------------------------   */
    $('.banner-container').owlCarousel({
        // margin:10,
        nav:true,
        dots: true,
        loop: true,
        responsive:{
            0:{
                items:1
            },
            900:{
                items:1
            },
            1000:{
                items:1,
            },
            1200:{
                items:1
            }
        }

    })
/* -------------------------------products slider-------------------------------------   */
    $('.product-container').owlCarousel({
        // margin:10,
        nav:true,
        dots: false,
        responsive:{
            0:{
                items:2,
                dots: true,
                nav: false,
            },
            600:{
                items:3,
                dots: true,
                nav: false,
            },
            900:{
                items:2
            },
            1000:{
                items:3,
                nav:true,
            },
            1200:{
                items: 4
            }
        }
    })
/* -------------------------------brands slider-------------------------------------   */
    $('.brand-container').owlCarousel({
        // margin:10,
        nav:false,
        dots: false,
        responsive:{
            0:{
                items:2
            },
            600:{
                items:3
            },
            900:{
                items:2
            },
            1000:{
                items:3,
            },
            1200:{
                items: 5
            }
        }
    })
/* -------------------------------product details-------------------------------------   */
    $('.small-img-slider').owlCarousel({
        // margin:10,
        nav:true,
        dots: false,
        responsive:{
            0:{
                items:2
            },
            600:{
                items:3
            },
            900:{
                items:2
            },
            1000:{
                items:3,
            },
            1200:{
                items: 4
            }
        }
    })

    $('.small-img img').click(function(){
        $('.small-img img').removeClass('active');
        $(this).addClass('active');

        let image = $(this).attr('src');
        $('.big-img img').attr('src',image);

    });

});
