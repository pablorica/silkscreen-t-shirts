(function($){
	var onResizeTheme = function(){
		var _smt = $('header nav').length > 0 ? ($('header nav').offset().top + $('header nav').height())+'px' : '';
		$('header nav ul li .sub-menu').css({'top': _smt});
		if($('.pinterestLike').length > 0){
			$('.pinterestLike a').removeAttr('style');
			$('.pinterestLike a').each(function(){
				var _prevup = $('.pinterestLike').width() < 992 ? $(this).prev().prev() : $(this).prev().prev().prev();
				if(_prevup.length > 0){
					var _ret = _prevup.position().top + parseInt(_prevup.css('margin-top')) + _prevup.height() + 30 - $(this).position().top;
					$(this).css('margin-top', _ret+'px');
				}
			});
		}
	};
	$('[rel^="prettyPhoto"]').prettyPhoto({social_tools: '', deeplinking: false});
	$('header .mobile-menu').on('click', function(){
		$('header').toggleClass('in');
		return false;
	});
	if($('.filtros').length > 0){
		$('.filtros a[href=#]').on('click', function(){
			$(this).parent().parent().next().find('a').hide();
			$(this).parent().parent().next().find('a.'+$(this).attr('e')).show();
			$('.filtros a[href=#]').removeClass('current');
			$(this).addClass('current');
			return false;
		});
	}
	$(window).on('load resize', onResizeTheme);
	$(document).ready(onResizeTheme);
})(jQuery);