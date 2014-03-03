         <tr>
           <td colspan="4">Network Info</td>
         </tr>
         <tr>
           <td>Difficulty</td>
           <td id="b-diff" class="text-right">{$NETWORK.difficulty|number_format:"8"}</td>
           <td>Est Next Difficulty</td>
           <td id="b-nextdiff" class="text-right">{$NETWORK.EstNextDifficulty|number_format:"8"} (Change in {$NETWORK.BlocksUntilDiffChange} Blocks)</td>
         </tr>
         <tr>
           <td>Est. Avg. Time per Block</td>
           <td id="b-esttimeperblock" class="text-right">{$NETWORK.EstTimePerBlock|seconds_to_words}</td>
           <td>Current Block</td>
           <td id="b-nblock" class="text-right">{$NETWORK.block}</td>
         </tr>
