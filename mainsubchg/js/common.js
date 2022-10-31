$.fn.tabList = function( opts ){
	return this.each(function(){
		
		var options = $.extend({}, $.fn.tabList.defaults, opts)
				
			, $btns = $("> ul .tab", this)
			, oldActive = null;
		$btns.filter(function(i, n){			
			$(this.hash).hide();
			$(this).bind("click", onChange);
		});

		// tab initialize
		$btns.eq( options.active ).trigger("click");
		
		function onChange(){
			var isAnchor = /^#/.test( $(this).attr("href") );	
					
			if( isAnchor ){	
				hide( oldActive );
				show( this );
				oldActive = this;
				
				return false;	
			}
		}
		
		function show( ele ){
			$(ele.hash).show();
			$(ele).parent().addClass("current");
		}
		
		function hide( ele ){
			if( ! ele ) return false;
			$(ele.hash).hide();
			$(ele).parent().removeClass("current");
		}

	});
};
$.fn.tabList.defaults = {
	active : 0
}
$(document).ready(function(){
	$('.cont_wrap select').selectOrDie();
	$('#POSTpopup').click(function(){
		$('#layer_popup').show();
	});
	$('#POSTpopupClose').click(function(){
		$('#layer_popup').hide();
	});

});