// Next pass wlil be to move this all into an object so that 
// we don't expose details we don't need to... 


var retryFun = undefined
var id = 0; // incremented for every incoming event
var stratum_nodes = ["-us-east", "-us-west", "-eu"] 
var connected = false  
var stratum;
var currFilters
var failedPingCount = 0;
var authMessage;

// TODO this ALL needs to be namespaced into an object, "streamInstance" - 
// because this shit is sloppy as hell. 
// TODO this request will be changing: {"request":"filter","data":{"type":"share", "diff":X, "user":Y}
var filters  = { 
 diff : function(value) { 
    return { "request" : "mindiff", "value" : value } 
 },
 user : function(value) { 
    return { "request" : "userfilter", "value" : value } 
 }
}

function getAuthAPIMessage(Key) { 
  return { "request" : "auth", "apikey" : Key } 
}

function getAuthReconnectMessage(Token) { 
  return { "request" : "auth", "token" : Token } 
} 

function getAuthCredentialsMessage(User, Pass) { 
  return { "request" : "auth", "user" : User, "password" : Pass } 
}

function real_monitor_stratum(handlers, init_data) { 
  if (connected) { 
      return
  }
  connected = true  // ensures that no matter what, we won't get more than one connection at a time. 
  stratum = new WebSocket("wss://awesomehash.com/stratum-events/");
  stratum.onopen = function() { 
   
    // Re-auth if we have stored credentials
    // We do this first because auth will affect the events we can subsscript to
    // and the filters we set. 
    if (authMessage != undefined) { 
      stratum.send(JSON.stringify(authMessage));
    } 
      
    // Because we're reconnecting,we have to tell the streamer what our filter is. 
    // Set this before we subscribe so we don't get values we don't are about. 
    loadFilterValues();
    for (x = 0; x < init_data.length; x++) {
      stratum.send(JSON.stringify(init_data[x])) 
    }
  } 

  stratum.onmessage = function(evt) { 
    id++; 
    var data = JSON.parse(evt.data);
    if (handlers.hasOwnProperty(data.type)) { 
      handlers[data.type](data);
    } 
  } 
  stratum.onclose = function(evt) { 
    connected = false
  }
  stratum.onerror = function(evt) { 
  }
}

function loadFilterValues() { 
  applyFilterValues(filterValues())
}

function filterValues() { 
  var filterVals = window.localStorage.getItem(document.URL + "-filterVal");
  try { 
    return (filterVals == undefined) ?  { user : "", diff: 500 } : JSON.parse(filterVals);
  } catch(e) { 
    return { user:"",diff:500}
  }
  
}
// Ugh - repetition in two functions below is mesy, but I'm out of time for now... 
function updateFilterValues(newValues) { 
  var oldValues = filterValues()  
  if (newValues.diff != undefined) { 
    stratum.send(JSON.stringify(filters.diff(newValues.diff)))
    oldValues.diff = newValues.diff
  }
  if (newValues.user!= undefined){ 
    stratum.send(JSON.stringify(filters.user(newValues.user)))
    oldValues.user= newValues.user
  }
  window.localStorage.setItem(document.URL+ "-filterVal", JSON.stringify( oldValues))
}
function applyFilterValues(values) { 
  var filterVals = filterValues()
  if (values.diff == undefined) { 
    values.diff = 500
  }
  if (values.user == undefined) { 
    values.user = "" 
  }
  stratum.send(JSON.stringify(filters.diff(values.diff)))
  stratum.send(JSON.stringify(filters.user(values.user)))
}

function monitor_stratum(handlers, initial_request) { 
  
  // some setup of stuff we need in place in the handlers we receive
  handlers = handlers || [] 
  initial_request = initial_request || []
  var subscriptions = []
  for (var ev in handlers) { 
    if (handlers.hasOwnProperty(ev) && ev != "responders") { 
      subscriptions.push(ev) 
    }
  }
  // Rig  up default requests to include subscriptions that are indicated 
  // by handlers present. 
  initial_request.push({ "request" : "subscribe", "events" : subscriptions })
  handlers['response'] = function(message) {
    if (handlers['responders'] == undefined)
      handlers['responders'] = {} 

    responders = handlers['responders'] 
    responders['getcurrentblocktemplate'] = function(message) { 
       if (handlers.hasOwnProperty("blocktemplate")) { 
          handlers.blocktemplate(message.data)
       } 
    }
    responders['ping'] = function(message) { 
       failedPingCount = 0;
    } 
    responders['connect'] = function(message) { 
       console.log("Connected, session id " + message.data.session_id)
    }
    if (responders.hasOwnProperty(message.request)) { 
       responders[message.request](message) 
    } else { 
      console.log(JSON.stringify(message))
      if (message.error) { 
        console.log("server replies: " + JSON.stringify(message))
      }
    }
  }

  // this will perform initial connection then reconnect as needed if connection gets lost, 
  // without spawning accidental extra requests...
  real_monitor_stratum(handlers, initial_request) 
  setInterval( function() { real_monitor_stratum(handlers, initial_request) }, 1000 )  
}


