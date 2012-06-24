<?php
/**
 * This file contains the Google class
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * PHP version 5.3
 * 
 * @category Scraper
 * @package  Php-web-scraper
 * @author   colinschmidt <colinjschmidt@gmail.com>
 * @license  Apache 2.0 http://www.apache.org/licenses/LICENSE-2.0
 * @link     https://github.com/colinjschmidt/php-web-scraper
 * @filesource
 */

// require base ScraperSearch class
require_once __DIR__ . '/ScraperSearch.php';

/**
 * Google Class
 * 
 * This class contains the functionality scraping google.com search results. 
 * 
 * @category  Scraper
 * @package   Php-web-scraper
 * @author    colinschmidt <colinjschmidt@gmail.com>
 * @copyright 2012 Colin Schmidt
 * @license   Apache 2.0 http://www.apache.org/licenses/LICENSE-2.0
 * @version   Release: @package_version@
 * @link      https://github.com/colinjschmidt/php-web-scraper
 * @abstract
 */
class Google extends ScraperSearch
{
    /**
     * The base url
     * 
     * @var string
     */
    protected $url = 'http://www.google.com';
    
    /**
     * getUrl Method
     * 
     * Returns the URL to be scraped
     * 
     * @return string The url to scrape
     */
    protected function getUrl()
    {
        global $argv;
        
        if (!is_array($argv) || empty($argv[1])) {
            die('You must pass a search term as the first argument');
        }
        
        // Get the search term from the command line argument
        $searchTerm = $argv[1];
        
        // construct the url for google
        $url = $this->url
             . '/search?query='
             . $searchTerm;
        
        return $url;
    }
    
    /**
     * getNextPageUrl Method
     *
     * Returns the URL of the next page of items to be scraped
     * 
     * @param simple_html_dom $html The HTML to retrieve the next page URL
     *
     * @return string|null The next page url to scrape or null
     */
    public function getNextPageUrl(simple_html_dom $html)
    {
        $a = $html->find('a#pnnext', 0);
        
        if (empty($a)) {
            return null;
        }
        
        return $this->url . html_entity_decode($a->getAttribute('href'));
    }
    
    /**
     * getItems Method
     *
     * Returns an array of simple_html_dom_node objects
     *
     * @param simple_html_dom $html The HTML to retrieve the next page URL
     *
     * @return array|null An array of simple_html_dom_node objects or null
     */
    public function getItems(simple_html_dom $html)
    {
        $items = $html->find('li.g');
    
        if (empty($items)) {
            return null;
        }
    
        return $items;
    }
    
    /**
     * getItemUrl Method
     *
     * @param simple_html_dom_node $item The simple_html_dom_node for a single item
     *
     * @return string|null The url for the search result or null
     */
    public function getItemUrl(simple_html_dom_node $item)
    {
        $cite = $item->find('cite', 0);
        
        if (empty($cite)) {
            return null;
        }
        
        return $cite->text();
    }
    
    /**
     * getItemTitle Method
     *
     * @param simple_html_dom_node $item The simple_html_dom_node for a single item
     *
     * @return string|null The title of the search result or null
     */
    public function getItemTitle(simple_html_dom_node $item)
    {
        $a = $item->find('h3 a', 0);
        
        if (empty($a)) {
            return null;
        }
        
        return $a->text();
    }
    
    /**
     * getItemDescription Method
     *
     * @param simple_html_dom_node $item The simple_html_dom_node for a single item
     * 
     * @return string|null The title of the search result or null
     */
    public function getItemDescription(simple_html_dom_node $item)
    {
        $span = $item->find('span.st', 0);
        
        if (empty($span)) {
            return null;
        }
        
        return $span->text();
    }
}