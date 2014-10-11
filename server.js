var ioServer = require('socket.io').listen(10000);
var ioClient = require('socket.io').listen(8000);

var socketClient = null;

ioClient.sockets.on('connection', function (socket) {
});

ioServer.sockets.on('connection', function (socket) {
    socket.on('chat', function (data) {
        if(data.broadcast) {
            if(data.type === "message") {
                ioClient.sockets.emit('chat message', data.data);
            }
        }
    });
});