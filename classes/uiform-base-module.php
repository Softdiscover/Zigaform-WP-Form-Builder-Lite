<?php

/**
 * Frontend
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      https://wordpress-form-builder.zigaform.com/
 */
if ( ! defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if ( class_exists('Uiform_Base_Module')) {
    return;
}

/**
 * Abstract class to define/implement base methods for all module classes
 */
abstract class Uiform_Base_Module
{


    private static $instances = array();
    public static $_modules   = array();
    public static $_models    = array();

    public static $_addons           = array();
    public static $_addons_actions   = array();
    public static $_form_data        = array();

    /*
     * Magic methods
     */

    /**
     * Public getter for protected variables
     *
     * @mvc Model
     *
     * @param string $variable
     * @return mixed
     */
    public function __get($variable)
    {
        $module = get_called_class();

        if ( in_array($variable, $module::$readable_properties)) {
            return $this->$variable;
        } else {
            throw new Exception(__METHOD__ . ' error: $' . $variable . " doesn't exist or isn't readable.");
        }
    }

    /**
     * Public setter for protected variables
     *
     * @mvc Model
     *
     * @param string $variable
     * @param mixed  $value
     */
    public function __set($variable, $value)
    {
        $module = get_called_class();

        if ( in_array($variable, $module::$writeable_properties)) {
            $this->$variable = $value;

            if ( ! $this->is_valid()) {
                throw new Exception(__METHOD__ . ' error: $' . $value . ' is not valid.');
            }
        } else {
            throw new Exception(__METHOD__ . ' error: $' . $variable . " doesn't exist or isn't writable.");
        }
    }

    /*
     * Non-abstract methods
     */

    /**
     * Provides access to a single instance of a module using the singleton pattern
     *
     * @mvc Controller
     *
     * @return object
     */
    public static function get_instance()
    {
         $module = get_called_class();

        if ( ! isset(self::$instances[ $module ])) {
            self::$instances[ $module ] = new $module();
        }

        return self::$instances[ $module ];
    }

    /**
     * Render a template
     *
     * Allows parent/child themes to override the markup by placing the a file named basename( $default_template_path ) in their root folder,
     * and also allows plugins or themes to override the markup by a filter. Themes might prefer that method if they place their templates
     * in sub-directories to avoid cluttering the root folder. In both cases, the theme/plugin will have access to the variables so they can
     * fully customize the output.
     *
     * @mvc @model
     *
     * @param  string $default_template_path The path to the template, relative to the plugin's `views` folder
     * @param  array  $variables             An array of variables to pass into the template's scope, indexed with the variable name so that it can be extract()-ed
     * @param  string $require               'once' to use require_once() | 'always' to use require()
     * @return string
     */
    protected static function render_template($default_template_path = false, $variables = array(), $require = 'once')
    {
        //$template_path = locate_template( basename( $default_template_path ) );
        //if ( ! $template_path ) {
            $template_path = dirname(__DIR__) . '/modules/' . $default_template_path;
        //}
        $template_path = apply_filters('uiform_rocket_template_path', $template_path);

        if ( is_file($template_path)) {
            extract($variables);
            ob_start();

            if ( 'always' == $require) {
                require $template_path;
            } else {
                require_once $template_path;
            }

            $template_content = apply_filters('uiform_rocket_template_content', ob_get_clean(), $default_template_path, $template_path, $variables);
        } else {
            $template_content = '';
        }

        return $template_content;
    }

    /**
     * Render a layout
     *
     * Allows parent/child themes to override the markup by placing the a file named basename( $default_template_path ) in their root folder,
     * and also allows plugins or themes to override the markup by a filter. Themes might prefer that method if they place their templates
     * in sub-directories to avoid cluttering the root folder. In both cases, the theme/plugin will have access to the variables so they can
     * fully customize the output.
     *
     * @mvc @model
     *
     * @param  string $default_template_path The path to the template, relative to the plugin's `views` folder
     * @param  array  $variables             An array of variables to pass into the template's scope, indexed with the variable name so that it can be extract()-ed
     * @param  string $require               'once' to use require_once() | 'always' to use require()
     * @return string
     */
    protected static function render_layout($default_template_path = false, $variables = array(), $require = 'once')
    {
        //$template_path = locate_template( basename( $default_template_path ) );
        //if ( ! $template_path ) {
            $template_path = dirname(__DIR__) . '/views/' . $default_template_path;
        //}
        $template_path = apply_filters('uiform_rocket_template_path', $template_path);

        if ( is_file($template_path)) {
            extract($variables);
            ob_start();

            if ( 'always' == $require) {
                require $template_path;
            } else {
                require_once $template_path;
            }

            $template_content = apply_filters('uiform_rocket_template_content', ob_get_clean(), $default_template_path, $template_path, $variables);
        } else {
            $template_content = '';
        }

        return $template_content;
    }

    /**
     * Load partial
     *
     * @param string  $template  template
     * @param string  $view      view
     * @param string  $view_data view_data
     * @param boolean $return    return
     *
     * @return    array
     */
    protected static function loadPartial($template = '', $view = '', $view_data = array(), $return = false)
    {
        $data            = array();
        $data['content'] = self::render_template($view, $view_data);
        // $this->set('content', $this->template_data['controller']->load->view($view, $view_data, true));
        // return $this->template_data['controller']->load->view($template, $this->template_data, $return);
        echo self::render_layout($template, $data);
    }


    /**
     * Load partial 2
     *
     * @param string  $template  template
     * @param string  $view      view
     * @param string  $view_data view_data
     * @param boolean $return    return
     *
     * @return  array
     */
    protected static function loadPartial2($template = '', $content = '', $return = false)
    {
        $data            = array();
        $data['content'] = $content;
        // $this->set('content', $this->template_data['controller']->load->view($view, $view_data, true));
        // return $this->template_data['controller']->load->view($template, $this->template_data, $return);
        echo self::render_layout($template, $data);
    }

    /*
     * Abstract methods
     */

    /**
     * Constructor
     *
     * @mvc Controller
     */
    abstract protected function __construct();

    /**
     * Register callbacks for actions and filters
     *
     * @mvc Controller
     */
    abstract public function register_hook_callbacks();

    /**
     * Initializes variables
     *
     * @mvc Controller
     */
    abstract public function init();

    /**
     * Checks if the plugin was recently updated and upgrades if necessary
     *
     * @mvc Controller
     *
     * @param string $db_version
     */
    abstract public function upgrade($db_version = 0);

    /**
     * Checks that the object is in a correct state
     *
     * @mvc Model
     *
     * @param string $property An individual property to check, or 'all' to check all of them
     * @return bool
     */
    abstract protected function is_valid($property = 'all');
}
