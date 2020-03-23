(function($){
	$('.woo-product1 .big .arr').on('click', function(){
		var _last	= $('.woo-product1 .list a').last(),
		_first		= $('.woo-product1 .list a').first(),
		_thumb		= $('.woo-product1 .list a[e='+$(this).parent().attr('e')+']');
		if($(this).hasClass('right') && _thumb.next().length > 0)	_thumb.next().click();
		else if($(this).hasClass('right'))							_first.click();
		else if(_thumb.prev().length > 0)							_thumb.prev().click();
		else														_last.click();
		return false;
	});
	$('.woo-product1 .list a').on('click', function(){
		$('.woo-product1 .big img').attr({'src': $(this).find('img').attr('src'), 'alt': $(this).find('img').attr('alt')});
		$('.woo-product1 .big p').html($(this).find('img').attr('alt'));
		$('.woo-product1 .big').attr('e', $(this).attr('e'));
		return false;
	});
	$('.woo-slide .arr').on('click', function(){
		var _target	= $(this).parent().find('.woo-slide-box'),
		_animation	= +_target.attr('left') +($(this).hasClass('right') ? -100 : 100);
		if(_animation < (+_target.attr('pages') * -100) + 100)	_animation = 0;
		else if(_animation > 0)									_animation = (+_target.attr('pages') * -100) + 100;
		_target.attr({'left': _animation});
		_target.stop(true).animate({'margin-left': _animation+'%'}, 500);
		return false;
	});
})(jQuery);