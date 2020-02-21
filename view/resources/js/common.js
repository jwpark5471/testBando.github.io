var winH , 
	 contH,
	 fooH,
	 headH,
	 $header;

$(document).ready(function(){
	$header = $('#header');
	contH = $('#container').outerHeight();
	contHeightSetting();
	HeaderLeftMov();

	$(window).resize(function(){
		contHeightSetting();
	});

	$(window).scroll(function(event){
		HeaderLeftMov();
	});
		
	$("a").each(function(){
		if( $(this).attr("href") == "#" || $(this).attr("href") == "#none" || $(this).attr("href") == ""){
			$(this).attr("href", "javascript:void(0)");
		}
	});

// file_ui
	$('.file_ui').each(function(){
		var $file_ui = $(this);
		$file_ui.find('.file_add').change(function(){
			$file_ui.find('.file_text').val($(this).val())
		});
		$file_ui.find('.btn_del').click(function(){
			$file_ui.find('.file_text').val('')
			$file_ui.find('.file_add').val('')
		});
	});

	$('.tab_type_01').tabs();
	
	// 팝업 닫기
	$('.commonPopup').find('.btn_close_pop').bind('click', function(){
		closeCommonPopup($(this));
	});
	
	// 팝업 닫기
	$('.popup').find('.btn_close_pop').bind('click', function(){
		closePopup($(this));
	});
	$('.popupRecruit').find('.btn_close_pop').bind('click', function(){
		closePopupRecruit($(this));
	});
	$('.popupRecruitCompany').find('.btn_close_pop').bind('click', function(){
		closePopupRecruitCompany($(this));
	});
	
	// 팝업 닫기
	$('.commonPopup').find('.btn').bind('click', function(){
		closeCommonPopup($(this));
	});
	// 팝업 닫기
	$('.popup').find('.btn').bind('click', function(){
		closePopup($(this));
	});
	$('.popupRecruit').find('.btn').bind('click', function(){
		closePopupRecruit($(this));
	});
	$('.popupRecruitCompany').find('.btn').bind('click', function(){
		closePopupRecruitCompany($(this));
	});
	
	$('.commonPopup').find('.btn close').bind('click', function(){
		closeCommonPopup($(this));
	});
	
	$('.noticepopup').find('.btn_close_pop').bind('click', function(){
		$(this).closest($('.noticepopup')).fadeOut();
	});
});

function HeaderLeftMov(){
	$header.css('left', -$('html').scrollLeft());
};

function closeCommonPopup(e){
	e.closest($('.commonPopup')).fadeOut();
}

function closePopup(e){
	e.closest($('.popup')).fadeOut();
}

function closePopupRecruit(e){
	e.closest($('.popupRecruit')).fadeOut();
}

function closePopupRecruitCompany(e){
	e.closest($('.popupRecruitCompany')).fadeOut();
}

function contHeightSetting(){
	winH = $(window).height();
	fooH = $('footer').height();
	headH = $('header').height();
	if ((winH - fooH - headH) > contH){
		if (!$('#wrap').hasClass('main')){
			$('#container').css('min-height', winH - fooH - headH);
		}
	}
}

;(function($){
	$.fn.tabs = function(options) {
		 var defaults = {
			speed: 'slow',
		};
		var opts = $.extend(defaults, options);

		return this.each(function() {
			var $tabWrap = $(this);										// 현재 tab
			var $tabButton = $tabWrap.find('.tab_btn li a');		// 
			var $tabContent = $(this).find('.tab_cont');			// 
			var tabLink = $(location).attr('href').split('#');

			if (tabLink[1]){
				$tabButton.parent().removeClass('on');
				$tabContent.removeClass('on');
				$tabWrap.find('.tab_btn li a[href="#' + tabLink[1]+'"]').parent().addClass('on')
				$tabWrap.find('.tab_cont#'+tabLink[1]).addClass('on');
			}else{
				console.log('탭주소 없음');
			}

			$tabButton.bind('click', function(e) {						// tabButton 클릭시
				e.preventDefault();
				if ($(this).parent().hasClass('on')){
					return false;
				}else{
					$tabButton.parent().removeClass('on');
					$(this).parent().addClass('on');
					$tabContent.removeClass('on').css('opacity',0)                 // 클릭된 tab index를 이용해 동일한 순서의 컨텐츠 노출\
					$tabContent.eq($(this).parent().index()).addClass('on').animate({opacity:1}, opts.speed);
					return false;
				}
			});
		});
	};
})(jQuery);