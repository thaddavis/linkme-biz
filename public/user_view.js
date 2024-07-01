(function(){
  function sendMessage(message) {
      console.log('sendMessage');
      socket.send(message);
  }

  function setup() {
      // var sender = '';
      var userViewForm = document.querySelector('form');
      // var msgForm = document.querySelector('form.msg-form');
      // var closeForm = document.querySelector('form.close-form');
  
      function formSubmit(event) {
          console.log('formSubmit');

          // debugger;
          event.preventDefault();
          // sender = document.getElementById('sender').value;
          var userViewSubmitMsg = {
              type: "submission",
              // sender: sender,
              text: 'User View Form Submitted'
          };

          msg = JSON.stringify(userViewSubmitMsg);
          sendMessage(msg);
          // msgField.value = ''; // TODO
      }
  
      userViewForm.addEventListener('submit', formSubmit);
  
      // function msgFormSubmit(event) {
      //     event.preventDefault();
      //     var msgField, msgText, msg;
      //     msgField = document.getElementById('msg');
      //     msgText = msgField.value;
      //     msg = {
      //         type: "normal",
      //         sender: sender,
      //         text: msgText
      //     };
      //     msg = JSON.stringify(msg);
      //     sendMessage(msg);
      //     msgField.value = '';
      // }
  
      // msgForm.addEventListener('submit', msgFormSubmit);

      // function closeFormSubmit(event) {
      //     event.preventDefault();
      //     socket.close();
      //     window.location.reload();
      // }

      // closeForm.addEventListener('submit', closeFormSubmit);
  }

  let socket = new WebSocket("ws://localhost:8910");

  var socketOpen = (e) => {
      console.log("connected to the socket");
      var msg = {
          type: 'join',
          sender: 'Browser',
          text: 'connected to the chat server'
      }
      sendMessage(JSON.stringify(msg));
      setup();
  }

  var socketMessage = (e) => {
      console.log(`Message from socket: ${e.data}`);
  }

  var socketClose = (e) => {
      var msg;
      console.log(e);
      if(e.wasClean) {
          console.log("The connection closed cleanly");
          msg = {
              type: 'left',
              sender: 'Browser',
              text: 'The connection closed cleanly'
          }
      }
      else {
          console.log("The connection closed for some reason");
          var msg = {
              type: 'left',
              sender: 'Browser',
              text: 'The connection closed for some reason'
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