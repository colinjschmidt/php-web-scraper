<?php
/**
 * This file contains the Unit Tests for the Scraper Class
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
 * @link     https://github.com/colinschmidt/php-web-scraper
 * @filesource
 */

require_once 'vfsStream/vfsStream.php';
require_once __DIR__ . '/../../classes/Scraper.php';

/**
 * Scraper Test Class
 * 
 * @category Tests
 * @package  Php-web-scraper
 * @author   colinschmidt <colinjschmidt@gmail.com>
 * @license  Apache 2.0 http://www.apache.org/licenses/LICENSE-2.0
 * @link     https://github.com/colinschmidt/php-web-scraper
 */
class ScraperTest extends PHPUnit_Framework_TestCase
{
    /**
     * An instance of the Scraper Class
     * 
     * @var Scraper
     */
    protected $scraper;
    
    /**
     * Setup function before each test
     * 
     * @return void
     */
    public function setup()
    {   
        // Get a Mock Object for the abstract Scraper class
        $scraper = $this->getMockForAbstractClass('Scraper');
        
        // Use PHP's reflection API to interact with mock Scraper Class
        $this->scraper = new ReflectionClass($scraper);
    }
    
    /**
     * Tests Scraper::getFileName
     * 
     * @return void
     */
    public function testGetFileName()
    {
        // Setup a mock object for Scraper class
        $mockController = $this->getMockForAbstractClass('Scraper');
        
        $method = $this->scraper->getMethod('getFileName');
        $method->setAccessible(true);
        
        $this->assertInternalType('string', $method->invoke($mockController));
    }
    
    /**
     * Tests Scraper::getUrl
     * 
     * @return void
     */
    public function testGetUrl()
    {
        $baseUrl = 'http://google.com/search';
        
        // Setup a mock object for Scraper class
        $mockController = $this->getMockForAbstractClass('Scraper');
        
        // Define URL property
        $urlProperty = $this->scraper->getProperty('url');
        $urlProperty->setAccessible(true);
        $urlProperty->setValue($mockController, 'http://google.com/search');
        
        // Get PageParam property value
        $pageParamProperty = $this->scraper->getProperty('pageParam');
        $pageParamProperty->setAccessible(true);
        $pageParam = $pageParamProperty->getValue($mockController);
        
        // Get itemsPerPageParam property value
        $itemsPerPageParamProperty = $this->scraper
            ->getProperty('itemsPerPageParam');
        $itemsPerPageParamProperty->setAccessible(true);
        $itemsPerPageParam = $itemsPerPageParamProperty
            ->getValue($mockController);
        
        // Get page property value
        $pageProperty = $this->scraper->getProperty('page');
        $pageProperty->setAccessible(true);
        $page = $pageProperty->getValue($mockController);
        
        // Get itemsPerPage property value
        $itemsPerPageProperty = $this->scraper->getProperty('itemsPerPage');
        $itemsPerPageProperty->setAccessible(true);
        $itemsPerPage = $itemsPerPageProperty->getValue($mockController);
        
        // Generate URL manually to compare with method output
        $url = "$baseUrl?$pageParam=$page&$itemsPerPageParam=$itemsPerPage";
        
        // Get the method and make it accessible
        $method = $this->scraper->getMethod('getUrl');
        $method->setAccessible(true);
        
        // Assert that the method responses matches the expected output
        $this->assertEquals($url, $method->invoke($mockController));
    }
    
    /**
     * Tests Scraper::getHtml
     * 
     * @covers Scraper::getHtml
     * @return void
     */
    public function testGetHtml()
    {
        // Setup a mock object for Scraper class
        $mockController = $this->getMockForAbstractClass('Scraper');
        
        $method = $this->scraper->getMethod('getHtml');
        $method->setAccessible(true);
        
        // Make sure that the response is a simple_html_dom object
        $this->assertInstanceOf(
            'Simple_html_dom',
            $method->invokeArgs($mockController, array('http://google.com'))
        );
        
        // Bad test, not a real url
        $this->assertFalse(
            $method->invokeArgs($mockController, array('adfhsdafkjhdsakl'))
        );
    }
    
    /**
     * Tests Scraper::formatData
     * 
     * @covers Scraper::formatData
     * @return void
     */
    public function testFormatData()
    {
        // Setup a mock object for Scraper class
        $mockController = $this->getMockForAbstractClass('Scraper');
    
        $method = $this->scraper->getMethod('formatData');
        $method->setAccessible(true);
    
        // Make sure that the response is a simple_html_dom object
        $this->assertInternalType(
            'string',
            $method->invokeArgs($mockController, array(array('test'=>1)))
        );
        
        // Make sure that method is json encoding the data
        $json = $method->invokeArgs($mockController, array(array('test'=>1)));
        $this->assertInternalType(
            'array',
            json_decode($json, true)
        );
        
        // what if a string is passed in
        $this->assertInternalType(
            'string',
            $method->invokeArgs($mockController, array('test'))
        );
        
        // what if null is passed in
        $this->assertInternalType(
            'string',
            $method->invokeArgs($mockController, array(null))
        );
    }
    
    /**
     * Tests Scraper::getResultsFolder
     *
     * @covers Scraper::getResultsFolder
     * @return void
     */
    public function testGetResultsFolder()
    {
        // Setup a mock object for Scraper class
        $mockController = $this->getMockForAbstractClass('Scraper');
        
        $method = $this->scraper->getMethod('getResultsFolder');
        $method->setAccessible(true);
        
        // Successful test, directory exists
        $directory = $method->invokeArgs(
            $mockController,
            array('results')
        );
        $this->assertTrue(is_dir($directory));
        
        // Non existent directory
        $directory = $method->invokeArgs(
            $mockController,
            array('test')
        );
        $this->assertFalse(is_dir($directory));
    }
    
    /**
     * Tests Scraper::appendData
     * 
     * @covers Scraper::appendData
     * @return void
     */
    public function testAppendData()
    {   
        // Setup a mock object for Scraper class
        $mockController = $this->getMockForAbstractClass('Scraper');
        
        $method = $this->scraper->getMethod('appendData');
        $method->setAccessible(true);
        
        $property = $this->scraper->getProperty('resultsFolder');
        $property->setAccessible(true);
        
        $fileName = 'test-' . date('Y-m-d-h', time()) . '.txt';
        
        $fullPath = __DIR__ . '/../../' 
            . $property->getValue($mockController)
            . '/'
            . $fileName;
        
        // Run the appendData method
        $method->invokeArgs(
            $mockController,
            array($fullPath, 'test')
        );
        
        // Assert that the file exists
        $this->assertTrue(file_exists($fullPath));
        
        // Check that the output equals the expected
        $this->assertEquals("test\n", file_get_contents($fullPath));       

        // Run the appendData method again
        $method->invokeArgs(
            $mockController,
            array($fullPath, 'test2')
        );
        
        // Check that the output equals the expected
        $this->assertEquals("test\ntest2\n", file_get_contents($fullPath));
        
        unlink($fullPath);
    }
}