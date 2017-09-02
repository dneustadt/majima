# majima

[![Join the chat at https://gitter.im/majima-framework/Lobby](https://badges.gitter.im/majima-framework/Lobby.svg)](https://gitter.im/majima-framework/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

- **License**: MIT
- **Github Repository**: <https://github.com/dneustadt/majima>

majima is a lightweight PHP framework that is based on [Symfony](https://github.com/symfony/symfony) 
components. It features a plugin system that relies heavily on the concept of dependency injection 
and should suffice as a foundation for most web applications.

### Features

* Plugin System
  * Template inheritance
  * Collectors for JS and LESS compiling
  * install and update routines
* Template Engine
  * functions for inheriting, routing and asset linking
  * HTML bootstrap template
* Query Builder
* User Provider

### Server requirements

- PHP 5.6.4 or above
- [Apache 2.2 or 2.4](https://httpd.apache.org/)
- Apache's `mod_rewrite` module
- MySQL 5.5.0 or above

#### Required PHP extensions

-   <a href="http://php.net/manual/en/book.json.php" target="_blank">json</a>
-   <a href="http://php.net/manual/en/book.mbstring.php" target="_blank">mbstring</a>
-   <a href="http://php.net/manual/en/book.session.php" target="_blank">session</a>
-   <a href="http://php.net/manual/en/ref.pdo-mysql.php" target="_blank">PDO/MySQL</a>

### Installation

1.) Clone the git repository to the desired location using:

    git clone https://github.com/dneustadt/majima.git
    
or use composer

    composer require dneustadt/majima

2.) Set the correct directory permissions:

    chmod -R 755 var
    chmod -R 755 web
    chmod -R 755 upload
    
3.) Install dependencies from the same directory

    composer install
    
4.) Create a new MySQL database

5.) Run the majima installation script by accessing the URL in a web browser. 
This should be the URL where you uploaded the majima files.
* If you installed majima in the root directory, you should visit: `http://example.com/install`
* If you installed majima in its own subdirectory called `majima`, for example, 
you should visit: `http://example.com/majima/install`

### Plugins

**For a real life examples see the demo plugins:**
* <https://github.com/dneustadt/majima-grid>
* <https://github.com/dneustadt/majima-pages>

To create a new plugin first make a new directory under the `plugins` directory. In this example
it will be `MyPlugin`. Create a new class `MyPlugin.php` within that new directory. The name of
the file must match the name of the containing folder.

By extending `PluginAbstract` you will gain access to the `ContainerBuilder` reference

    $this->container // returns instance of ContainerBuilder
    
#### Example of a Plugin class:    

```php
namespace Plugins\MyPlugin;
 
class MyPlugin extends PluginAbstract
{
    private $priority = 0;
    
    private $version = '1.0.0';
 
    public function getPriority()
    {
        return $this->priority;
    }
```    

Return an integer for the priority in which order plugins should be loaded. default is 1

```php
    public function getVersion()
    {
        return $this->version;
    }
```

Returns the version of the plugin. default is 1.0.0. If the version is newer than what is
saved in the database, the method `update()` will be called.

```php
    public function update()
    {
    }
    
    public function install()
    {
    }
```

Do something if the plugin isn't registered in database yet, e.g. create tables.

```php
    public function build()
    {
    }
```

Use this method and `$this->container` to add compiler passes, set container services or parameters.

```php
    public function registerControllers()
    {
        return new ControllerCollection([
            'my_plugin.my_controller' => MyController::class,
            'majima.admin_controller' => AdminControllerDecorator::class,
        ]);
    }
```

Use this method to register new controllers or decorate existing ones. Since the id `majima.admin_controller`
is already set, the service for that controller will be decorated. The new id for the decorated service
will then be prepended by snake case of the plugin class name, so in this case it would be 
`my_plugin.majima.admin_controller`

```php
    public function setRoutes()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->addRoute(
            new RouteConfig(
                'myPlugin_index',
                '/hello/world/',
                'my_plugin.my_controller:indexAction'
            )
        );
        $routeCollection->addRoute(
            new RouteConfig(
                'admin_new',
                '/admin/new/',
                'my_plugin.majima.admin_controller:newAction'
            )
        );
        return $routeCollection;
    }
```

Enhance the routes to actions of controllers for the routing. The name/id of a route, e.g. `myPlugin_index`,
will also determine, where the view for that action will be looked for. Underscores will be converted
to slashes and the first letter will be capitalized. So in this example majima would look for a template
`MyPlugin/index.tpl`

```php
    public function setViewResources()
    {
        $viewCollection = new ViewCollection(join(DIRECTORY_SEPARATOR, [__DIR__, 'Resources']));
        $viewCollection->setViews(['views']);
        return $viewCollection;
    }
 
    public function setCssResources()
    {
        $assetCollection = new AssetCollection(join(DIRECTORY_SEPARATOR, [__DIR__, 'Resources', 'css', 'src']));
        $assetCollection->setFrontendAssets([
            join(DIRECTORY_SEPARATOR, ['frontend', 'all.scss']),
        ]);
        $assetCollection->setBackendAssets([
            join(DIRECTORY_SEPARATOR, ['backend', 'all.scss']),
        ]);
        return $assetCollection;
    }
    
    public function setJsResources()
    {
        $assetCollection = new AssetCollection(join(DIRECTORY_SEPARATOR, [__DIR__, 'Resources', 'js', 'src']));
        $assetCollection->setFrontendAssets([
            join(DIRECTORY_SEPARATOR, ['frontend', 'jquery.js']),
        ]);
        $assetCollection->setBackendAssets([
            join(DIRECTORY_SEPARATOR, ['backend', 'jquery.plugin.js']),
        ]);
        return $assetCollection;
    }
}
```

Collect views for routes and assets for compilation. Assets can be separated for use in frontend and backend
context (when user is logged in).

##### Controller Example

By extending `MajimaController` you will gain access to the following references

    $this->container //returns instance of DependencyInjection\Container
    $this->dbal //returns instance of FluentPDO
    $this->engine //returns instance of Dwoo\Core

```php
class MyController extends MajimaController
{
    /**
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $bar = $this->dbal->from('foo')->fetchAll();
    
        $this->assign(
            [
                'foo' => $bar,
            ]
        );
    }
}
```

[FluentPDO](https://github.com/envms/fluentpdo) is a query builder  
[Dwoo](https://github.com/dwoo-project/dwoo) is the template engine of majima  
Use `assign` to pass an array of data to views.  

If the route to the action can be resolved as a path to a template (see above),
you don't have to return anything in your action. If you're outside of the
conventions, you can render a template and return a response yourself:

```php
    $template = $this->engine->render('/path/to/my/template.tpl');

    return new Response(
        $template,
        200,
        ['Content-Type' => 'text/html']
    );
```    

##### Template functions

Adding to Dwoo's own functions majima introduces a few more:

    {inherits "Index/index.tpl"}

Virtually the same as Dwoo's `extends` but allows you to inherit templates
from the majima's base HTML bootstrap and views of previously loaded plugins.

    {url "index_index" array('foo' => $bar)}
    
Will generate the url of a registered route. Optionally pass
an array of GET parameters.

    {link "/web/css/style.min.css", $.cache_buster}
    
Get the absolute path to an asset. Optionally pass a cache buster value.
You can use the global `$.cache_buster` which will be regenerated once the
cache is emptied.