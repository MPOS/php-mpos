<article class="module width_quarter">
  <header><h3>Dashboard</h3></header>
  <div class="module_content">
    <table width="50%" align="center">
      <tbody>
        <tr>
          <td><b>PPS Value</b></td>
          <td>{$GLOBAL.ppsvalue}</td>
        </tr>
      </tbody>
    </table>
    <table align="left" width="50%">
      <tbody>
        <tr>
          <td colspan="2"><b><u>Round Shares</u></b> <span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='Submitted shares since last found block (ie. round shares)'></span></td>
        </tr>
        <tr>
          <td><b>Pool Valid</b></td>
          <td class="right"><i>{$GLOBAL.roundshares.valid|number_format}</i></td>
        </tr>
        <tr>
          <td><b>Your Valid</b></td>
          <td class="right"><i>{$GLOBAL.userdata.shares.valid|number_format}</i></td>
        </tr>
        <tr>
          <td><b>Pool Invalid</b></td>
          <td class="right"><i>{$GLOBAL.roundshares.invalid|number_format}</i>{if $GLOBAL.roundshares.valid > 0}<font size='1px'> ({($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%)</font>{/if}</td>
        </tr>
        <tr>
          <td><b>Your Invalid</b></td>
          <td class="right"><i>{$GLOBAL.userdata.shares.invalid|number_format}</i>{if $GLOBAL.roundshares.valid > 0}<font size='1px'> ({($GLOBAL.userdata.shares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%)</font>{/if}</td>
        </tr>
      </tbody>
    </table>
    <table align="left" width="50%">
      <tbody>
        <tr><td colspan="2"><b><u>{$GLOBAL.config.currency} Estimates</u></b></td></tr>
        <tr>
          <td><b>in 24 hours</b></td>
          <td class="right">{($GLOBAL.userdata.sharerate * 24 * 60 * 60 * $GLOBAL.ppsvalue)|number_format:"8"}</td>
        </tr>
        <tr>
          <td><b>in 7 days</b></td>
          <td class="right">{($GLOBAL.userdata.sharerate * 7 * 24 * 60 * 60 * $GLOBAL.ppsvalue)|number_format:"8"}</td>
        </tr>
        <tr>
          <td><b>in 14 days</b></td>
          <td class="right">{($GLOBAL.userdata.sharerate * 14 * 24 * 60 * 60 * $GLOBAL.ppsvalue)|number_format:"8"}</td>
        </tr>
      </tbody>
    </table>
  </div>
</article>

