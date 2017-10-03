/*
* @Author: suifengtec
* @Date:   2017-04-23 14:22:07
* @Last Modified by:   suifengtec
* @Last Modified time: 2017-04-24 13:20:24
*/

'use strict';
jQuery(document).ready(function($) {
	
$(window).scroll(function () {
        $(this).scrollTop() > 20 ? $("#back-to-top").fadeIn() : $("#back-to-top").fadeOut();
    });


$("#back-to-top").on("click", function (e) {
        return e.preventDefault(), $("html, body").animate({
            scrollTop: 0
        }, 20), !1
    });




$('.post-delete').on('click',function(e){

	var postId = $(this).data('postid');
	window.location.href = 'delete.php?post-id='+ postId;

});



$('.post-favorite').on('click',function(e){
	e.preventDefault();
	var postId = $(this).data('postid');
	var ref = $(this).data('ref');
	window.location.href = 'do-favorite.php?like=1&post-id='+ postId+ '&ref='+encodeURIComponent(ref);

});

$('.post-unfavorite').on('click',function(e){

	e.preventDefault();
	var postId = $(this).data('postid');
	var ref = $(this).data('ref');
	window.location.href = 'do-favorite.php?like=0&post-id='+ postId+ '&ref='+encodeURIComponent(ref);
})


});