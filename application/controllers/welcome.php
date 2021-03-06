<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @package    three20
 * @author     Jeff Verkoeyen
 * @copyright  (c) 2010 Jeff Verkoeyen
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
class Welcome_Controller extends Three20_Controller {

  const ALLOW_PRODUCTION = TRUE;

  public function index() {
    if (!IN_PRODUCTION) {
      $profiler = new Profiler;
    }
    
    $content = new View('welcome_content');

    $this->add_css_file('css/main.css');
    $this->add_js_foot_file('http://www.google.com/jsapi?key='.Kohana::config('core.gfeeds_api_key'));
    $this->add_js_foot_file('js/blogfeed.js');

    $this->render_markdown_template($content);
  }

} // End Welcome Controller