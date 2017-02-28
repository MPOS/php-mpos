  <div class="col-lg-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Round Information
        </div>
        <span class="tools">
          <i class="fa fa-spinner"></i>
        </span>
      </div>
      {assign var=PAYOUT_SYSTEM value=$GLOBAL.config.payout_system}
      {include file="dashboard/round_statistics/$PAYOUT_SYSTEM/round.tpl"}
    </div>
  </div>
  <div class="col-lg-4">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          Share Information
        </div>
        <span class="tools">
          <i class="fa fa-cloud"></i>
        </span>
      </div>
      {assign var=PAYOUT_SYSTEM value=$GLOBAL.config.payout_system}
      {include file="dashboard/round_statistics/$PAYOUT_SYSTEM/shares.tpl"}
    </div>
  </div>
