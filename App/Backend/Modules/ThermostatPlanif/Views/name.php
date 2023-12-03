<?php

if(!empty($thermostatPlanifs)){	
		echo json_encode($thermostatPlanifs, JSON_THROW_ON_ERROR);	
	}else{
		echo 'Pas de Planning correspondant à cet id!';
	}