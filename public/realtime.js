(function(){
  function sendMessage(message) {
      socket.send(message);
  }

  function parseMessage(message) {
      var msg = {type: "", sender: "", data: ""};
      try {
          msg = JSON.parse(message);
      }
      catch(e) {
          return false;
      }
      return msg;
  }

  function appendMessage(message) {
      var parsedMsg;
      var msgContainer = document.querySelector(".messages");
      if (parsedMsg = parseMessage(message)) {
          console.log('appending message');
          console.log(parsedMsg);

          var msgElem, textElem;
          var text;

          msgElem = document.createElement("div");
          
          text = document.createTextNode(message);
          msgElem.appendChild(text);
          msgContainer.appendChild(msgElem);
      }
  }

  function setup() {
      var sender = '';
      var joinForm = document.querySelector('form.join-form');
      var msgForm = document.querySelector('form.msg-form');
      var closeForm = document.querySelector('form.close-form');
  
      function joinFormSubmit(event) {
          event.preventDefault();
          sender = document.getElementById('sender').value;
          var joinMsg = {
              type: "join",
              sender: sender,
              data: sender + ' joined the chat!'
          };
          sendMessage(JSON.stringify(joinMsg));
          joinForm.classList.add('hidden');
          msgForm.classList.remove('hidden');
          closeForm.classList.remove('hidden');
      }
  }

  let socket = new WebSocket("ws://localhost:8910");

  var socketOpen = (e) => {
      console.log("connected to the socket");
      var msg = {
          type: 'join',
          sender: 'Browser',
          data: 'connected'
      }
      sendMessage(JSON.stringify(msg));
      setup();
  }

  var socketMessage = (e) => {
      console.log(`Message from socket: ${e.data}`);
      appendMessage(e.data);
  }

  var socketClose = (e) => {
      var msg;
      console.log(e);
      if(e.wasClean) {
          console.log("The connection closed cleanly");
          msg = {
              type: 'left',
              sender: 'Browser',
              data: 'The connection closed cleanly'
          }
      }
      else {
          console.log("The connection closed for some reason");
          var msg = {
              type: 'left',
              sender: 'Browser',
              data: 'The connection closed for some reason'
          }
      }
      appendMessage(JSON.stringify(msg));
  }
  
  var socketError = (e) => {
      console.log("WebSocket Error");
      console.log(e);
  }

  socket.addEventListener("open", socketOpen);
  socket.addEventListener("message", socketMessage);
  socket.addEventListener("close", socketClose);
  socket.addEventListener("error", socketError);
 
})();