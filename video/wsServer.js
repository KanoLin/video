var ws = require("nodejs-websocket")

var server = ws.createServer(function (conn) {
	console.log("New connection")
	conn.on("text", function (str) {
		console.log("Received "+str)
		broadcast(str);
	})
	conn.on("close", function (code, reason) {
		console.log("Connection closed")
	})
	conn.on("error",function (err){
		console.log(err);
	})
}).listen(3000)

function broadcast(str){
	server.connections.forEach(function(connection){
		connection.sendText(str);
	})
}