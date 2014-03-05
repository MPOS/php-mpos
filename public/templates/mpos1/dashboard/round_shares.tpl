         <tr>
           <td colspan="4" class="text-center"><b>Round Shares</b></td>
         </tr>
         <tr>
           <td colspan="1"><b>Est. Shares</b></td>
           <td colspan="3" id="b-target" class="text-left">{$ESTIMATES.shares|number_format} (done: {$ESTIMATES.percent}%)</td>
         </tr>
         <tr>
           <td><b>Pool Valid</b></td>
           <td id="b-pvalid" class="text-left">{$GLOBAL.roundshares.valid|number_format}</td>
           <td><b>Your Valid</b></td>
           <td id="b-yvalid" class="text-left">{$GLOBAL.userdata.shares.valid|number_format}</td>
         </tr>
         <tr>
           <td><b>Pool Invalid</b></td>
           <td id="b-pivalid" class="text-left">{$GLOBAL.roundshares.invalid|number_format} {if $GLOBAL.roundshares.valid > 0}({($GLOBAL.roundshares.invalid / ($GLOBAL.roundshares.valid + $GLOBAL.roundshares.invalid) * 100)|number_format:"2"}%){else}(0.00%){/if}</td></td>
           <td><b>Your Invalid</b></td>
           <td id="b-yivalid" class="text-left">{$GLOBAL.userdata.shares.invalid|number_format} {if $GLOBAL.userdata.shares.valid > 0}({($GLOBAL.userdata.shares.invalid / ($GLOBAL.userdata.shares.valid + $GLOBAL.userdata.shares.invalid) * 100)|number_format:"2"}%){else}(0.00%){/if}</td>
         </tr>
