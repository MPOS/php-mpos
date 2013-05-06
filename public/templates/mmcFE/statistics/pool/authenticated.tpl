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
      </tr>
    </thead>
    <tbody>
      <tr class="">
        <td>1</td>
        <td>TheSerapher</td>
        <td>576</td>
        <td>&nbsp;4.045</td>
      </tr>
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" BLOCK_HEADER="Top 15 Contributors" ALIGN="right" BUTTONS=array(More,Less)}
<center>
  <table class="" width="100%" style="font-size:13px;">
    <thead>
      <tr style="background-color:#B6DAFF;"><th scope="col" align="left">Rank</th><th scope="col" align="left">User Name</th><th scope="col" align="left">Shares</th></tr>
    </thead>
    <tbody>
      <tr class="user_position">
        <td>1</td>
        <td>TheSerapher</td>
        <td>94,133</td>
      </tr>
    </tbody>
  </table>
</center>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" BLOCK_HEADER="Server Stats" BLOCK_STYLE="clear:all;" STYLE="padding-left:5px;padding-right:5px;" BUTTONS=array(More)}
<table class="" width="100%" style="font-size:13px;">
  <tbody>
    <tr>
      <td class="leftheader">Pool Hash Rate</td>
      <td>{$GLOBAL.hashrate} Mhash/s</td>
    </tr>
    <tr>
      <td class="leftheader">Current Workers Mining</td>
      <td>{$GLOBAL.workers}</td>
    </tr>
    <tr>
      <td class="leftheader">Next Network Block</td>
      <td><a href="http://explorer.litecoin.net/search?q=333758" target="_new">333,759</a> &nbsp;&nbsp;<font size="1"> (Current: <a href="http://explorer.litecoin.net/search?q=333758" target="_new">333,758)</a></font></td>
    </tr>
    <tr>
      <td class="leftheader">Current Difficulty</td>
      <td><a href="http://allchains.info" target="_new"><font size="2">293.35991187</font></a></td>
    </tr>
    <tr>
      <td class="leftheader">Est. Avg. Time per Round</td>
      <td>205 Hours 40 Minutes</td>
    </tr>
    <tr>
      <td class="leftheader">Time Since Last Block</td>
      <td>N/A</td>
    </tr>
  </tbody>
</table>
<ul>
  <li><font color="orange">Server stats are also available in JSON format <a href="/api" target="_api">HERE</a></font></li>
</ul>
{include file="global/block_footer.tpl"}


{include file="global/block_header.tpl" BLOCK_HEADER="Last 10 Blocks Found" BLOCK_STYLE="clear:none;" BUTTONS=array(More)}
<center>
  <table class="stats_lastblocks" width="100%" style="font-size:13px;">
    <tbody>
      <tr style="background-color:#B6DAFF;">
        <th scope="col" align="left">Block</th>
        <th scope="col" align="left">Validity</th>
        <th scope="col" align="left">Finder</th>
        <th scope="col" align="left">Date / Time</th>
        <th scope="col" align="left">Shares</th>
      </tr>
    </tbody>
  </table>
</center>
<ul>
  <li>Note: <font color="orange">Round Earnings are not credited until 120 confirms.</font></li>
</ul>
{include file="global/block_footer.tpl"}
{include file="global/block_footer.tpl"}
