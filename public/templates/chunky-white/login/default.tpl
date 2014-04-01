  <section class="widget login-widget">
        <header class="text-align-center">
            <h4>Login to your account</h4>
        </header>
        <div class="body">
          <form action="{$smarty.server.PHP_SELF}?page=login" method="post" class="no-margin">
            <input type="hidden" name="to" value="{($smarty.request.to|default:"{$smarty.server.PHP_SELF}?page=dashboard")|escape}" />
                <fieldset>
                    <div class="form-group no-margin">
                        <label for="username" >Username or E-mail</label>

                        <div class="input-group input-group-lg">
                                <span class="input-group-addon">
                                    <i class="eicon-user"></i>
                                </span>
                            <input id="username" type="username" name="username" class="form-control input-lg"
                                   placeholder="Your Username or E-mail" value="{$smarty.request.username|default:""|escape}" required>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="password" >Password</label>

                        <div class="input-group input-group-lg">
                                <span class="input-group-addon">
                                    <i class="fa fa-lock"></i>
                                </span>
                            <input name="password" id="password" type="password" class="form-control input-lg"
                                   placeholder="Your Password" required>
                        </div>

                    </div>
                </fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-block btn-lg btn-danger">
                        <span class="small-circle"><i class="fa fa-caret-right"></i></span>
                        <small>Sign In</small>
                    </button>
                    <div class="forgot"><a class="forgot" href="{$smarty.server.PHP_SELF}?page=password">Forgot your Password?</a></div>
                </div>
            </form>
        </div>
    </section>
