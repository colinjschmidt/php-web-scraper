<?php
/**
 * This file contains the ScraperSearch class
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

// require base Scraper class
require_once __DIR__ . '/../Scraper.php';

/**
 * ScraperSearch Class
 * 
 * This class contains the base functionality for the web scraper. 
 * It should be extended for each project.
 * 
 * @category  Scraper
 * @package   Php-web-scraper
 * @author    colinschmidt <colinjschmidt@gmail.com>
 * @copyright 2012 Colin Schmidt
 * @license   Apache 2.0 http://www.apache.org/licenses/LICENSE-2.0
 * @version   Release: @package_version@
 * @link      https://github.com/colinjschmidt/php-web-scraper
 */
class ScraperSearch extends Scraper
{
    /**
     * getItems method
     * 
     * Important: override this method in child classes
     * 
     * @param simple_html_dom $html The simple_dom_html object for the scraped page
     * 
     * @return array an array of simple_dom_html_nodes for each object
     */
    protected function getItems(simple_html_dom $html)
    {
        return array();
    }
    
    /**
     * getNextPageUrl Method
     *
     * Returns the URL of the next page of items to be scraped
     * Important: override this method in child classes
     * 
     * @param simple_html_dom $html The HTML to retrieve the next page URL
     *
     * @return string|null The next page url to scrape or null
     */
    protected function getNextPageUrl(simple_html_dom $html)
    {
        return null;
    }
    
    /**
     * getItemData method
     * 
     * @param simple_html_dom_node $item The simple_html_dom_node for a single item
     * 
     * @return array An array of item data
     */
    protected function getItemData(simple_html_dom_node $item)
    {
        $data = array();
        
        $data['url'] = $this->getItemUrl($item);
        $data['title'] = $this->getItemTitle($item);
        $data['description'] = $this->getItemDescription($item);
        
        return $data;
    }
    
    /**
     * getItemUrl Method
     *
     * @param simple_html_dom_node $item The simple_html_dom_node for a single item
     *
     * @return string|null The url for the search result or null
     */
    protected function getItemUrl(simple_html_dom_node $item)
    {
        return null;
    }
    
    /**
     * getItemTitle Method
     *
     * @param simple_html_dom_node $item The simple_html_dom_node for a single item
     * 
     * @return string|null The title of the search result or null
     */
    protected function getItemTitle(simple_html_dom_node $item)
    {
        return null;
    }
    
    /**
     * getItemDescription Method
     *
     * @param simple_html_dom_node $item The simple_html_dom_node for a single item
     * 
     * @return string|null The title of the search result or null
     */
    protected function getItemDescription(simple_html_dom_node $item)
    {
        return null;
    }
    
    /**
     * factory method
     * 
     * Returns an instance of the scraper class based on the passed in $name
     * 
     * @param string $name The name of the scraper to initialize
     * 
     * @final
     * @return Scraper|null an instance of the Scraper class
     */
    final static public function factory($name)
    {
        $className = ucfirst($name);
        $fileName = $className . '.php';
        $file = __DIR__ . '/' . $fileName;
        
        // Make sure that the file exists
        if (!file_exists($file)) {
            return null;
        }
        
        // Require the file
        include_once $file;
        
        // Make sure that the class exists
        if (!class_exists($className)) {
            return null;
        }
        // return an instance of the class
        return new $className(); 
    }
}