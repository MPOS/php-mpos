{include file="global/block_header.tpl" BLOCK_HEADER="Account Details"}
    <form action="{$smarty.server.PHP_SELF}" method="post">
      <input type="hidden" name="page" value="{$smarty.request.page|escape}">
      <input type="hidden" name="action" value="{$smarty.request.action|escape}">
      <input type="hidden" name="do" value="updateAccount">
      <table>
        <tbody><tr><td>Username: </td><td>{$GLOBAL.userdata.username}</td></tr>
        <tr><td>User Id: </td><td>{$GLOBAL.userdata.id}</td></tr>
        <tr><td>API Key: </td><td>{$GLOBAL.userdata.api_key}</td></tr>
        <tr><td>E-Mail: </td><td><input type="text" name="email" value="{$GLOBAL.userdata.email|escape}" size="20"></td></tr>
        <tr><td>Payment Address: </td><td><input type="text" name="paymentAddress" value="{$smarty.request.paymentAddress|default:$GLOBAL.userdata.coin_address|escape}" size="40"></td></tr>
        <tr><td>Donation %: </td><td><input type="text" name="donatePercent" value="{$smarty.request.donatePercent|default:$GLOBAL.userdata.donate_percent|escape}" size="4"><font size="1"> [donation amount in percent (example: 0.5)]</font></td></tr>
        <tr><td>Automatic Payout Threshold: </td><td valign="top"><input type="text" name="payoutThreshold" value="{$smarty.request.payoutThreshold|default:$GLOBAL.userdata.ap_threshold|escape}" size="5" maxlength="5"> <font size="1">[{$GLOBAL.config.ap_threshold.min}-{$GLOBAL.config.ap_threshold.max} {$GLOBAL.config.currency}. Set to '0' for no auto payout]</font></td></tr>
        <tr><td>4 digit PIN: </td><td><input type="password" name="authPin" size="4" maxlength="4"><font size="1"> [The 4 digit PIN you chose when registering]</font></td></tr>
      </tbody></table>
      <input type="submit" class="submit long" value="Update Settings"></form>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" BLOCK_HEADER="Cash Out"}
    <ul><li><font color="">Please note: a {$GLOBAL.config.txfee} {$GLOBAL.config.currency} transaction will apply when processing "On-Demand" manual payments</font></li></ul>
    <form action="{$smarty.server.PHP_SELF}" method="post">
      <input type="hidden" name="page" value="{$smarty.request.page|escape}">
      <input type="hidden" name="action" value="{$smarty.request.action|escape}">
      <input type="hidden" name="do" value="cashOut">
      <table>
        <tbody><tr><td>Account Balance: &nbsp;&nbsp;&nbsp;</td><td>{$GLOBAL.userdata.balance.confirmed|escape} {$GLOBAL.config.currency}</td></tr>
        <tr><td>Payout to: </td><td><h6>{$GLOBAL.userdata.coin_address|escape}</h6></td></tr>
        <tr><td>4 digit PIN: </td><td><input type="password" name="authPin" size="4" maxlength="4"></td></tr>
      </tbody></table>
      <input type="submit" class="submit mid" value="Cash Out"></form>
{include file="global/block_footer.tpl"}

{include file="global/block_header.tpl" BLOCK_HEADER="Change Password"}
    <ul><li><font color="">Note: You will be redirected to login on successful completion of a password change</font></li></ul>
    <form action="{$smarty.server.PHP_SELF}" method="post"><input type="hidden" name="act" value="updatePassword">
      <input type="hidden" name="page" value="{$smarty.request.page|escape}">
      <input type="hidden" name="action" value="{$smarty.request.action|escape}">
      <input type="hidden" name="do" value="updatePassword">
      <table>
        <tbody><tr><td>Current Password: </td><td><input type="password" name="currentPassword"></td></tr>
        <tr><td>New Password: </td><td><input type="password" name="newPassword"></td></tr>
        <tr><td>New Password Repeat: </td><td><input type="password" name="newPassword2"></td></tr>
        <tr><td>4 digit PIN: </td><td><input type="password" name="authPin" size="4" maxlength="4"></td></tr>
      </tbody></table>
      <input type="submit" class="submit long" value="Change Password"></form>
{include file="global/block_footer.tpl"}
