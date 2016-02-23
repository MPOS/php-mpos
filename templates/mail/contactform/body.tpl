<html>
<body>
<p>{t 1=$WEBSITENAME}%1 Message,{/t}</p>
<p>{nocache}{$DATA.senderName}{/nocache} {t}Sent you a message{/t}</p>
<p>{t}Senders Email: {/t}{nocache}{$DATA.senderEmail}{/nocache}</p>
<p>{t}Subject: {/t}{nocache}{$DATA.senderSubject}{/nocache}</p>
<p>{t}Personal message{/t}:</p><p>{nocache}{$DATA.senderMessage}{/nocache}</p>
<p></p>
</body>
</html>
