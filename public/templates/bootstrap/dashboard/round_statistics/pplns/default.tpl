  <div class="col-lg-8">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h4 class="panel-title">
          <i class="fa fa-align-left fa-fw"></i> Round statistics
        </h4>
      </div>
      {assign var=PAYOUT_SYSTEM value=$GLOBAL.config.payout_system}
      {include file="dashboard/round_statistics/$PAYOUT_SYSTEM/_header.tpl"}
      {include file="dashboard/round_statistics/$PAYOUT_SYSTEM/_content.tpl"}
      {include file="dashboard/round_statistics/$PAYOUT_SYSTEM/_footer.tpl"}
      <div class="panel-footer">
        Refresh interval: {$GLOBAL.config.statistics_ajax_refresh_interval|default:"10"} seconds.
      </div>
    </div>
  </div>
