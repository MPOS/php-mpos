<article class="module width_quarter">
  <header><h3>PPS Stats</h3></header>
  <div class="module_content">
    <table width="100%">
      <tbody>
        <tr>
          <td><b>PPS Value</b></td>
          <td>{$GLOBAL.ppsvalue}</td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>       
        <tr>
          <td colspan="2"><b><u>Round Shares</u></b> <span id='tt'><img src='{$PATH}/images/questionmark.png' height='15px' width='15px' title='Submitted shares since last found block (ie. round shares)'></span></td>
        </tr>
        <tr>
          <td><b>Pool Valid</b></td>
          <td id="b-pvalid" class="right"></td>
        </tr>
        <tr>
          <td><b>Your Valid</b></td>
          <td id="b-yvalid" class="right"></td>
        </tr>
        <tr>
          <td><b>Pool Invalid</b></td>
          <td id="b-pivalid" class="right"></td>
        </tr>
        <tr>
          <td><b>Your Invalid</b></td>
          <td id="b-yivalid class="right"></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
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
         <tr><td colspan="2">&nbsp;</td></tr>
         <tr>
           <td colspan="2"><b><u>{$GLOBAL.config.currency} Info</u></b></td>
         </tr>
         <tr>
           <td><b>Current Difficulty</b></td>
           <td id="b-diff" class="right"></td>
         </tr>
         <tr>
           <td><b>Current Block</b></td>
           <td id="b-nblock" class="right"></td>
         </tr>
         <tr><td colspan="2">&nbsp;</td></tr>
      </tbody>
    </table>
  </div>
</article>

