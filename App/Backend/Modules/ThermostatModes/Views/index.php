<?php 

	if(!empty($modes)){	
		echo json_encode($modes, JSON_THROW_ON_ERROR);	
	}else{
		echo 'Pas de Mode correspondant à cet id!';
	}
