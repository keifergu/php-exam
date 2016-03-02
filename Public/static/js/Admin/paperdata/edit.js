
function openUrl(url, title){
	if($("#pagetabs").tabs("exists", title)){
		$("#pagetabs").tabs("select", title);
	}else{
		$("#pagetabs").tabs("add",{
			title: title,
			href: url,
			closable: true,
			cache: false
		});
	}
}
function RemoveOption(paperid,optionid){
	$.post("index.php?m=Admin&c=Paperdata&a=paperEdit",{
		paperid:paperid,
		optionid:optionid
	},function(data){
		var tab=$("#pagetabs").tabs("getSelected");
		$("#tt").tabs("update",{
			tab: tab,
		});
		tab.panel("refresh");
	});
}