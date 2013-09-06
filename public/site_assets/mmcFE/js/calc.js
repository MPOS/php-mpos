function round_to(num, dec) {
  var powah = Math.pow(10, dec);
  return Math.round(num * powah) / powah;
}

function elem(element_name) {
  return document.getElementById(element_name);
}

function calc(coin, value) {
  var sec_per_day =  86400;
  var diff        =  elem(coin + "_diff").value;
  var gh_per_share = 4.2949673;

  fee        =  elem(coin + "_fee").value / 100.0,
  gh_per_sec =  elem(coin + "_mhs").value / 1000000.0,
  coin_usd   =  elem(coin + "_usd").value,
  coin_btc   =  elem(coin + "_btc").value,
  usd_kwh    =  elem(coin + "_usd_kwh").value,
  watts      =  elem(coin + "_watts").value,
  cost_day     =  (watts / 1000) * 24  * usd_kwh,

  coin_per_share  =  (1 / diff) * (value - (value * fee)),
  shares_per_day =  gh_per_sec * (1 / gh_per_share) * sec_per_day,
  coin_per_day    =  coin_per_share * shares_per_day,
  time_hour      =  ((gh_per_share * diff) / gh_per_sec) / 3600,
  time_day       =  time_hour / 24,
  usd_profit         =  (coin_per_day * coin_usd) - cost_day;
  usd_gross         =  (coin_per_day * coin_usd);
  btc_gross         =  (coin_per_day * coin_btc);

  elem(coin + "_cost_day").innerHTML =  "$" + round_to(cost_day, 2);
  elem(coin + "_coin_day").innerHTML  =  round_to(coin_per_day, 4);
  elem(coin + "_usd_profit").innerHTML   =  "$" + round_to(usd_profit, 2);
  elem(coin + "_usd_gross").innerHTML   =  "$" + round_to(usd_gross, 2);
  elem(coin + "_btc_gross").innerHTML   =  round_to(btc_gross, 4);

  elem(coin + "_cost_day7").innerHTML =  "$" + round_to((cost_day*7), 2);
  elem(coin + "_coin_day7").innerHTML  =  round_to((coin_per_day*7), 4);
  elem(coin + "_usd_profit7").innerHTML   =  "$" + round_to((usd_profit*7), 2);
  elem(coin + "_usd_gross7").innerHTML   =  "$" + round_to((usd_gross*7), 2);
  elem(coin + "_btc_gross7").innerHTML   =  round_to((btc_gross*7), 4);

  elem(coin + "_cost_day30").innerHTML =  "$" + round_to((cost_day*30), 2);
  elem(coin + "_coin_day30").innerHTML  =  round_to((coin_per_day*30), 4);
  elem(coin + "_usd_profit30").innerHTML   =  "$" + round_to((usd_profit*30), 2);
  elem(coin + "_usd_gross30").innerHTML   =  "$" + round_to((usd_gross*30), 2);
  elem(coin + "_btc_gross30").innerHTML   =  round_to((btc_gross*30), 4);

  elem(coin + "_cost_day365").innerHTML =  "$" + round_to((cost_day*365), 2);
  elem(coin + "_coin_day365").innerHTML  =  round_to((coin_per_day*365), 4);
  elem(coin + "_usd_profit365").innerHTML   =  "$" + round_to((usd_profit*365), 2);
  elem(coin + "_usd_gross365").innerHTML   =  "$" + round_to((usd_gross*365), 2);
  elem(coin + "_btc_gross365").innerHTML   =  round_to((btc_gross*365), 4);
}

function coin_rate() {
  var do_calc = function (coin, value) {
    setTimeout(function () {
      calc(coin, value);
    });
  };
 do_calc("coinamount", 50);
}
