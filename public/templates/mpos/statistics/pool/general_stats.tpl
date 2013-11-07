 <article class="module width_half">
  <header><h3>General Statistics</h3></header>
  <div class="module_content">
    <table width="100%">
      <tbody>
        <tr>
          <th align="left" width="50%">Pool Hash Rate</th>
          <td width="70%"><span id="b-hashrate"></span> {$GLOBAL.hashunits.pool}</td>
        </tr>
        <tr>
          <th align="left">Pool Efficiency</td>
          <td>{if $GLOBAL.roundshares.valid > 0}{($GLOBAL.roundshares.valid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%{else}0%{/if}</td>
        </tr>
        <tr>
          <th align="left">Current Active Workers</td>
          <td id="b-workers"></td>
        </tr>
        <tr>
          <th align="left">Current Difficulty</td>
      {if ! $GLOBAL.website.chaininfo.disabled}
          <td><a href="{$GLOBAL.website.chaininfo.url}" target="_new"><font size="2"><span id="b-diff"></span></font></a></td>
      {else}
          <td><font size="2"><span id="b-diff"></span></font></td>
      {/if}
        </tr>
        <tr>
          <th align="left">Est. Avg. Time per Round</td>
          <td>{$ESTTIME|seconds_to_words}</td>
        </tr>
        <tr>
          <th align="left">Est. Shares this Round</td>
          <td id="b-target"></td>
        </tr>
    {if ! $GLOBAL.website.blockexplorer.disabled}
        <tr>
          <th align="left" width="50%">Next Network Block</td>
          <td colspan="3">{$CURRENTBLOCK + 1} &nbsp;&nbsp;<font size="1"> (Current: <a href="{$GLOBAL.website.blockexplorer.url}{$CURRENTBLOCKHASH}" target="_new">{$CURRENTBLOCK})</a></font></td>
        </tr>
    {else}
        <tr>
          <th align="left">Next Network Block</td>
          <td colspan="3">{$CURRENTBLOCK + 1} &nbsp;&nbsp; (Current: {$CURRENTBLOCK})</td>
        </tr>
    {/if}
        <tr>
          <th align="left">Last Block Found</td>
          <td colspan="3"><a href="{$smarty.server.PHP_SELF}?page=statistics&action=round&height={$LASTBLOCK}" target="_new">{$LASTBLOCK|default:"0"}</a></td>
        </tr>
        <tr>
          <th align="left">Time Since Last Block</td>
          <td colspan="3">{$TIMESINCELAST|seconds_to_words}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <header><h3>Pool Luck</h3></header>
  <div class="module_content">
    <table width="100%">
      <tbody>
        <tr>
          <th align="left"></th>
          <th align="center">Est. Blocks</th>
          <th align="center">Blocks found</th>
          <th align="center">Luck</th>
        </tr>
        <tr>
          <th align="left">since 1st Block</td>
          <td align="center">{($FIRSTBLOCKFOUND / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.Total}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / ($FIRSTBLOCKFOUND / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.Total / ($FIRSTBLOCKFOUND / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last hour</td>
          <td align="center">{(3600 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.1HourTotal / (3600 / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.1HourTotal / (3600 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last day</td>
          <td align="center">{(86400 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / (86400 / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.Total / (86400 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last week</td>
          <td align="center">{(604800 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / (604800 / $ESTTIME) * 100)|number_format:"2"} %{else} {($LASTBLOCKSBYTIME.Total / (604800 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last month</td>
          <td align="center">{(419200 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / (2419200 / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.Total / (2419200 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
        <tr>
          <th align="left">last year</td>
          <td align="center">{(29030400 / $ESTTIME)|number_format:"2"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthTotal}</td>
          <td align="center">{IF $ESTTIME > 0}{($LASTBLOCKSBYTIME.Total / (29030400 / $ESTTIME) * 100)|number_format:"2"} %{else}{($LASTBLOCKSBYTIME.Total / (29030400 / 0.00) * 100)|number_format:"2"} %{/if}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <footer>
{if !$GLOBAL.website.api.disabled}<ul><li>These stats are also available in JSON format <a href="{$smarty.server.PHP_SELF}?page=api&action=getpoolstatus&api_key={$GLOBAL.userdata.api_key|default:""}">HERE</a></li></ul>{/if}
  </footer>
</article>
