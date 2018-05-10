<?php 

	if(!empty($modes)){	
		echo json_encode($modes);	
	}else{
		echo 'Pas de Mode correspondant à cet id!';
	}
