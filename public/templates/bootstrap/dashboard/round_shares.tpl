         <tr>
           <td colspan="4">Round Shares</td>
         </tr>
         <tr>
           <td colspan="1">Est. Shares</td>
           <td colspan="3" id="b-target" class="text-right">{$ESTIMATES.shares|number_format} (done: {$ESTIMATES.percent}%)</td>
         </tr>
         <tr>
           <td>Pool Valid</td>
           <td id="b-pvalid" class="text-right">{$GLOBAL.roundshares.valid|number_format}</td>
           <td>Your Valid</td>
           <td id="b-yvalid" class="text-right">{$GLOBAL.userdata.shares.valid|number_format}</td>
         </tr>
         <tr>
           <td>Pool Invalid</td>
           <td id="b-pivalid" class="text-right">{$GLOBAL.roundshares.invalid|number_format} {if $GLOBAL.roundshares.valid > 0}({($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%){else}(0.00%){/if}</td></td>
           <td>Your Invalid</td>
           <td id="b-yivalid" class="text-right">{$GLOBAL.userdata.shares.invalid|number_format} {if $GLOBAL.userdata.shares.valid > 0}({($GLOBAL.userdata.shares.invalid / ($GLOBAL.userdata.shares.valid + $GLOBAL.userdata.shares.invalid) * 100)|number_format:"2"}%){else}(0.00%){/if}</td>
         </tr>
