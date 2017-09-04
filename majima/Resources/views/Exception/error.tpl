{inherits "Index/index.tpl"}

{block "content"}
    <h1>Error</h1>
    <h2>{$errorMessage}</h2>
    <code style="white-space: pre-wrap;">{$exception}</code>
{/block}
