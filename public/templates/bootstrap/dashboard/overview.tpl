  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title"><i class="fa fa-align-left fa-fw"></i> Overview {if $GLOBAL.config.price.currency}{$GLOBAL.config.currency}/{$GLOBAL.config.price.currency}: <span id="b-price">{$GLOBAL.price|number_format:"8"|default:"0"}</span>{/if}</h4>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <td>My Hashrate</td>
            <td id="b-hashrate">{$GLOBAL.userdata.hashrate|number_format:"2"}</td>
            <td class="personal-hashrate-bar" style="margin-left:auto; margin-right:auto; width:10%;"></td>
            <td>My Sharerate</td>
            <td id="b-sharerate">{$GLOBAL.userdata.sharerate|number_format:"2"}</td>
            <td class="personal-sharerate-bar" style="margin-left:auto; margin-right:auto; width:10%;"></td>
          </tr>
          <tr>
            <td>Pool Hashrate</td>
            <td id="b-poolhashrate">{$GLOBAL.hashrate|number_format:"2"}</td>
            <td class="pool-hashrate-bar" style="margin-left:auto; margin-right:auto; width:10%;"></td>
            <td>Pool Workers</td>
            <td id="b-poolworkers">{$GLOBAL.workers}</td>
            <td class="pool-workers-bar" style="margin-left:auto; margin-right:auto; width:10%;"></td>
          </tr>
        </table>
      </div>
      <div class="panel-footer">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds. Hashrate based on shares submitted in the past {$INTERVAL|default:"5"} minutes.
      </div>
    </div>
  </div>
