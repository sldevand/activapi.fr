$(document).ready(function(){
	
	centerConStatus();
	
	$(window).resize(function(){
		centerConStatus();
	});
	
	$("#disconnect").click(function(){
		postDisconnect();
	});
	
	$("#constatus").click(function(){			
		postDisconnect();
	});
	
	
	function centerConStatus(){
		if(($(window).height() < 300 && $(window).width() <600)
			|| $(window).width() <600){			
			$( "#constatus" ).offset({ top:8 });
		}else{			
			$( "#constatus" ).offset({ top:16 });
		}		
	}
	
	function postDisconnect(){
		
		$.post("php/requetes.php",{"disconnect":"yes"},function(data){	
			$("#constatus").off("click");
			$("#constatus").html(data).delay(1000).fadeOut("slow");				
			$("#disconnect").load("html/sideNav/sideNavUserPart.html");		
			$("#editMode").hide();			
			$("#parametres").hide();			
		});	
						
	}			
	
			
});
