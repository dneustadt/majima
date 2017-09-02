<nav class="row" role="navigation">
    {block "footer-navigation"}
        <div class="col-xs-12 col-lg-offset-1 col-lg-10">
            {block "footer-navigation-inner"}{/block}
        </div>
    {/block}
</nav>
<div class="row footer-copyright">
    {block "footer-copyright"}
        <div class="col-xs-12 col-lg-offset-1 col-lg-10 center-xs">
            powered by majima
        </div>
    {/block}
    {block "footer-admin"}
        {if !$.admin}
            <div class="col-xs-12 col-lg-offset-1 col-lg-10">
                <form action="{url "admin_login"}" method="post">
                    <div class="row">
                        <div>
                            <input placeholder="Username" type="text" id="user" name="_username" required>
                        </div>
                        <div>
                            <input placeholder="Password" type="password" id="password" name="_password" required>
                        </div>
                        <div>
                            <input type="hidden" name="_target_path" value="/" />
                            <button type="submit" class="btn"><span class="typcn typcn-key"></span> Login</button>
                        </div>
                    </div>
                </form>
            </div>
        {else}
            <div class="col-xs-12 col-lg-offset-1 col-lg-10">
                <a href="{url "admin_logout"}" class="button"><span class="typcn typcn-eject"></span> Logout</a>
                <a href="{url "admin_clearcache"}" class="button"><span class="typcn typcn-trash"></span> Clear Cache</a>
            </div>
        {/if}
    {/block}
</div>
