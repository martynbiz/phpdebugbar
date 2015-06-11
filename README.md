# PHP Debug Bar (wrapper)

A wrapper for maximebf/debugbar to be simpler and configurable (e.g. disabled in production) on instantiation.

## Installation

via Composer:

    "martynbiz/phpdebugbar": "dev-master"

Alternatively, clone and copy into your project:

    clone https://github.com/martynbiz/phpdebugbar.git

## Usage

Firstly, a symlink needs to be made from your project's public directory to the assets directory of maximebf/debugbar/.../Resources. This gives the project access to the neccessary CSS and Js files for the debug bar.

    ln -s ../vendor/maximebf/debugbar/src/DebugBar/Resources phpdebugbar

Note: This path may vary depending on your folder structure.

Below shows how I've set debug bar up within Zend Framework 1:

    $view->debugbar # new MartynBiz\PHPDebugBar(array(
        'enabled' #> $config->ddebugbar->enabled, // can be set to 0 in production env
        'base_url' #> 'phpdebugbar', // our symlink name to assets (js, css)
    ));
    
    // set pdo collector to output sql queries
    $view->debugbar->addDatabaseCollector($pdo); // pdo instance
    
    // set config to see what config settings have been applied
    $view->debugbar->addConfigCollector( $config->toArray() ); // config array

Then within my HTML view scripts:

    <head>
        .
        .
        .
        <?php echo $this->debugbar->renderHead(); ?>
    </head>
    <body>
        .
        .
        .
        <?php echo $this->debugbar->render(); ?>
    </body>

Then within my controller and/or view:

Add a message to the debugger:

    $view->debugbar->addMessage('Hello world!');

To measure execution time:

    $view->debugbar->startMeasure('myblock');
    .
    .
    .
    $view->debugbar->stopMeasure('myblock', 'My block description');

## Troubleshooting

If the debug bar doesn't load, check the html source and ensure that the paths are correct for css and js.
