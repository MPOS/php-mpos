<div class="row">
    <div class="col-md-7">
        <section class="widget">
            <header>
                <h4><i class="fa fa-user"></i> Register new account</h4>
            </header>
            <div class="body">
                <form action="{$smarty.server.PHP_SELF}" id="user-form" class="form-horizontal label-left"
                      novalidate="novalidate"
                      method="post">
                    <input type="hidden" name="page" value="{$smarty.request.page|escape}">
                {if $smarty.request.token|default:""}
                 §  <input type="hidden" name="token" value="{$smarty.request.token|escape}" />
                {/if}
                    <input type="hidden" name="action" value="register">
                    <input type="hidden" value="1" name="tac" id="tac">
                    

                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="username">Username</label>
                            <div class="controls form-group">
                              <div class="input-group col-sm-8">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input name="username" id="username" class="form-control" size="16" type="text" value="{$smarty.post.username|escape|default:""}" placeholder="Username" required>
                              </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="password-field">Password</label>
                            <div class="controls form-group">
                                <div class="input-group col-sm-8">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input name="password1" type="password" class="form-control" id="password-field-1" placeholder="Password" required>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="password-field">Password repeat</label>
                            <div class="controls form-group">
                                <div class="input-group col-sm-8">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input name="password2" type="password" class="form-control" id="password-field-2" placeholder="Password repeat" required>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="normal-field">Email</label>
                            <div class="controls form-group">
                                <div class="col-sm-8">
                                    <input type="text" id="email1" name="email1" class="form-control" placeholder="Email" required>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="normal-field">Email repeat</label>
                            <div class="controls form-group">
                                <div class="col-sm-8">
                                    <input type="text" id="email2" name="email2" class="form-control" placeholder="Email repeat" required>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="password-field">PIN</label>
                            <div class="controls form-group">
                                <div class="input-group col-sm-3">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" name="pin" size="4" maxlength="4" id="pin-field" placeholder="PIN" required>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Register</button>
                        <button type="button" class="btn btn-default">Cancel</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

