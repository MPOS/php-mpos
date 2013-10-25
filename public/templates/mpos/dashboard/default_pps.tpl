<article class="module width_quarter">
  <header><h3>PPS Stats</h3></header>
  <div class="module_content">
    <table width="100%">
      <tbody>
        <tr>
          <td><b>PPS Value</b></td>
          <td>{$GLOBAL.ppsvalue}</td>
        </tr>
        <tr>
          <td><b>PPS Difficulty</b></td>
          <td id="b-ppsdiff">{$GLOBAL.userdata.sharedifficulty|number_format:"2"}</td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
          <td colspan="2"><b><u>Round Shares</u></b> <span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='Submitted shares since last found block (ie. round shares)'></span></td>
        </tr>
        <tr>
          <td><b>Pool Valid</b></td>
          <td id="b-pvalid" align="right"></td>
        </tr>
        <tr>
          <td><b>Your Valid</b></td>
          <td id="b-yvalid" align="right"></td>
        </tr>
        <tr>
          <td><b>Pool Invalid</b></td>
          <td id="b-pivalid" align="right"></td>
        </tr>
        <tr>
          <td><b>Your Invalid</b></td>
          <td id="b-yinvalid" align="right"></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr><td colspan="2"><b><u>{$GLOBAL.config.currency} Estimates</u></b></td></tr>
        <tr>
          <td><b>in 1 hour</b></td>
          <td id="b-est1hour" align="right">{$GLOBAL.userdata.estimates.hours1|round:"8"}</td>
        </tr>
        <tr>
          <td><b>in 24 hours</b></td>
          <td id="b-est24hours" align="right">{($GLOBAL.userdata.estimates.hours24)|round:"8"}</td>
        </tr>
        <tr>
          <td><b>in 7 days</b></td>
          <td id="b-est7days" align="right">{($GLOBAL.userdata.estimates.days7)|round:"8"}</td>
        </tr>
        <tr>
          <td><b>in 14 days</b></td>
          <td id="b-est14days" align="right">{($GLOBAL.userdata.estimates.days14)|round:"8"}</td>
        </tr>
        <tr>
          <td><b>in 30 days</b></td>
          <td id="b-est30days" align="right">{($GLOBAL.userdata.estimates.days30)|round:"8"}</td>
        </tr>
         <tr><td colspan="2">&nbsp;</td></tr>
         <tr>
           <td colspan="2"><b><u>{$GLOBAL.config.currency} Info</u></b></td>
         </tr>
         <tr>
           <td><b>Current Difficulty</b></td>
           <td id="b-diff" align="right"></td>
         </tr>
         <tr>
           <td><b>Current Block</b></td>
           <td id="b-nblock" align="right"></td>
         </tr>
         <tr><td colspan="2">&nbsp;</td></tr>
      </tbody>
    </table>
  </div>
</article>

