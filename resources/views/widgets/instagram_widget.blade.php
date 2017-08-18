<style>
    #instafeed{
        list-style:none
    }
    #instafeed li{
        float:left;
        width:200px;
        height:200px;
        margin:10px
    }
    #instafeed li img{
        max-width:100%;
        max-height:100%;
    }
</style>
<ul id="instafeed"></ul>

<script>
    var token = '1362627527.6de15d4.46dd652a7de942b3a96c39e79b571054',
            num_photos = 10,
            container = document.getElementById( 'instafeed' ),
            scrElement = document.createElement( 'script' );

    window.instagramProcessResult = function( data ) {
        for( x in data.data ){
//            console.log(data.data[x].images);
            container.innerHTML += '<li><a data-fancybox="group_2" href="' + data.data[x].images.standard_resolution.url + '"><img src="' + data.data[x].images.thumbnail.url + '"></a></li>';
        }
    }

    scrElement.setAttribute( 'src', 'https://api.instagram.com/v1/users/self/media/recent?access_token=' + token + '&count=' + num_photos + '&callback=instagramProcessResult' );
    document.body.appendChild( scrElement );
</script>