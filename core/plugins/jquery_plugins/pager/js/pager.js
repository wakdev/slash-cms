var pager_i=0;

$.fn.pager = function(clas, options) {
	
	var settings = {		
		navIdTop: 'nav',
		navIdBottom: '',
		navClass: 'nav',
		navAttach: 'append',
		highlightClass: 'highlight',
		prevText: '&laquo;',
		nextText: '&raquo;',
		linkText: null,
		linkWrap: null,
		height: null,
		initPage: 1
	}
	if(options) $.extend(settings, options);
	
		
	return this.each( function () {
		
		var me = $(this);
		var size;	
		pager_i = settings.initPage - 1;
		var navidtop = '#'+settings.navIdTop;
		var navidbottom = '#'+settings.navIdBottom;
		
		/**
		* Init
		*/
		function init () {
			size = $(clas, me).not(navidtop).size();

			if(settings.height == null) {			
				settings.height = getHighest();
			}
			
			show();
			highlight();
			sizePanel();
			
			if(settings.linkWrap != null) {
				linkWrap();
			}
		}
		
		
		function show () {
			$(me).find(clas).not(navidtop).hide();
			var show = $(me).find(clas).not(navidtop).get(pager_i);
			$(show).show();
			
			if (navidbottom != '') {
				/* For Bottom navigation */
				$(me).find(clas).not(navidbottom).hide();
				var show = $(me).find(clas).not(navidbottom).get(pager_i);
				$(show).show();
			}
			
		}
		
		function highlight () {
			$(me).find(navidtop).find('a').removeClass(settings.highlightClass);
			var show = $(me).find(navidtop).find('a').get(pager_i+1);			
			$(show).addClass(settings.highlightClass);
			
			if (navidbottom != '') {
				// For Bottom navigation
				$(me).find(navidbottom).find('a').removeClass(settings.highlightClass);
				var show = $(me).find(navidbottom).find('a').get(pager_i+1);			
				$(show).addClass(settings.highlightClass);
			}
			
		}

		function sizePanel () {
			if($.browser.msie) {
				$(me).find(clas).not(navidtop).css( {
					height: settings.height
				});	

			} else {
				$(me).find(clas).not(navidtop).css( {
					minHeight: settings.height	
				});
			}
			
			if (navidbottom != '') {
				// For Bottom navigation
				if($.browser.msie) {
					$(me).find(clas).not(navidbottom).css( {
						height: settings.height
					});	
	
				} else {
					$(me).find(clas).not(navidbottom).css( {
						minHeight: settings.height
					});	
				}
			}
		}
		
		function getHighest () {
			var highest = 0;
			$(me).find(clas).not(navidtop).each(function () {
				
				if(this.offsetHeight > highest) {
					highest = this.offsetHeight;
				}
			});
			
			highest = highest + "px";
			return highest;
		}
		
		
		
		function getNavHeight () {
			var nav = $(navidtop).get(0);
			return nav.offsetHeight;
		}
		
		function linkWrap () {
			$(me).find(navidtop).find("a").wrap(settings.linkWrap);
		}
		
		function getPage() {
			return 3;
		}
		
	
		init();
		

		$(this).find(navidtop).find("a").click(function () {

			if($(this).attr('rel') == 'next') {
				if(pager_i + 1 < size) {
					pager_i = pager_i+1;
				}
			} else if($(this).attr('rel') == 'prev') { 
				if(pager_i > 0) {	
					pager_i = pager_i-1;
				}
			} else {		
				var j = $(this).attr('rel');	
				pager_i = j-1;		
			}
			show();
			highlight();
			return false;
		});
		
		
		if (navidbottom != '') {
		
			$(this).find(navidbottom).find("a").click(function () {
	
				if($(this).attr('rel') == 'next') {
					if(pager_i + 1 < size) {
						pager_i = pager_i+1;
					}
				} else if($(this).attr('rel') == 'prev') { 
					if(pager_i > 0) {	
						pager_i = pager_i-1;
					}
				} else {		
					var j = $(this).attr('rel');	
					pager_i = j-1;		
				}
				show();
				highlight();
				//alert('test');
				return false;
			});
		
		}
		
		
	});	
}