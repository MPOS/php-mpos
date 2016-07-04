<script>
{literal}
$(document).ready(function(){
  var g1, g2;
  g1 = new JustGage({
    id: "mr",
    value: parseFloat({/literal}{$GLOBAL.workers}{literal}).toFixed(0),
    min: 0,
    max: Math.round({/literal}{$GLOBAL.workers}{literal} * 2),
    title: "Miners",
    gaugeColor: '#6f7a8a',
    labelFontColor: '#555',
    titleFontColor: '#555',
    valueFontColor: '#555',
    label: "Active Miners",
    relativeGaugeSize: true,
    showMinMax: true,
    shadowOpacity : 0.8,
    shadowSize : 0,
    shadowVerticalOffset : 10
  });

  g2 = new JustGage({
    id: "hr",
    value: parseFloat({/literal}{$GLOBAL.hashrate}{literal}).toFixed(2),
    min: 0,
    max: Math.round({/literal}{$GLOBAL.hashrate}{literal} * 2),
    title: "Pool Hashrate",
    gaugeColor: '#6f7a8a',
    labelFontColor: '#555',
    titleFontColor: '#555',
    valueFontColor: '#555',
    label: "{/literal}{$GLOBAL.hashunits.pool}{literal}",
    relativeGaugeSize: true,
    showMinMax: true,
    shadowOpacity : 0.8,
    shadowSize : 0,
    shadowVerticalOffset : 10
  });
 });
{/literal}
</script>
