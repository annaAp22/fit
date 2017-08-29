<div class="products-carousel-block instagram">
    <div class="products-carousel-block__title instagram__title">Звезды выбирают
        <img src="/img/logo-min.png" alt="fit2u">
    </div>
    <div class="products-carousel products-carousel_full-width js-instagram-gallery">
        <button class="btn btn_carousel-control">
            <i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
        </button>
        <div class="products-carousel__wrap products-carousel__wrap_ovh">
            <div class="products-carousel__track instagram__track" id="js-instafeed">
                {{-- Photos here --}}
            </div>
        </div>
        <button class="btn btn_carousel-control">
            <i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
        </button>
    </div>
</div>

<script>
    var token = '1362627527.6de15d4.46dd652a7de942b3a96c39e79b571054',
            num_photos = 10,
            container = document.getElementById( 'js-instafeed' ),
            scrElement = document.createElement( 'script' );

    window.instagramProcessResult = function( data ) {
        for( x in data.data ){
            container.innerHTML += '<a class="hover-zoom-dark" data-fancybox="group_2" href="' + data.data[x].images.standard_resolution.url + '" style="background-image:url(' + data.data[x].images.low_resolution.url + ')"></a>';
            if(x == (num_photos - 1) )
            {
                carouselInit('.js-instagram-gallery', {
                    margin: 0,
                    responsive: {
                        1492 : {items: 5},
                        1203 : {items: 4},
                        840 : {items: 3},
                        576 : {items: 2},
                        300 : {items: 1},
                        0 : {items: 1}
                    }
                });
            }

        }
    };

    scrElement.setAttribute( 'src', 'https://api.instagram.com/v1/users/self/media/recent?access_token=' + token + '&count=' + num_photos + '&callback=instagramProcessResult' );
    document.body.appendChild( scrElement );
</script>