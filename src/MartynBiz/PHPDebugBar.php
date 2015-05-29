<?php 

namespace MartynBiz;

/**
 * This is a simple wrapper class for DebugBar\StandardDebugBar
 * It allows the bar to be configurable e.g. disabled in production env
 * Also, it encapsulated everything so only the wrapper needs instantiating
 */

use DebugBar\StandardDebugBar;
use DebugBar\DataCollector\PDO\TraceablePDO;
use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DataCollector\ConfigCollector;

class PHPDebugBar
{
    /**
     * @var array $options Options for the wrapper and/or debugbar
     */
    protected $options;
    
    /**
     * @var StandardDebugBar $debugbar The debugbar instance
     */
    protected $debugbar;
    
    /**
     * @var 
     */
    protected $debugbarRenderer;
    
    /**
     * Constructor
     * @param mixed $options Options for the wrapper and/or debugbar
     * @param StandardDebugBar $debugbar The PHP debug bar object for test mocking
     */
    public function __construct($options=array(), $debugbar=null)
    {
        // set defaults
        $this->options = array_merge(array(
            'enabled' => true, // pass in as false in production env
            'base_url' => '',
        ), $options);
        
        // set debugbar
        // if null, we can assume that the develop wants to use the StandardDebugBar
        // otherwise, we can pass in a mock instance for testing so not too tightly 
        // coupled
        if ($debugbar instanceof StandardDebugBar) {
            $this->debugbar = $debugbar;
        } else {
            $this->debugbar = new StandardDebugBar();
        }
        
        // set renderer
        $this->debugbarRenderer = $this->debugbar->getJavascriptRenderer()->setBaseUrl($this->options['base_url']);
    }
    
    /**
     * Will start the execution time clock for a given $id
     * @param mixed $data Data to dump
     */
    public function addMessage($data)
    {
        // if debugbar is not enabled, return false
        if (!$this->options['enabled'])
            return false;
        
        // add message to the debugbar
        $this->debugbar["messages"]->addMessage($data);
    }
    
    /**
     * Will start the execution time clock for a given $id
     * @param string $id Identitfier for this time measurement
     * @param string $desc (optional) Description for this time measurement
     */
    public function startMeasure($id, $desc=null)
    {
        // if debugbar is not enabled, return false
        if (!$this->options['enabled'])
            return false;
        
        // start the time measurement
        $this->debugbar['time']->startMeasure($id, $desc);
    }
    
    /**
     * Will stop the execution time clock for a given $id
     * @param string $id Identitfier for this time measurement
     */
    public function stopMeasure($id)
    {
        // if debugbar is not enabled, return false
        if (!$this->options['enabled'])
            return false;
        
        // stop the time measurement
        $this->debugbar['time']->startMeasure($id);
    }

    
    /**
     * Will render the debug bar
     */
    public function render()
    {
        // if debugbar is not enabled, return false
        if (!$this->options['enabled'])
            return false;
        
        return $this->debugbarRenderer->render();
    }
    
    /**
     * Will render the <head> stuff e.g. css, js
     */
    public function renderHead()
    {
        // if debugbar is not enabled, return false
        if (!$this->options['enabled'])
            return false;
        
        return $this->debugbarRenderer->renderHead();
    }
    
    /**
     * Allows SQL queries to be logged
     * @param PDO $pdo The PDO instance to track
     */
    public function addDatabaseCollector(\PDO $pdo)
    {
        $pdo = new TraceablePDO( $pdo );
        $this->debugbar->addCollector(new PDOCollector($pdo));
    }
    
    /**
     * Allows name/value pair arrays (or nested) to be logged
     * @param array $config The config file to track
     */
    public function addConfigCollector($config)
    {
        $this->debugbar->addCollector(new ConfigCollector($config));
    }
}