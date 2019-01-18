var	address='192.168.1.52';

var	port=5901;

var fulladdress='http://'+address+':'+port;
var socket = io.connect(fulladdress);

//EVENTS FROM SERVER
socket.on('messageConsole', function(message) {
	$('#console-display').prepend(message+"<br>");
});

socket.on('connect', function(message) {
	$('#ioconnection').removeClass("red").removeClass("red-text");
	$('#ioconnection').addClass("teal").addClass("teal-text");
});

socket.on('disconnect',function(message){
	$('#ioconnection').removeClass("teal").removeClass("teal-text");
	$('#ioconnection').addClass("red").addClass("red-text");
});

//EVENTS FROM CONSOLE
$("#send-command").click(function(e){
	e.preventDefault();
	var command=$("#console-edit-text").val();
	if(command != "" && command != null ){
			console.log(command);
			socket.emit("command",command);
	}
});
