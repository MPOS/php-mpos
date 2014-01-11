<article class="module width_full">
  <header><h3>Block Shares</h3></header>
  <table width="70%" class="visualize" rel="line">
    <caption>Block Shares</caption>
    <thead>
      <tr>
{section block $BLOCKSFOUND step=-1}
        <th scope="col">{$BLOCKSFOUND[block].height}</th>
{/section}
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">Expected</th>
{section block $BLOCKSFOUND step=-1}
        <td>{$BLOCKSFOUND[block].estshares}</td>
{/section}
      </tr>
      <tr>
        <th scope="row">Actual</th>
{section block $BLOCKSFOUND step=-1}
        <td>{$BLOCKSFOUND[block].shares|default:"0"}</td>
{/section}
     </tr>
    {if $GLOBAL.config.payout_system == 'pplns'}<tr>
      <th scope="row">PPLNS</th>
{section block $BLOCKSFOUND step=-1}
      <td>{$BLOCKSFOUND[block].pplns_shares}</td>
{/section}
   </tr>{/if}
    {if $USEBLOCKAVERAGE}<tr>
      <th scope="row">Average</th>
{section block $BLOCKSFOUND step=-1}
      <td>{$BLOCKSFOUND[block].block_avg}</td>
{/section}
   </tr>{/if}
    </tbody>
  </table>
  <table class="tablesorter">
    <tbody>
      <tr>
        <td align="left">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($BLOCKSFOUND) && count($BLOCKSFOUND) > ($BLOCKLIMIT - 1)}{$BLOCKSFOUND[$BLOCKLIMIT - 1].height}{/if}&prev=1"><i class="icon-left-open"></i></a>
        </td>
        <td align="right">
          <a href="{$smarty.server.SCRIPT_NAME}?page={$smarty.request.page|escape}&action={$smarty.request.action|escape}&height={if is_array($BLOCKSFOUND) && count($BLOCKSFOUND) > 0}{$BLOCKSFOUND[0].height}{/if}&next=1"><i class="icon-right-open"></i></a>
        </td>
      </tr>
    </tbody>
  </table>
  <footer>
    <p style="padding-left:30px; padding-redight:30px; font-size:10px;">
    The graph above illustrates N shares to find a block vs. E Shares expected to find a block based on
    target and network difficulty and assuming a zero variance scenario.
    </p>
  </footer>
</article>
