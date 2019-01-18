$(document).ready(function(){
	$("#resultCon").slideUp('fast');

	$('#formcon').on('submit', function(e) {
		e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire

		var $this = $(this); // L'objet jQuery du formulaire

		// Je récupère les valeurs
		var pass = $('#con_password').val();

		// Je vérifie une première fois pour ne pas lancer la requête HTTP
		// si je sais que mon PHP renverra une erreur
		if(!verifPass(pass)) {
			$("#resultCon").css("color","red");
			$("#resultCon").text('Veuillez remplir correctement tous les champs');
			$("#resultCon").slideDown('fast').delay(2000).slideUp('fast');
		} else {
			// Envoi de la requête HTTP en mode asynchrone
			$.ajax({
				url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
				type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
				data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
				success: function(html) { // Je récupère la réponse du fichier PHP

					var result = html.substring(0,7);

					if(result =="Bonjour"){					
						window.setTimeout(function() {window.location.href = 'index.php';},10);

					} else {

						$("#resultCon").css("color","red");
						$("#resultCon").text(html).show();
						$("#resultCon").slideDown('fast').delay(2000).slideUp('fast');
						$("#error").html(html).show();
					}
				}
			});
		}
    });


	

});
