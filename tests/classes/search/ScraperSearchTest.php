<?php
/**
 * This file contains the Unit Tests for the ScraperSearch Class
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
 * @category Tests
 * @package  Php-web-scraper
 * @author   colinschmidt <colinjschmidt@gmail.com>
 * @license  Apache 2.0 http://www.apache.org/licenses/LICENSE-2.0
 * @link     https://github.com/colinjschmidt/php-web-scraper
 * @filesource
 */

// Require the class that is being tested
require_once __DIR__ . '/../../../classes/search/ScraperSearch.php';

/**
 * ScraperSearch Test Class
 * 
 * @category Tests
 * @package  Php-web-scraper
 * @author   colinschmidt <colinjschmidt@gmail.com>
 * @license  Apache 2.0 http://www.apache.org/licenses/LICENSE-2.0
 * @link     https://github.com/colinjschmidt/php-web-scraper
 */
class ScraperSearchTest extends PHPUnit_Framework_TestCase
{
    /**
     * An instance of the ScraperSearch Class
     * 
     * @var ScraperSearch
     */
    protected $scraper;
    
    /**
     * Setup function before each test
     * 
     * @return void
     */
    public function setup()
    {
        // Use PHP's reflection API to interact with mock Scraper Class
        $this->scraper = new ReflectionClass('ScraperSearch');
    }
    
    /**
     * Tests ScraperSearch::getItems
     * 
     * @return void
     */
    public function testGetItems()
    {
        $scraper = new ScraperSearch();
        
        $method = $this->scraper->getMethod('getItems');
        $method->setAccessible(true);
        
        $items = $method->invokeArgs($scraper, array(new simple_html_dom(null)));
        
        $this->assertInternalType('array', $items);
    }
    
    /**
     * Tests ScraperSearch::getItemData
     *
     * @return void
     */
    public function testGetItemData()
    {
        $scraper = new ScraperSearch();
    
        $method = $this->scraper->getMethod('getItemData');
        $method->setAccessible(true);
    
        $itemData = $method->invokeArgs($scraper, array(new simple_html_dom_node(null)));
        
        $expected = array(
            'url' => null,
            'title' => null,
            'description' => null        
        );
         
        $this->assertEquals($expected, $itemData);
    }
    
    /**
     * Tests ScraperSearch::getNextPageUrl
     *
     * @return void
     */
    public function testGetNextPageUrl()
    {
        $scraper = new ScraperSearch();
    
        $method = $this->scraper->getMethod('getNextPageUrl');
        $method->setAccessible(true);
    
        $nextPageUrl = $method->invokeArgs($scraper, array(new simple_html_dom(null)));
    
        $this->assertEquals(null, $nextPageUrl);
    }
    
    /**
     * Test ScraperSearch::getItemUrl
     * 
     * @return void
     */
    public function testGetItemUrl()
    {
        $scraper = new ScraperSearch();
        
        $method = $this->scraper->getMethod('getItemUrl');
        $method->setAccessible(true);
        
        $response = $method->invokeArgs($scraper, array(new simple_html_dom_node(null)));
        
        $this->assertEquals(null, $response);
    }
    
    /**
     * Test ScraperSearch::getItemTitle
     *
     * @return void
     */
    public function testGetItemTitle()
    {
        $scraper = new ScraperSearch();
    
        $method = $this->scraper->getMethod('getItemTitle');
        $method->setAccessible(true);
    
        $response = $method->invokeArgs($scraper, array(new simple_html_dom_node(null)));
    
        $this->assertEquals(null, $response);
    }
    
    /**
     * Test ScraperSearch::getItemDescription
     *
     * @return void
     */
    public function testGetItemDescription()
    {
        $scraper = new ScraperSearch();
    
        $method = $this->scraper->getMethod('getItemDescription');
        $method->setAccessible(true);
    
        $response = $method->invokeArgs($scraper, array(new simple_html_dom_node(null)));
    
        $this->assertEquals(null, $response);
    }
    
    /**
     * Tests ScraperSearch::factory
     * 
     * @return void
     */
    public function testFactory()
    {
        // Positive test
        $scraper = ScraperSearch::factory('scraperSearch');
        $this->assertInstanceOf('ScraperSearch', $scraper);
        
        // Negative test, file not found
        $scraper = ScraperSearch::factory('test');
        $this->assertEquals(null, $scraper);
        
        // Negative test case - file exists, but not class
        fopen(__DIR__ . '/../../../classes/search/Test.php', 'w');
        $scraper = ScraperSearch::factory('test');
        $this->assertEquals(null, $scraper);
        unlink(__DIR__ . '/../../../classes/search/Test.php');
    }
}