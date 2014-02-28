<script type="text/javascript">
$(function() {
        var blk = [
                   {section block $BLOCKSFOUND step=-1}
                     [{$smarty.section.block.iteration}, "{$BLOCKSFOUND[block].height}"],
                   {/section}
                  ];
        var exp = [
                   {section block $BLOCKSFOUND step=-1}
                     [{$smarty.section.block.iteration}, {$BLOCKSFOUND[block].estshares}],
                   {/section}
                  ];
        var act = [
                   {section block $BLOCKSFOUND step=-1}
                     [{$smarty.section.block.iteration}, {$BLOCKSFOUND[block].shares|default:"0"}],
                   {/section}
                  ];
{if $USEBLOCKAVERAGE}
        var avg = [
                   {section block $BLOCKSFOUND step=-1}
                     [{$smarty.section.block.iteration}, {$BLOCKSFOUND[block].block_avg|default:"0"}],
                   {/section}
                  ];
{/if}
{if $GLOBAL.config.payout_system == 'pplns'}
        var pplns = [
                     {section block $BLOCKSFOUND step=-1}
                       [{$smarty.section.block.iteration}, {$BLOCKSFOUND[block].pplns_shares|default:"0"}],
                     {/section}
                    ];
{/if}

    function doPlot(position) {
        $.plot($("#block-line-chart"), [{
            data: exp,
            label: "Expected Shares"
        }, {
            data: act,
            label: "Actual Shares"
{if $USEBLOCKAVERAGE}
        }, {
            data: avg,
            label: "Average Shares"
{/if}
{if $GLOBAL.config.payout_system == 'pplns'}
        }, {
            data: pplns,
            label: "PPLNS Shares"
{/if}
        }], {
            xaxes: [{
                ticks: blk,
                mode: null
            }],
            yaxes: [{
                min: 0
            }, {
                // align if we are to the right
                alignTicksWithAxis: position == "right" ? 1 : null,
                position: position
            }],
            grid: {
                hoverable: true //IMPORTANT! this is needed for tooltip to work
            },
            tooltip: true,
            tooltipOpts: {
                content: "%y %s",
            }

        });
    }

    doPlot("right");

    $("button").click(function() {
        doPlot($(this).text());
    });
});
</script>

<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        Block Shares
      </div>
      <div class="panel-body">
        <div class="panel-group">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="flot-chart">
                <div class="flot-chart-content" id="block-line-chart"></div>
              </div>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
