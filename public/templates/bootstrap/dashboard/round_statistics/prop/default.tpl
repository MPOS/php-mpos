  <div class="col-lg-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-spinner fa-fw"></i> Round statistics
        </h4>
      </div>
      {assign var=PAYOUT_SYSTEM value=$GLOBAL.config.payout_system}
      {include file="dashboard/round_statistics/$PAYOUT_SYSTEM/round.tpl"}
      <div class="panel-footer">
        <h6>Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds.</h6>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-cloud fa-fw"></i> Share statistics
        </h4>
      </div>
      {assign var=PAYOUT_SYSTEM value=$GLOBAL.config.payout_system}
      {include file="dashboard/round_statistics/$PAYOUT_SYSTEM/shares.tpl"}
      <div class="panel-footer">
        <h6>Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds.</h6>
      </div>
    </div>
  </div>
