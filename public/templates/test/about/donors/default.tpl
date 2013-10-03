<article class="module width_full">
  <header>
    <h3>Pool Donors</h3>
    <div class="submit_link">
      {include file="global/pagination.tpl"}
    </div>
  </header>
      <table class="tablesorter" cellspacing="0">
        <thead>
          <tr>
            <th>Name</th>
            <th align="right">%<th>
            <th align="right" style="padding-right: 25px">{$GLOBAL.config.currency} Total</th>
          </tr>
        </thead>
        <tbody>
{section name=donor loop=$DONORS}
          <tr>
            <td>{if $DONORS[donor].is_anonymous|default:"0" == 1}anonymous{else}{$DONORS[donor].username}{/if}</td>
            <td align="right">{$DONORS[donor].donate_percent}</td>
            <td align="right" style="padding-right: 25px">{$DONORS[donor].donation|number_format:"2"}</td>
          </tr>
{sectionelse}
          <tr>
            <td align="center" colspan="3">No confirmed donations yet, please be patient!</td>
          </tr>
{/section}
        </tbody>
      </table>
</article>
