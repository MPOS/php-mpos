<div class="block" style="clear:none;">
  <div class="block_head">
    <div class="bheadl"></div>
    <div class="bheadr"></div>
    <h1>{$GLOBAL.config.currency} Profitability Calculator</h1>

  </div>
  <div class="block_content" style="padding:10px;">
            	<table>
			<tr>
				<th scope="col">Mining</th>
				<th scope="col">kH/s</th>
				<th scope="col">Difficulty</th>
				<th scope="col">Pool Fee %</th>
				<th scope="col">Watts</th>
				<th scope="col">$/KwH</th>
			</tr>
                                <input type="hidden" id="coinamount_btc" maxlength="10" size="10" value="{$GLOBAL.price2}"/>
                                <input type="hidden" id="coinamount_usd" maxlength="10" size="10" value="{$GLOBAL.price}"/>
			<tr>
				<td><input type="button" value=" Calculate " onclick="coin_rate()"/></td>
				<td><input type="text" id="coinamount_mhs" maxlength="10" size="8" value="1000"/></td>
				<td><input type="text" id="coinamount_diff" maxlength="20" size="15" value="{$GLOBAL.difficulty}"/></td>
				<td><input type="text" id="coinamount_fee" maxlength="4" size="4" value="0"/></td>
				<td><input type="text" id="coinamount_watts" maxlength="5" size="5" value="500"/></td>
				<td><input type="text" id="coinamount_usd_kwh" maxlength="4" size="4" value="0.10"/></td>
			</tr>
		</table>
                <table>
                        <tr>
                                <th scope="col">Trading Market</th>
                                <th scope="col">{$GLOBAL.config.currency}/BTC</th>
                                <th scope="col">{$GLOBAL.config.currency}/USD</th>
                        </tr>

                        <tr>
                                <td><a href="{$GLOBAL.url}">Market Stats</a></td>
                                <td scope="col">{$GLOBAL.price2|number_format:"7"}</td>
                                <td scope="col">${$GLOBAL.price|number_format:"4"}</td>
                        </tr>
                </table>
                <br/>
		<table>
			<tr>
				<th scope="col">Profit</th>
				<th scope="col">BTC</th>
				<th scope="col">{$GLOBAL.config.currency}</th>
				<th scope="col">USD</th>
				<th scope="col">Power Cost</th>
				<th scope="col">Profit</th>
			</tr>

			<tr>
				<td>Per Day</td>
				<td id="coinamount_btc_gross" maxlength="5" size="5">0</td>
				<td id="coinamount_coin_day" maxlength="12" size="12">0</td>
				<td id="coinamount_usd_gross" maxlength="5" size="5">0</td>
				<td id="coinamount_cost_day" maxlength="5" size="5">0</td>
				<td id="coinamount_usd_profit" maxlength="5" size="5">0</td>
			</tr>
			<tr>
				<td>Per Week</td>
				<td id="coinamount_btc_gross7" maxlength="5" size="5">0</td>
				<td id="coinamount_coin_day7" maxlength="12" size="12">0</td>
				<td id="coinamount_usd_gross7" maxlength="5" size="5">0</td>
				<td id="coinamount_cost_day7" maxlength="5" size="5">0</td>
				<td id="coinamount_usd_profit7" maxlength="5" size="5">0</td>
			</tr>
			<tr>
				<td>Per Month</td>
				<td id="coinamount_btc_gross30" maxlength="5" size="5">0</td>
				<td id="coinamount_coin_day30" maxlength="12" size="12">0</td>
				<td id="coinamount_usd_gross30" maxlength="5" size="5">0</td>
				<td id="coinamount_cost_day30" maxlength="5" size="5">0</td>
				<td id="coinamount_usd_profit30" maxlength="5" size="5">0</td>
			</tr>
			<tr>
				<td>Per Year</td>
				<td id="coinamount_btc_gross365" maxlength="5" size="5">0</td>
				<td id="coinamount_coin_day365" maxlength="12" size="12">0</td>
				<td id="coinamount_usd_gross365" maxlength="5" size="5">0</td>
				<td id="coinamount_cost_day365" maxlength="5" size="5">0</td>
				<td id="coinamount_usd_profit365" maxlength="5" size="5">0</td>
			</tr>
		</table>
                <tr>
                       <li><strong>Note: <font color="orange">This profitability calculator is only an estimate for {$GLOBAL.config.currency}.</font></strong></li>
                </tr>
  </div>
  <div class="bendl"></div>
  <div class="bendr"></div>
</div>
