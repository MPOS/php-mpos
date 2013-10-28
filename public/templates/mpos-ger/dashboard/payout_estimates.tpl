
         <tr>
           <td colspan="2"><b><u>{$GLOBAL.config.currency} Estimates</u></b></td>
         </tr>
{if $GLOBAL.config.payout_system != 'pps'}
         <tr>
           <td><b>Block</b></td>
           <td id="b-block" class="right"></td>
         </tr>
         <tr>
           <td><b>Steuer</b></td>
           <td id="b-fee" class="right"></td>
         </tr>
         <tr>
           <td><b>Spende</b></td>
           <td id="b-donation" class="right"></td>
         </tr>
         <tr>
           <td><b>Auszahlung</b></td>
           <td id="b-payout" class="right"></td>
         </tr>
{else}
        <tr>
          <td><b>in 1 Stunde</b></td>
          <td id="b-est1hour" align="left">{$GLOBAL.userdata.estimates.hours1|round:"8"}</td>
        </tr>
        <tr>
          <td><b>in 24 Stunden</b></td>
          <td id="b-est24hours" align="left">{($GLOBAL.userdata.estimates.hours24)|round:"8"}</td>
        </tr>
        <tr>
          <td><b>in 7 Tagen</b></td>
          <td id="b-est7days" align="left">{($GLOBAL.userdata.estimates.days7)|round:"8"}</td>
        </tr>
        <tr>
          <td><b>in 14 Tagen</b></td>
          <td id="b-est14days" align="left">{($GLOBAL.userdata.estimates.days14)|round:"8"}</td>
        </tr>
        <tr>
          <td><b>in 30 Tagen</b></td>
          <td id="b-est30days" align="left">{($GLOBAL.userdata.estimates.days30)|round:"8"}</td>
        </tr>
{/if}
