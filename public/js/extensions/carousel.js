$(document).ready(function() {
    let getProductHeight = $('.product.active').height();

    $('.products').css({
        height: getProductHeight
    });

    function calcProductHeight() {
        getProductHeight = $('.product.active').height();

        if (getProductHeight == 0) {
            getProductHeight = 400
        }
        $('.products').css({
            height: getProductHeight
        });
    }

    $('#nextCarousel').on('click', function(e) {
        e.preventDefault();
        console.log('next')
        let productItem = $('.product');
        let productCurrentItem = productItem.filter('.active');

        let nextItem = productCurrentItem.next();

        productCurrentItem.removeClass('active');

        if (nextItem.length) {
            productCurrentItem = nextItem.addClass('active');
        } else {
            productCurrentItem = productItem.first().addClass('active');
        }

        calcProductHeight();
        // animateContentColor();


    });

    $('#prevCarousel').on('click', function(e) {
        e.preventDefault();

        let productItem = $('.product');
        let productCurrentItem = productItem.filter('.active');

        let prevItem = productCurrentItem.prev();

        productCurrentItem.removeClass('active');

        if (prevItem.length) {
            productCurrentItem = prevItem.addClass('active');
        } else {
            productCurrentItem = productItem.last().addClass('active');
        }

        calcProductHeight();
        // animateContentColor();
    });
});
