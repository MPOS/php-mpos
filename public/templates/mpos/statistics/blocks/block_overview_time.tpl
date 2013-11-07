<article class="module width_half">
  <header><h3>Block Overview</h3></header>
  <table width="100%" class="tablesorter" cellspacing="0">
    <thead>
        <tr>
          <th align="left"></th>
          <th align="center">Gen</th>
          <th align="center">Found</th>
          <th align="center">Valid</th>
          <th align="center">Orphan</th>
          <th align="center">Rate</th>
        </tr>
    </thead>
    <tbody>
        <tr>
          <th align="left" style="padding-left: 15px">All Time</td>
          <td align="center">{($FIRSTBLOCKFOUND / $COINGENTIME)|number_format:"0"}</td>
          <td align="center">{$LASTBLOCKSBYTIME.Total}</td>
          <td align="center">{$LASTBLOCKSBYTIME.TotalValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.TotalOrphan}</td>
          <td align="center">{($LASTBLOCKSBYTIME.Total|default:"0.00" / ($FIRSTBLOCKFOUND / $COINGENTIME)  * 100)|number_format:"2"} %</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last Hour</td>
          <td align="center">{(3600 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.1HourOrphan}</td>
          <td align="center">{($LASTBLOCKSBYTIME.1HourTotal|default:"0.00" / (3600 / $COINGENTIME)  * 100)|number_format:"2"} %</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last 24 Hours</td>
          <td align="center">{(86400 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.24HourOrphan}</td>
          <td align="center">{($LASTBLOCKSBYTIME.24HourTotal|default:"0.00" / (86400 / $COINGENTIME)  * 100)|number_format:"2"} %</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last 7 Days</td>
          <td align="center">{(604800 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.7DaysOrphan}</td>
          <td align="center">{($LASTBLOCKSBYTIME.7DaysTotal|default:"0.00" / (604800 / $COINGENTIME)  * 100)|number_format:"2"} %</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last 4 Weeks</td>
          <td align="center">{(2419200 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.4WeeksOrphan}</td>
          <td align="center">{($LASTBLOCKSBYTIME.4WeeksTotal|default:"0.00" / (2419200 / $COINGENTIME)  * 100)|number_format:"2"} %</td>
        </tr>
        <tr>
          <th align="left" style="padding-left: 15px">Last 12 Month</td>
          <td align="center">{(29030400 / $COINGENTIME)}</td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthTotal}</td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthValid}</td>
          <td align="center">{$LASTBLOCKSBYTIME.12MonthOrphan}</td>
          <td align="center">{($LASTBLOCKSBYTIME.12MonthTotal|default:"0.00" / (29030400 / $COINGENTIME)  * 100)|number_format:"2"} %</td>
        </tr>
    </tbody>
  </table>
</article>