<html>
<body>
<p>{$WEBSITENAME} Message,</p>
<p>{nocache}{$DATA.senderName}{/nocache} Sent you a message</p>
<p>Senders Email: {nocache}{$DATA.senderEmail}{/nocache}</p>
<p>Subject: {nocache}{$DATA.senderSubject}{/nocache}</p>
<p>Personal message:</p><p>{nocache}{$DATA.senderMessage}{/nocache}</p>
<p></p>
</body>
</html>
