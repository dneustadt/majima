<!doctype html>
<html>
<head>
    <title>{block "title"}majima{/block}</title>
    {block "meta"}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    {/block}

    {block "css"}
        <link rel="stylesheet" href="{link "/web/css/style.min.css", $.cache_buster}">
        {if $.admin}
            <link rel="stylesheet" href="{link "/web/css/style.backend.min.css", $.cache_buster}">
        {/if}
    {/block}
</head>
<body data-base-url="{$.base_url}" data-path-info="{$.path_info}">
    {block "header-outer"}
        <header>
            {block "header"}
                {include "Base/header.tpl"}
            {/block}
        </header>
    {/block}

    {block "content-outer"}
        <section class="col-xs-12 col-lg-offset-1 col-lg-10">
            {block "content"}
            {/block}
        </section>
    {/block}

    {block "footer-outer"}
        <footer>
            {block "footer"}
                {include "Base/footer.tpl"}
            {/block}
        </footer>
    {/block}

    {block "js"}
        <script src="{link "/web/js/scripts.min.js", $.cache_buster}"></script>
        {if $.admin}
            <script src="{link "/web/js/scripts.backend.min.js", $.cache_buster}"></script>
        {/if}
    {/block}
</body>
</html>