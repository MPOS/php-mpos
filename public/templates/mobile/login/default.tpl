      <form action="{$smarty.server.SCRIPT_NAME}?page=login" method="post" id="loginForm" data-ajax="false">
        <input type="hidden" name="to" value="{($smarty.request.to|default:"{$smarty.server.SCRIPT_NAME}?page=dashboard")|escape}" />
        <p><input type="text" name="username" value="" id="userForm" maxlength="20"></p>
        <p><input type="password" name="password" value="" id="passForm" maxlength="20"></p>
        <center><p><input type="submit" value="Login"></p></center>
      </form>
      <center><p><a href="{$smarty.server.SCRIPT_NAME}?page=password"><font size="1">Forgot your password?</font></a></p></center>
