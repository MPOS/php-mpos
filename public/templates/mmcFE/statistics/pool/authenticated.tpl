{include file="global/block_header.tpl" BLOCK_HEADER="Pool Statistics" BLOCK_STYLE="clear:none;"}
{include file="global/block_header.tpl" BLOCK_HEADER="Top 15 Hashrates" ALIGN="left" BUTTONS=array(More,Less)}
<center>
  <table width="100%" border="0" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;">
        <th align="left">Rank</th>
        <th align="left" scope="col">User Name</th>
        <th align="left" scope="col">KH/s</th>
        <th align="left">Ł/Day<font size="1"> (est)</font></th>
        <th align="left">Ł/Day<font size="1"> (est)</font></th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{section hashrate $TOPHASHRATES}
      <tr class="{cycle values="odd,even"}">
        <td>{$rank++}</td>
        <td>{$TOPHASHRATES[hashrate].account}</td>
        <td>{$TOPHASHRATES[hashrate].hashrate|number_format}</td>
        <td>{math equation="round(( 24 / (((diff * pow(2,targetdiff)) / hashrate) / 3600) * reward ),3)" diff=$DIFFICULTY targetdiff=$TARGETDIFF hashrate=$TOPHASHRATES[hashrate].hashrate reward=$REWARD}</td>
	<td>{$TOPHASHRATES[hashrate].ltcday|number_format}
      </tr>
{/section}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" BLOCK_HEADER="Top 15 Contributers" ALIGN="right" BUTTONS=array(More,Less)}
<center>
  <table class="" width="100%" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;"><th scope="col" align="left">Rank</th><th scope="col" align="left">User Name</th><th scope="col" align="left">Shares</th></tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{section contributor $CONTRIBUTORS}
      <tr class="{cycle values="odd,even"}">
        <td>{$rank++}</td>
        <td>{$CONTRIBUTORS[contributor].account}</td>
        <td>{$CONTRIBUTORS[contributor].shares|number_format}</td>
      </tr>
{/section}
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" BLOCK_HEADER="Server Stats" BLOCK_STYLE="clear:all;" STYLE="padding-left:5px;padding-right:5px;" BUTTONS=array(More)}
<table class="" width="100%" style="font-size:13px;">
  <tbody>
    <tr>
      <td class="leftheader">Pool Hash Rate</td>
      <td>{$GLOBAL.hashrate / 1000} Mhash/s</td>
    </tr>
    <tr>
      <td class="leftheader">Current Workers Mining</td>
      <td>{$GLOBAL.workers}</td>
    </tr>
    <tr>
      <td class="leftheader">Next Network Block</td>
      <td><a href="http://explorer.litecoin.net/search?q={$CURRENTBLOCK + 1}" target="_new">{$CURRENTBLOCK + 1}</a> &nbsp;&nbsp;<font size="1"> (Current: <a href="http://explorer.litecoin.net/search?q={$CURRENTBLOCK}" target="_new">{$CURRENTBLOCK})</a></font></td>
    </tr>
    <tr>
      <td class="leftheader">Last Block Found</td>
      <td><a href="http://explorer.litecoin.net/search?q={$LASTBLOCK}" target="_new">{$LASTBLOCK}</a></td>
    </tr>
    <tr>
      <td class="leftheader">Current Difficulty</td>
      <td><a href="http://allchains.info" target="_new"><font size="2">{$DIFFICULTY}</font></a></td>
    </tr>
    <tr>
      <td class="leftheader">Est. Avg. Time per Round</td>
      <td>{$ESTTIME|seconds_to_words}</td>
    </tr>
    <tr>
      <td class="leftheader">Time Since Last Block</td>
      <td>{$TIMESINCELAST|seconds_to_words}</td>
    </tr>
  </tbody>
</table>
<ul>
  <li><font color="orange">Server stats are also available in JSON format <a href="/api" target="_api">HERE</a></font></li>
</ul>
{include file="global/block_footer.tpl"}


{include file="statistics/blocks/blocks_found.tpl"}

{include file="global/block_footer.tpl"}
