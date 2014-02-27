{nocache}
<article class="module width_quarter">
  <header><h3>Users</h3></header>
  <table class="tablesorter" cellspacing="0">
    <thead>
      <tr>
        <th align="center">Total</th>
        <th align="center">Active</th>
        <th align="center">Locked</th>
        <th align="center">Admins</th>
        <th align="center">No Fees</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td align="center">{$USER_INFO.total}</td>
        <td align="center">{$USER_INFO.active}</td>
        <td align="center">{$USER_INFO.locked}</td>
        <td align="center">{$USER_INFO.admins}</td>
        <td align="center">{$USER_INFO.nofees}</td>
      </tr>
    </tbody>
  </table>
</article>

<article class="module width_quarter">
  <header><h3>Logins</h3></header>
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
        <td align="center">{$USER_LOGINS.24hours}</td>
        <td align="center">{$USER_LOGINS.7days}</td>
        <td align="center">{$USER_LOGINS.1month}</td>
        <td align="center">{$USER_LOGINS.6month}</td>
        <td align="center">{$USER_LOGINS.1year}</td>
      </tr>
    </tbody>
  </table>
</article>
{/nocache}