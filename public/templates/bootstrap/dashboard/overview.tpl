  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-align-left fa-fw"></i> Overview {if $GLOBAL.config.price.currency}{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}: <span id="b-price">{$GLOBAL.price|number_format:"8"|default:"0"}</span>{/if}</h4>
      </div>
      <div class="panel-body">
         <div class="row show-grid">
            <div class="col-md-3">
              <center>
               <p><b>My Hashrate</b></p>
               <span id="b-hashrate">{$GLOBAL.userdata.hashrate|number_format:"2"}</span>
               <br>
               <span class="personal-hashrate-bar"></span>
              </center>
            </div>
            <div class="col-md-3">
              <center>
               <p><b>My Sharerate</b></p>
               <span id="b-sharerate">{$GLOBAL.userdata.sharerate|number_format:"2"}</span>
               <br>
               <span class="personal-sharerate-bar"></span>
              </center>
            </div>
            <div class="col-md-3">
              <center>
               <p><b>Pool Hashrate</b></p>
               <span id="b-poolhashrate">{$GLOBAL.hashrate|number_format:"2"}</span>
               <br>
               <span class="pool-hashrate-bar"></span>
              </center>
            </div>
            <div class="col-md-3">
              <center>
               <p><b>Pool Workers</b></p>
               <span id="b-poolworkers">{$GLOBAL.workers}</span>
               <br>
               <span class="pool-workers-bar"></span>
              </center>
            </div>
         </div>
       <div></div>
     </div>
      <div class="panel-footer" style="margin: 20px 0px 0px;">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.
      </div>
    </div>
  </div>
