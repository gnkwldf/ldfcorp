var ioServer = require('socket.io').listen(10000);
var ioClient = require('socket.io').listen(8000);

var socketClient = null;

ioClient.sockets.on('connection', function (socket) {
    console.log("Client connection");
});

ioServer.sockets.on('connection', function (socket) {
    console.log("Server connection");
    socket.on('chat', function (data) {
        if(data.broadcast) {
            console.log("Server BROADCAST");
            if(data.type === "message") {
                ioClient.sockets.emit('chat message', data.data);
            }
        }
    });
});