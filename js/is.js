jQuery(document).ready(function($) {

    // run the following script if the infinite scroll class exists
    if ( $('.infinite-scroll').length ) {

        var loading        = false;
        var trigger        = 1500;
        var page           = 2;
        var loadMoreOffset = [];
        var loadMoreHeight = [];
        var loadMore;
        var url;
        if ( is.offset ){
            trigger = is.offset;
        }
        
        if ( typeof( is.page ) !== 'undefined' && is.page > 1 ) {
            page = parseInt( is.page ) + 1;
            var scrollTo = parseInt( is.page ) - 2;
            $('.infinite-scroll .load-more').each( function( index, value ) {
                // store all the load-more co-ordinates
                storeLoadMoreData( $(this).offset().top, $(this).outerHeight(true) );
                if ( scrollTo === index ) {
                    $("html,body").animate({ 
                        scrollTop: $(this).offset().top
                    }, 500 );
                }
            });
        } else {
            $('.infinite-scroll li:last').addClass('load-more');
            loadMore = $('.infinite-scroll .load-more:last');
            storeLoadMoreData( loadMore.offset().top, loadMore.outerHeight(true) );
        }

        var scrollHandling = {
            allow: true,
            reallow: function() {
                scrollHandling.allow = true;
            },
            delay: 200 //(milliseconds) adjust to the highest acceptable value
        };

        var prev_page = 0;
        var original_url = window.location.pathname;
        var last_char = original_url.substr(-1);
        var page_segment = last_char === '/' ?  'page/' : '/page/';
        $(window).scroll(function(){

            var page_num = 1;
            for ( var i = 0; i < loadMoreOffset.length; i++ ) {
                if ( ( $(window).scrollTop() + loadMoreHeight[i] ) > loadMoreOffset[i] ) {
                    page_num = i + 2;
                }
            };

            if ( page_num != prev_page ) {

                url = window.location.pathname;
                var m = url.match(/page\/[0-9]*/);
                if ( m != null ) {
                    url = url.replace( m, 'page/' + page_num );
                } else {
                    url = url + page_segment + page_num;
                }
                // Update page number
                window.history.pushState( "", "", url );

            }
            prev_page = page_num;

            loadMore = $('.infinite-scroll .load-more:last');
            if( ! loading && scrollHandling.allow ) {
                scrollHandling.allow = false;
                setTimeout(scrollHandling.reallow, scrollHandling.delay);
                var offset = typeof( loadMore ) !== 'undefined' ? loadMore.offset().top - $(window).scrollTop() : false;

                if( offset && trigger > offset ) {
                    loading = true;
                    var data = {
                        action: 'load_more',
                        page: page,
                        template: is.template,
                        query: is.wp_query_args
                    };
                    if ( typeof is.publication !== typeof undefined ) {
                        data.publication = is.publication;
                    }
                    $.post(is.ajaxurl, data, function(response) {
                        if( response.success) {
                            $('.infinite-scroll').append( response.data );
                            $('.infinite-scroll li:last').addClass('load-more');
                            loadMore = $('.infinite-scroll .load-more:last');
                            storeLoadMoreData( loadMore.offset().top, loadMore.outerHeight(true) );
                            page = page + 1;
                            loading = false;
                        } else {
                            // console.log(res);
                        }
                    }).fail(function(xhr, textStatus, e) {
                        // console.log(xhr.responseText);
                    });

                }
            }
        });

        function storeLoadMoreData( storeOffset, storeHeight ) {
            loadMoreOffset.push( storeOffset );
            loadMoreHeight.push( storeHeight );
        }
    }
});