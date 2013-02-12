function show_tab(id) {
					$('#slash-tabs a').removeClass('sl_adm_tabs-active');
					$('#slash-tabs-container div').hide();
					$('div.sl_adm_form_main').hide(); 
					$('#tab_'+id).show();
					$('#main_'+id).show();
				}