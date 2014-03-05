         <tr>
           <td colspan="4" class="text-center"><b>Network Info</b></td>
         </tr>
         <tr>
           <td><b>Difficulty</b></td>
           <td id="b-diff" class="text-left">{$NETWORK.difficulty|number_format:"8"}</td>
           <td><b>Est Next Difficulty</b></td>
           <td id="b-nextdiff" class="text-left">{$NETWORK.EstNextDifficulty|number_format:"8"} (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</td>
         </tr>
         <tr>
           <td><b>Est. Avg. Time per Block</b></td>
           <td id="b-esttimeperblock" class="text-left">{$NETWORK.EstTimePerBlock|seconds_to_words}</td>
           <td><b>Current Block</b></td>
           <td id="b-nblock" class="text-left">{$NETWORK.block}</td>
         </tr>
