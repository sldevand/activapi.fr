<?php
namespace Materialize;
class Log{
	
	public static function info($donnees){
		
		
		echo '<pre><span  style="color:red;">--Debug--</span><br> ';
			if(is_array($donnees) || is_object($donnees)){
				var_dump($donnees);
			}else{
				echo htmlspecialchars($donnees). '<br>';
			}
		
		echo '<span  style="color:red;">--End of Debug--</span> </pre>';
	
	}
	
	
}