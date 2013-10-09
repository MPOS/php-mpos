
  <table width="100%" style="font-size:13px;">
    <thead>
      <tr>
        <th>Block</th>
        <th>Finder</th>
        <th>Time</th>
        <th align="right">Shares</th>
      </tr>
    </thead>
    <tbody>
{assign var=rank value=1}
{section block $BLOCKSFOUND}
      <tr class="{cycle values="odd,even"}">
        <th><a href="{$GLOBAL.website.blockexplorer.url}{$BLOCKSFOUND[block].height}" target="_blank">{$BLOCKSFOUND[block].height}</a></th>
        <td>{if $BLOCKSFOUND[block].is_anonymous|default:"0" == 1}anonymous{else}{$BLOCKSFOUND[block].finder|default:"unknown"|escape}{/if}</td>
        <td>{$BLOCKSFOUND[block].time|date_format:"%d/%m %H:%M"}</td>
        <td align="right">{$BLOCKSFOUND[block].shares|number_format}</td>
      </tr>
{/section}
    </tbody>
  </table>
