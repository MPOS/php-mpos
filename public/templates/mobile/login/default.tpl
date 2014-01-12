      <form action="{$smarty.server.PHP_SELF}?page=login" method="post" id="loginForm" data-ajax="false">
        <input type="hidden" name="to" value="{($smarty.request.to|default:"{$smarty.server.PHP_SELF}?page=dashboard")|escape}" />
        <p><label for="userForm">Email</label><input type="text" name="username" value="" id="userForm"></p>
        <p><label for="passForm">Password</label><input type="password" name="password" value="" id="passForm"></p>
        <center><p><input type="submit" value="Login"></p></center>
      </form>
      <center><p><a href="{$smarty.server.PHP_SELF}?page=password"><font size="1">Forgot your password?</font></a></p></center>
