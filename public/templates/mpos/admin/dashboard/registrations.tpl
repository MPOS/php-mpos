<article class="module width_full">
  <header><h3>Registrations</h3></header>

<article class="module width_quarter">
  <header><h3>by time</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">24 hours</th>
        <th align="center">7 days</th>
        <th align="center">1 month</th>
        <th align="center">6 months</th>
        <th align="center">1 year</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td align="center">{$USER_REGISTRATIONS.24hours}</td>
        <td align="center">{$USER_REGISTRATIONS.7days}</td>
        <td align="center">{$USER_REGISTRATIONS.1month}</td>
        <td align="center">{$USER_REGISTRATIONS.6month}</td>
        <td align="center">{$USER_REGISTRATIONS.1year}</td>
      </tr>
    </tbody>
  </table>
</article>

<article class="module width_half" style="min-height: 350px">
  <header><h3>Last 10 registered Users</h3></header>
  <div>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">ID</th>
        <th>Username</th>
        <th align="left">eMail</th>
        <th align="center">Reg. Date</th>
      </tr>
    </thead>
    <tbody>
{section user $LASTREGISTEREDUSERS}
      <tr class="{cycle values="odd,even"}">
        <td align="center">{$LASTREGISTEREDUSERS[user].id|escape}</td>
        <td>{$LASTREGISTEREDUSERS[user].username|escape}</td>
        <td align="left">{$LASTREGISTEREDUSERS[user].email}</td>
        <td align="center">{$LASTREGISTEREDUSERS[user].signup_timestamp|date_format:"%d/%m %H:%M:%S"}</td>
      </tr>
{/section}
    </tbody>
  </table>
</article>
