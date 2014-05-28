      /* 
       * Attaches to the specified multi-stratum event stream 
       * and installas handlers.  Includes retry in case of error. 
       */
      function monitor_stratum(url, message_handlers) { 
        if (retryInProgress) { 
          console.log("Retry already in progress, wait for it...") 
          return; 
        } 
        retryInProgress = true 
        var stratum = new WebSocket(url);
        stratum.onopen = function() { 
          retryInProgress = false
          stratum.send(JSON.stringify({ "key" : "fuckingpoop", "request" : "connections"})) 
        } 
        stratum.onmessage = function(evt) { 
          id++; 
          var data = JSON.parse(evt.data);
          message_handlers[data.type](data);
        } 
        stratum.onclose = function(evt) { 
          console.log("event stream closed, will retry")
          setInterval(10000, { monitor_stratum(url) } )  
          
        } 
        stratum.onerror = function(evt) { 
          console.log(evt); 
          setInterval(10000, { monitor_stratum(url) } )  
        } 
      }
