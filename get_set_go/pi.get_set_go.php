<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package   ExpressionEngine
 * @author    ExpressionEngine Dev Team
 * @copyright Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license   http://expressionengine.com/user_guide/license.html
 * @link      http://expressionengine.com
 * @since     Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Get! Set! Go! Plugin
 *
 * @package     ExpressionEngine
 * @subpackage  Addons
 * @category    Plugin
 * @author      Tom Davies
 * @link        https://github.com/tomdavies/getsetgo.ee_addon
 * @license     http://opensource.org/licenses/MIT Released under the MIT license
 *
 * Copyright (c) 2013 Tom Davies
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions 
 * of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED 
 * TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
 * DEALINGS IN THE SOFTWARE.
 * 
 */

$plugin_info = array(
  'pi_name'   => 'Get! Set! Go!',
  'pi_version'  => '0.9b',
  'pi_author'   => 'Tom Davies',
  'pi_author_url' => 'http://github.com/tomdavies',
  'pi_description'=> 'Get! Set! Go!',
  'pi_usage'    => Get_set_go::usage()
);


class Get_set_go {

  public $return_data;
    
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->format = 'query_string';
    $this->values = $_GET;

    $this->EE =& get_instance();

    //get all the tag params 
    $this->params = $this->EE->TMPL->tagparams;
    
    //get default values
    $this->values = $this->setInputSource();

    $this->setFormat();
    $this->unsetKeys();

    foreach ($this->params as $key => $value) {
      $this->values[$key] = $value;
    }

    $this->buildOutput();
  }

  //Are we using $_GET, $_POST, or both?
  private function setInputSource(){
    $source = $this->EE->TMPL->fetch_param('use');
    unset($this->params['use']);
    switch ($this->EE->TMPL->fetch_param('use')) {
      case 'both':
        return array_merge($_GET, $_POST);
        break;
      case 'post':
        return $_POST; 
        break;
      case 'get':
      default:
        return $_GET; 
        break;
    }
  }

  //remove anything the tag has asked us to
  private function unsetKeys(){
    $unsetKeys = array();

    if(isset($this->params['unset'])){
      $unsetKeys = explode('|', $this->EE->TMPL->fetch_param('unset'));
      unset($this->params['unset']);
    }
    //unset values
    foreach ($unsetKeys as $key) {
      if(isset($this->values[$key])){
        unset($this->values[$key]);
      }
    }
  }

  //set output format
  private function setFormat(){
    if (isset($this->params['format'])){
      $this->format = $this->params['format'];
      unset($this->params['format']);
    }
  }

  //build the output string
  private function buildOutput(){
    $this->EE->load->helper('url');
    switch ($this->format) {
      //full url eg http://example.com/group/template?foo=bar
      case 'url':
        $this->return_data = current_url();
        $this->return_data .= '?';
        break;
      //absolute, site relative path eg /group/template?foo=bar
      case 'uri':
        $this->return_data = '/';
        $this->return_data .= $this->EE->uri->uri_string();
        $this->return_data .= '?';
        break;
      //page relative uri (no trailing slash) eg group/template?foo=bar
      case 'relative_uri':
        $this->return_data = $this->EE->uri->uri_string();
        $this->return_data .= '?';
        break;
      //query string without trailing "?"eg foo=bar
      case 'string':
        break;
      //query string with trailing "?" eg ?foo=bar
      case 'query_string':
      default:
        $this->return_data = '?';
        break;
    }
    $this->return_data .= http_build_query($this->values); 
  }
  // ----------------------------------------------------------------
  
  /**
   * Plugin Usage
   */
  public static function usage()
  {
    ob_start();
?>

What?
=====

Get! Set! Go! makes manipulating/outputting query strings in your templates easier.

How?
====

If the query string on page load is this:

    http://example.com/group/template/search?category=fruit&order=product_price+asc&count=10

A GSG tag like this:

    {exp:get_set_go unset="category" count="20" }

Would produce the following output

    /group/template/search?order=product_price+asc&count=20    

Why?
====

Search addons like Solspace Super Search can make use of query strings/GET params for bookmarkable/indexable results pages, but outputting html links to allow the user to modify the current query string can be an ugly, messy business.

GSG makes it easy to output paths in your templates that derive from the current query string (or POST array, translated to GET format) but differ in arbitrary ways. Set new parameters and unset existing ones at will, and GSG will output consistent paths in your templates to your requirements.

Example use cases include "results per-page" links, search result filters etc - basically anywhere that you want to manipulate the current page's query string and output it back to your template.

Parameters
==========

unset="foo|bar|baz": An optional, bar (|) delimited list of keys to remove from the generated query string.

use="get|post|both": Should the starting query string be built from $_GET, $_POST, or the merged contents of the two. Defaults to "get".

format="url|uri|relative_uri|query_string|string" : What format should be used for the output? Select from (full) URL, site relative URI, page relative URI, query string (with trailing "?"), or string (just the url encoded .string, no trailing "?"). Defaults to "query_string"

Requirements
============

EE 2.5.x (2.5.3 & 2.5.5 tested)

<?php
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
  }
}


/* End of file pi.get_set_go.php */
/* Location: /system/expressionengine/third_party/get_set_go/pi.get_set_go.php */