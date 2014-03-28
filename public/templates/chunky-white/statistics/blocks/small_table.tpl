<article class="widget col-md-5">
  <header><h3>Last Found Blocks</h3></header>
  <table cellspacing="0" class="table table-striped">
    <thead>
      <tr>
        <th align="center">Block</th>
        <th>Finder</th>
        <th align="center">Time</th>
        <th align="right" style="padding-right: 25px;">Actual Shares</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{section block $BLOCKSFOUND}
      <tr class="{cycle values="odd,even"}">
        {if ! $GLOBAL.website.blockexplorer.disabled}
        <td align="center"><a href="{$GLOBAL.website.blockexplorer.url}{$BLOCKSFOUND[block].blockhash}" target="_new">{$BLOCKSFOUND[block].height}</a></td>
        {else}
        <td align="center">{$BLOCKSFOUND[block].height}</td>
        {/if}
        <td>{if $BLOCKSFOUND[block].is_anonymous|default:"0" == 1 && $GLOBAL.userdata.is_admin|default:"0" == 0}anonymous{else}{substr($BLOCKSFOUND[block].finder, 0, strlen($BLOCKSFOUND[block].finder) - rand(1, 3))|escape}{substr(md5($BLOCKSFOUND[block].finder), 0, 5)}***{/if}</td>
        <td align="center">{$BLOCKSFOUND[block].time|date_format:"%d/%m %H:%M:%S"}</td>
        <td align="right" style="padding-right: 25px;">{$BLOCKSFOUND[block].shares|number_format}</td>
      </tr>
{/section}
    </tbody>
  </table>
{if $GLOBAL.config.payout_system != 'pps'}
<footer>
<ul>
  <li>Note: Round Earnings are not credited until <span class="orange">{$GLOBAL.confirmations}</span> confirms.</li>
</ul>
{/if}
</footer>
</article>
