<div class="col-lg-4">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h4 class="panel-title">{$GLOBAL.config.payout_system|capitalize} Stats</h4>
    </div>
    <div class="panel-body no-padding">
      <table class="table table-bordered table-hover table-striped">
       <tbody>
{if $GLOBAL.config.payout_system == 'pplns'}
         <tr>
           <td><b>PPLNS Target</b></td>
           <td id="b-pplns" class="right">{$GLOBAL.pplns.target}</td>
         </tr>
{elseif $GLOBAL.config.payout_system == 'pps'}
        <tr>
          <td><b>Unpaid Shares</b></td>
          <td id="b-ppsunpaid">{$GLOBAL.userdata.pps.unpaidshares}</td>
        </tr>
        <tr>
          <td><b>Baseline PPS Rate</b></td>
          <td>{$GLOBAL.ppsvalue|number_format:"12"} {$GLOBAL.config.currency}</td>
        </tr>
        <tr>
          <td><b>Pools PPS Rate</b></td>
          <td>{$GLOBAL.poolppsvalue|number_format:"12"} {$GLOBAL.config.currency}</td>
        </tr>
        <tr>
          <td><b>PPS Difficulty</b></td>
          <td id="b-ppsdiff">{$GLOBAL.userdata.sharedifficulty|number_format:"2"}</td>
        </tr>
{/if}
         {include file="dashboard/round_shares.tpl"}
         {include file="dashboard/payout_estimates.tpl"}
         {include file="dashboard/network_info.tpl"}
       </tbody>
      </table>
    </div>
  </div>
</div>