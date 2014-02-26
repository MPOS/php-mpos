{if $ADDRESSCOUNT > 1}
<article class="module width_full">
  <header><h3>Wallet Information</h3></header>
{/if}
  <article class="module width_quarter">
    <header><h3>Balance Summary</h3></header>
    <table width="25%" class="tablesorter" cellspacing="0">
    <tr>
      <td align="left">Wallet Balance</td>
      <td align="left">{$BALANCE|number_format:"8"}</td>
    </tr>
    <tr>
      <td align="left">Locked for users</td>
      <td align="left">{$LOCKED|number_format:"8"}</td>
    </tr>
    <tr>
      <td align="left">Unconfirmed</td>
      <td align="left">{$UNCONFIRMED|number_format:"8"}</td>
    </tr>
    <tr>
      <td align="left">Liquid Assets</td>
      <td align="left">{($BALANCE - $LOCKED - $UNCONFIRMED + $NEWMINT|default:"0")|number_format:"8"}</td>
    </tr>
{if $NEWMINT >= 0}
    <tr>
      <td align="left">PoS New Mint</td>
      <td align="left">{$NEWMINT|number_format:"8"}</td>
    </tr>
{/if}
  </table>
  </article>

  <article class="module width_3_quarter">
    <header><h3>Wallet Status</h3></header>
    <table class="tablesorter" cellspacing="0">
      <thead>
        <th align="center">Version</th>
        <th align="center">Protocol Version</th>
        <th align="center">Wallet Version</th>
        <th align="center">Connections</th>
        <th align="center">Errors</th>
      </thead>
      <tbody>
        <tr>
          <td align="center">{$COININFO.version|default:""}</td>
          <td align="center">{$COININFO.protocolversion|default:""}</td>
          <td align="center">{$COININFO.walletversion|default:""}</td>
          <td align="center">{$COININFO.connections|default:""}</td>
          <td align="center"><font color="{if $COININFO.errors}red{else}green{/if}">{$COININFO.errors|default:"OK"}</font></td>
        </tr>
      </tbody>
    </table>
  </article>
{if $ADDRESSCOUNT > 1}
</article>

<article class="module width_full">
  <header><h3>Number of Accounts in Wallet: {$ADDRESSCOUNT|default:"0"}</h3></header>
{foreach key=NAME item=VALUE from=$ACCOUNTS}
  <article class="module width_full">
    <header><h3>Account: {$NAME|default:"Default"}</h3></header>
    <article class="module width_quarter">
      <header><h3>Balance Info</h3></header>
      <table width="40%" class="tablesorter" cellspacing="0">
        <tr>
          <td align="left">Balance</td>
          <td align="left">{$VALUE|number_format:"8"}</td>
        </tr>
      </table>
    </article>
{foreach key=ACCOUNT item=ADDRESS from=$ACCOUNTADDRESSES}
{if $ACCOUNT == $NAME}
    <article class="module width_3_quarter">
      <header><h3>Addresses assigned to Account {$ACCOUNT|default:"Default"}</h3></header>
      <table class="tablesorter" cellspacing="0">
        <tbody>
{foreach from=$ACCOUNTADDRESSES[$ACCOUNT] key=ACCOUNT1 item=ADDRESS1}
{if $ADDRESS1@iteration is even by 1}
            <td align="left" style="padding-right: 25px;">{$ADDRESS1}</td>
          </tr>
{else}
          <tr>
            <td align="left" style="padding-right: 25px;">{$ADDRESS1}</td>
{/if}
{/foreach}
        </tbody>
      </table>
    </article>
{/if}
{/foreach}
  </article>
{/foreach}
</article>
{/if}
