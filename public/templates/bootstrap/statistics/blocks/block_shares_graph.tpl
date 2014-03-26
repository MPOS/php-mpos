<script type="text/javascript">
$(function () {

      var blockdata = [
        {section block $BLOCKSFOUND step=-1}{ldelim}
          blockHeight:    {$BLOCKSFOUND[block].height},
          estShares:      {$BLOCKSFOUND[block].estshares},
          actShares:      {$BLOCKSFOUND[block].shares|default:"0"},
          {if $USEBLOCKAVERAGE}
          avgShares:      {$BLOCKSFOUND[block].block_avg|default:"0"},
          {/if}
          {if $GLOBAL.config.payout_system == 'pplns'}
          pplnsShares:    {$BLOCKSFOUND[block].pplns_shares|default:"0"},
          {/if}
          {rdelim},
        {/section}
      ];

      Morris.Area({
        parseTime: false,
        behaveLikeLine: true,
        element: 'block-area-chart',
        data: blockdata,
        xkey: 'blockHeight',
        ykeys : ['estShares', {if $USEBLOCKAVERAGE}'avgShares', {/if}{if $GLOBAL.config.payout_system == 'pplns'}'pplnsShares', {/if} 'actShares'],
        labels : ['Expected Shares', {if $USEBLOCKAVERAGE}'Average Shares', {/if}{if $GLOBAL.config.payout_system == 'pplns'}'PPLNS Shares',{/if} 'Actual Shares'],
        pointSize: 2,
        lineColors: ['#2D9C2F','#D58665','#2D619C','#FF0000'],
        pointFillColors: ['#FFFFFF'],
        hideHover: 'auto',
        resize: true,
        fillOpacity: 0.05
      });
});
</script>

<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-th fa-fw"></i> Block Shares
      </div>
      <div class="panel-body">
        <ul class="pager">
          <li class="previous">
            <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($BLOCKSFOUND) && count($BLOCKSFOUND) > ($BLOCKLIMIT - 1)}{$BLOCKSFOUND[$BLOCKLIMIT - 1].height}{/if}&prev=1"> &larr;</a>
          </li>
          <li class="next">
            <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($BLOCKSFOUND) && count($BLOCKSFOUND) > 0}{$BLOCKSFOUND[0].height}{/if}&next=1"> &rarr;</a>
          </li>
        </ul>
        <div class="flot-chart">
          <div id="block-area-chart"></div>
        </div>
      </div>
    </div>
  </div>
</div>
