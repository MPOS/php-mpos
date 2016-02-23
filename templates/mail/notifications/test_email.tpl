{include file="../global/header.tpl"}
<h1>{t}Test email{/t}</h1>
<p>{t}If you see this email - your email protocol is configured correctly{/t}</p>
<p>{t 1=$DATA.coinname}Coin name: %1{/t}</p>
<p>{t 1=$DATA.stratumurl 2=$DATA.stratumport}Stratum: %1:%2{/t}</p>
{include file="../global/footer.tpl"}
