  <table data-role="table" id="hashes" data-mode="columntoggle">
    <thead>
      <tr>
        <th>Rank</th>
        <th>User Name</th>
        <th data-priority="critical" align="right">KH/s</th>
        <th data-priority="2" align="right">{$GLOBAL.config.currency}/Day</th>
        <th data-priority="3" align="right">{$GLOBAL.config.price.currency}/Day</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{assign var=listed value=0}
{section contrib $CONTRIBHASHES}
      {math assign="estday" equation="round(reward / ( diff * pow(2,32) / ( hashrate * 1000 ) / 3600 / 24), 3)" diff=$DIFFICULTY reward=$REWARD hashrate=$CONTRIBHASHES[contrib].hashrate}
      {if $GLOBAL.userdata.username|default:"" == $CONTRIBSHARES[contrib].account}{assign var=listed value=1}{/if}
      <tr>
        <th>{$rank++}</th>
        <td>{if $CONTRIBHASHES[contrib].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{$CONTRIBHASHES[contrib].account|escape}{/if}</td>
        <td align="right">{$CONTRIBHASHES[contrib].hashrate|number_format}</td>
        <td align="right">{$estday|number_format:"3"}</td>
        <td align="right">{($estday * $GLOBAL.price)|default:"n/a"|number_format:"4"}</td>
      </tr>
{/section}
{if $listed != 1 && $GLOBAL.userdata.username|default:"" && $GLOBAL.userdata.hashrate|default:"0" > 0}
      <tr>
        <th>n/a</th>
        <td>{$GLOBAL.userdata.username}</td>
        <td align="right">{$GLOBAL.userdata.hashrate}</td>
        <td align="right">{$estday|number_format:"3"|default:"n/a"}</td>
        <td align="right">{($estday * $GLOBAL.price)|default:"n/a"|number_format:"4"}</td>
      </tr>
{/if}
    </tbody>
  </table>
