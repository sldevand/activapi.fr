<?php

if(!empty($thermostatPlanifs)){	
		echo json_encode($thermostatPlanifs);	
	}else{
		echo 'Pas de Planning correspondant à cet id!';
	}