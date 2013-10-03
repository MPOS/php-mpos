 <article class="module width_quarter">
   <header><h3>Dashboard</h3></header>
   <div class="module_content">
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
           <td><b>Your Valid<b></td>
           <td class="right"><i>{$GLOBAL.userdata.shares.valid|number_format}</i><font size='1px'></font></b></td>
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
     <table align="right" width="50%">
       <tbody>
         <tr>
           <td colspan="2"><b><u>{$GLOBAL.config.currency} Round Estimate</u></b></td>
         </tr>
         <tr>
           <td><b>Block</b></td>
           <td class="right">{$GLOBAL.userdata.est_block|number_format:"8"}</td>
         </tr>
         <tr>
           <td><b>Fees</b></td>
           <td class="right">{$GLOBAL.userdata.est_fee|number_format:"8"}</td>
         </tr>
         <tr>
           <td><b>Donation</b></td>
           <td class="right">{$GLOBAL.userdata.est_donation|number_format:"8"}</td>
         </tr>
         <tr>
           <td><b>Payout</b></td>
           <td class="right">{$GLOBAL.userdata.est_payout|number_format:"8"}</td>
         </tr>
         <tr><td colspan="2">&nbsp;</td></tr>
       </tbody>
      </table>
    </div>
 </article>

