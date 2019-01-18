
function surligne(champ, erreur)
{

	if(erreur)
	{	  	
	  $("#"+champ.id).css("backgroundColor","#fba");
	  setTimeout(function(){
		   $("#"+champ.id).css("backgroundColor","");
		  
	  },1500);
	  
	}
	else
	{
	   $("#"+champ.id).css("backgroundColor","");
	}
}



function verifPass(champ)
{
 var regex = /^[a-zA-Z0-9._-]{8,32}$/;


   if(!regex.test(champ.value))
   {
	  surligne(champ, true);
	  return false;
   }
   else
   {
	  surligne(champ, false);
	  return true;
   }
}

function verifLogin(champ)
{
   var regex = /^[a-zA-Z._-]{6,32}$/;
   if(!regex.test(champ.value))
   {
	  surligne(champ, true);
	  return false;
   }
   else
   {
	  surligne(champ, false);
	  return true;
   }
}


function verifFormCon(f)
{

	   var mailOk = verifLogin(f.con_login);
	   var passOk = verifPass(f.con_password);

	if(mailOk && passOk)
		return true;
	else
	{

		  return false;
	}
}


