<?php
/**
 * This file contains the abstract Scraper class
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

// Set the include path for the project
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../library');

// require simple_html_dom library
require_once 'simple_html_dom/simple_html_dom.php';

/**
 * Scraper Class
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
 * @abstract
 */
abstract class Scraper
{
    /**
     * The folder to write the scraper results to relative to project root
     *
     * @var string
     */
    protected $resultsFolder = 'results';
    
    /**
     * The Base URL to scrape
     * 
     * @var string
     */
    protected $url;
    
    /**
     * The number of results desired
     * 
     * @var int
     */
    protected $size = 100;
    
    /**
     * The number of results obtained
     *
     * @var int
     */
    protected $count = 0;
    
    /**
     * getResultsFolder Method
     *
     * Returns the path to the folder where scraper results will be stored
     *
     * @param string $folder The name of the folder to store the scraped results 
     * 
     * @return string The absolute path to the results folder
     */
    protected function getResultsFolder($folder)
    {
        return __DIR__ . '/../' . $folder;
    }
    
    /**
     * getFileName Method
     *
     * Returns the name of the file where scraper results will be stored
     *
     * @return string The name of the file
     */
    protected function getFileName()
    {
        $fileName = get_called_class() . '-' . date('Y-m-d-h', time()) . '.txt';
        
        return $fileName;
    }
    
    /**
     * getUrl Method
     * 
     * Returns the URL to be scraped
     * 
     * @return string The url to scrape
     */
    protected function getUrl()
    {   
        return null;
    }
    
    /**
     * getUrl Method
     *
     * Returns the simple_html_dom object for the URL
     * 
     * @param string $url The URL to be scraped
     * 
     * @return Simple_html_dom The simple_html_dom object
     */
    protected function getHtml($url)
    {
        return file_get_html($url);
    }
    
    /**
     * getNextPageUrl Method
     *
     * Returns the URL of the next page of items to be scraped
     *
     * @param simple_html_dom $html The HTML to retrieve the next page URL
     *
     * @return string|NULL The next page url to scrape or NULL
     */
    abstract protected function getNextPageUrl(simple_html_dom $html);
    
    /**
     * getItems Method
     *
     * Returns an array of simple_html_dom_node objects for each item
     *
     * @param simple_html_dom $html The HTML to retrieve the next page URL
     *
     * @return array|NULL An array of items or NULL
     */
    abstract protected function getItems(simple_html_dom $html);
    
    /**
     * getItemData Method
     *
     * Returns an array of data for an item
     *
     * @param Simple_html_dom_node $item simple_html_dom_node object for an item
     *
     * @return array|NULL An array of data for an item or NULL
     */
    abstract protected function getItemData(simple_html_dom_node $item);
    
    /**
     * formatData Method
     * 
     * Returns a formatted string of data for an item
     * 
     * @param array $data An array of data for an item
     * 
     * @return string The formatted string of data to be appended to a file
     */
    protected function formatData($data)
    {
        return json_encode($data);
    }
    
    /**
     * appendData Method
     *
     * Appends string of data for an item to file
     *
     * @param string $file The full path to the file
     * @param string $str  A string of data for an item
     *
     * @return bool True on success, False on failure
     */
    protected function appendData($file, $str)
    {   
        $handle = fopen($file, 'a');
        fwrite($handle, $str);
        fclose($handle);
        
        return true;
    }
    
    /**
     * Run Method
     * 
     * Executes the steps required to scrape a websites content (recursively)
     * 
     * @param mixed $url The url of the page to scrape or null
     * 
     * @return bool
     */
    public function run($url = null)
    {
        // If no url was passed in, construct the url from the member variables
        if (empty($url)) {
            $url = $this->getUrl();
        }
        
        // Get the simple_html_dom page 
        $html = $this->getHtml($url);
        
        // Get the items from the page html
        $items = $this->getItems($html);
        
        // Create an empty string to start saving data to
        $str = '';
        
        // Loop through all of the items,
        // formatting the data and appending to the string
        foreach ($items as $item) {
            
            // If we've obtained enough results, break
            if ($this->count >= $this->size) {
                break;
            }
            
            // Otherwise get the item data and append to string
            $itemData = $this->getItemData($item);
            $str .= $this->formatData($itemData);
            $str .= "\n";
            
            $this->count++;
        }
        
        // Construct the path to the file to save the results to
        $file = $this->getResultsFolder($this->resultsFolder) 
              . '/'
              . $this->getFileName();
        
        // Append formatted data to the file
        $appended = $this->appendData($file, $str);
        
        // Check if there is a next page to scrape
        $nextPage = $this->getNextPageUrl($html);
        
        // If there is a next page and the last page's data was saved,
        // scrape the next page
        if ($appended == true 
            && !empty($nextPage) 
            && $this->count < $this->size
        ) {
            $this->run($nextPage);
        }
    }
}