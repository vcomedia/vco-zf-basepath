<?php
/**
 * VcoZfMediaPath - Zend Framework 2 basePath view helper wrapper.
 *
 * @category Module
 * @package  VcoZfMediaPath
 * @author   Vahag Dudukgian (valeeum)
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     http://github.com/vcomedia/vco-zf-mediapath/
 */

namespace VcoZfMediaPath\View\Helper;

use Zend\View\Helper\BasePath;
use Zend\View\Exception\RuntimeException;
use Zend\View\Helper\AbstractHelper;

/**
 * Class BasePath
 *
 * @package VcoZfMediaPath\View\Helper
 */
class MediaPath extends AbstractHelper {
    
    private $config;
    
    
    public function setConfig($config) {
        $this->config = $config;
    }
    
    /**
     * Returns site's base path, or file with base path prepended.
     *
     * $file is appended to the base path for simplicity.
     *
     * @param  string|null $file
     * @throws Exception\RuntimeException
     * @return string
     */
    public function __invoke($file = null, $basePathWrapper = true)
    {
        $basePath = $basePathWrapper ? $this->view->basePath($file) : '/' . ltrim($file, '/');
        $basePathBase = $basePathWrapper ? rtrim(substr($basePath, 0, -1 * strlen($file)), '/') : '';
        
        //cache buster logic
        $cacheBasePathMatch = false;
        foreach($this->config['cacheBusterOptions']['basePaths'] as $cacheBasePath) {
            $cacheBasePath = '/' . trim($cacheBasePath, '/');
            $cacheBasePath = $cacheBasePath === '/' ? $cacheBasePath : $cacheBasePath . '/';
            $cacheBasePath = $basePathBase . $cacheBasePath;
            if(strrpos($basePath, $cacheBasePath, -strlen($basePath)) !== false) {
               $cacheBasePathMatch = true;
            }
        }
        
        $fileDiskPath = getcwd() . '/' . trim($this->config['docRoot'], '/') . $basePath;
        $basePathInfo = pathinfo($basePath);
        if($this->config['cacheBusterOptions']['enabled'] === true && $cacheBasePathMatch && !empty($basePathInfo['extension']) && !empty($basePathInfo['filename']) && !empty($basePathInfo['basename']) 
            && in_array($basePathInfo['extension'], $this->config['cacheBusterOptions']['extensions']) && $fileModificationTime = filemtime($fileDiskPath)
          ){
            $final = array(
                $basePathInfo['filename'],
                $fileModificationTime,
                $basePathInfo['extension'],
            );
            $basePath = str_replace($basePathInfo['basename'], implode('.', $final), $basePath);
        }
        
        //cdn logic
        if($this->config['cdnOptions']['enabled'] === true) {
            $httpsCdn = rtrim($this->config['cdnOptions']['defaultDomain']['https'], '/'); 
            $httpCdn = rtrim($this->config['cdnOptions']['defaultDomain']['http'], '/'); 
            
            if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' && !empty($httpsCdn)) {
                return $httpsCdn . $basePath;  
            } else if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' && empty($this->config['cdnOptions']['defaultDomain']['https'])) {
                return $basePath;  
            } else {
                return $httpCdn . $basePath;    
            }
        } else {
            return $basePath;   
        }
    }
    
}