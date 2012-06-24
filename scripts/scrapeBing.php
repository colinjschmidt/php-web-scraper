<?php
/**
 * This file contains the google scraper script
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

// Require the Google Scraper Class
require_once __DIR__ . '/../classes/search/Bing.php';

// Run the google scraper
$scraper = new Bing();
$scraper->run();