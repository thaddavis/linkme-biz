(function(){
  function sendMessage(message) {
      console.log('sendMessage');
      socket.send(message);
  }

  function setup() {
      var userViewForm = document.querySelector('form');
      
      function formSubmit(event) {
          console.log('formSubmit');
          console.log('event')
          // event.preventDefault();

          const queryString = window.location.search;
          console.log('queryString', queryString);
          const urlParams = new URLSearchParams(queryString);
          const link = urlParams.get("link")

          const name = document.getElementById('name').value;
          const email = document.getElementById('email').value;
          const phone = document.getElementById('phone').value;
          
          var userViewSubmitMsg = {
              type: "submission",
              streamId: link,
              data: JSON.stringify({name, email, phone})
          };

          msg = JSON.stringify(userViewSubmitMsg);
          sendMessage(msg);
      }
  
      userViewForm.addEventListener('submit', formSubmit);
  }

  let socket = new WebSocket("ws://127.0.0.1:8910");

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