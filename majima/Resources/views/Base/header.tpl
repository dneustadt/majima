<nav class="row" role="navigation">
    {block "header-logo-outer"}
        <div class="col-xs-4 col-lg-offset-1 col-lg-3">
            <a href="{url "index_index"}" class="brand-logo">
                {block "header-logo"}<h1>majima</h1>{/block}
            </a>
        </div>
    {/block}
    {block "header-navigation-outer"}
        <div class="col-xs-8 col-lg-7">
            {block "header-navigation"}{/block}
        </div>
    {/block}
</nav>