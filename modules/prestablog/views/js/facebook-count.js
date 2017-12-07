/**
 * 2008 - 2017 (c) HDClic
 *
 * MODULE PrestaBlog
 *
 * @author    HDClic <prestashop@hdclic.com>
 * @copyright Copyright (c) permanent, HDClic
 * @license   Addons PrestaShop license limitation
 * @version    4.0.3
 * @link    http://www.hdclic.com
 *
 * NOTICE OF LICENSE
 *
 * Don't use this module on several shops. The license provided by PrestaShop Addons
 * for all its modules is valid only once for a single shop.
 */

$(function(){
    $('#blog_list a.comments').each( function( i ) {
        var mark = $(this);
        $.getJSON("https://graph.facebook.com/?id="+mark.data('commentsurl'), function( json ) {
            if ( json.share ) {
                mark.html(json.share.comment_count);
            } else {
                mark.html(0);
            }
        });
    });
});
