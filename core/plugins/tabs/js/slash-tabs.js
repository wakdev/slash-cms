/**
 * Show TAB
 * @param id id div to show
 * @param ref 
 */
function show_tab(id,ref) {
	$('#slash-tabs a').removeClass('sl_adm_tabs-active');
	$('#slash-tabs a').addClass('sl_adm_tabs-inactive');
	$('div.sl_adm_form_main').hide(); 
	ref.removeClass('sl_adm_tabs-inactive');
	ref.addClass('sl_adm_tabs-active');
	$('#'+id).show();
}