         <tr>
           <td colspan="4" class="text-center"><b>{$GLOBAL.config.currency} Estimates</b></td>
         </tr>
{if $GLOBAL.config.payout_system != 'pps'}
         <tr>
           <td><b>Block</b></td>
           <td id="b-block" class="text-left">{$GLOBAL.userdata.estimates.block|number_format:"8"}</td>
           <td><b>Fees</b></td>
           <td id="b-fee" class="text-left">{$GLOBAL.userdata.estimates.fee|number_format:"8"}</td>
         </tr>
         <tr>
           <td><b>Donation</b></td>
           <td id="b-donation" class="text-left">{$GLOBAL.userdata.estimates.donation|number_format:"8"}</td>
           <td><b>Payout<b/></td>
           <td id="b-payout" class="text-left">{$GLOBAL.userdata.estimates.payout|number_format:"8"}</td>
         </tr>
{else}
        <tr>
          <td>in 1 hour</td>
          <td id="b-est1hour" class="text-right">{$GLOBAL.userdata.estimates.hours1|number_format:"8"}</td>
          <td>in 24 hours</td>
          <td id="b-est24hours" class="text-right">{($GLOBAL.userdata.estimates.hours24)|number_format:"8"}</td>
        </tr>
        <tr>
          <td>in 7 days</td>
          <td id="b-est7days" class="text-right">{($GLOBAL.userdata.estimates.days7)|number_format:"8"}</td>
          <td>in 14 days</td>
          <td id="b-est14days" class="text-right">{($GLOBAL.userdata.estimates.days14)|number_format:"8"}</td>
        </tr>
      <!--
        <tr>
          <td>in 30 days</td>
          <td id="b-est30days" class="text-right">{($GLOBAL.userdata.estimates.days30)|number_format:"8"}</td>
        </tr>
      !-->
{/if}
