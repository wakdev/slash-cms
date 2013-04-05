

function setFinder(type,lang){
	
	//sla_media_myfiles_button
	//sla_media_current
	
	$("#sla_medias_files").removeClass("sla_medias_current");
	$("#sla_medias_images").removeClass("sla_medias_current");
	$("#sla_medias_media").removeClass("sla_medias_current");
	$("#sla_medias_"+type).addClass("sla_medias_current");
	
	$("#sla_medias_frame").attr("src", "../core/plugins/kcfinder/browse.php?type="+type+"&lang="+lang);
	
	return false;
}