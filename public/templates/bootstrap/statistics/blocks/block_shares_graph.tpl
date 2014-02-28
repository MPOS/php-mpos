<script type="text/javascript">
$(function() {

        var exp = [
                   {section block $BLOCKSFOUND step=-1}
                     [{$BLOCKSFOUND[block].time * 1000}, {$BLOCKSFOUND[block].estshares}],
                   {/section}
                  ];
        var act = [
                   {section block $BLOCKSFOUND step=-1}
                     [{$BLOCKSFOUND[block].time * 1000}, {$BLOCKSFOUND[block].shares|default:"0"}],
                   {/section}
                  ];
        var avg = [
                   {section block $BLOCKSFOUND step=-1}
                     [{$BLOCKSFOUND[block].time * 1000}, {$BLOCKSFOUND[block].block_avg}],
                   {/section}
                  ];

    function doPlot(position) {
        $.plot($("#block-line-chart"), [{
            data: exp,
            label: "Expected Shares"
        }, {
            data: avg,
            label: "Average Shares"
        }, {
            data: act,
            label: "Actual Shares",
            yaxis: 2
        }], {
            xaxes: [{
                mode: 'time'
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
                content: "%s for %x was %y",
                xDateFormat: "%y-%0m-%0d",

                onHover: function(flotItem, $tooltipEl) {
                    // console.log(flotItem, $tooltipEl);
                }
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
