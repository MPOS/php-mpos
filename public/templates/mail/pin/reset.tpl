<html>
<body>
<p>Hello {$DATA.username},</p><br />
<p>You have requested a PIN reset through our online form.</p>
<p>Randomly Generated PIN: {$DATA.pin}</p>
<p>Cheers,</p>
<p>{$GLOBAL.website.name|default:"Unknown Pool"}</p>
</body>
</html>
