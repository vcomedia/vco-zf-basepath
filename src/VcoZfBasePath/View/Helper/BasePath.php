<?php
/**
 * VcoZfBasePath - Zend Framework 2 basePath view helper wrapper.
 *
 * @category Module
 * @package  VcoZfBasePath
 * @author   Vahag Dudukgian (valeeum)
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     http://github.com/vcomedia/vco-zf-minify/
 */

namespace VcoZfBasePath\View\Helper;

use Zend\View\Helper\BasePath as BasePathOriginal;

/**
 * Class BasePath
 *
 * @package VcoZfBasePath\View\Helper
 */
class BasePath extends BasePathOriginal {
    
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
    public function __invoke($file = null)
    {
        if (null === $this->basePath) {
            throw new Exception\RuntimeException('No base path provided');
        }

        if (null !== $file) {
            $file = '/' . ltrim($file, '/');
        }
        
        //cache buster logic
        $cacheBasePathMatch = false;
        foreach($this->config['cacheBusterOptions']['basePaths'] as $cacheBasePath) {
            $cacheBasePath = '/' . trim($cacheBasePath, '/');
            $cacheBasePath = $cacheBasePath === '/' ? $cacheBasePath : $cacheBasePath . '/';
            if(strrpos($file, $cacheBasePath, -strlen($file)) !== false) {
               $cacheBasePathMatch = true;
            }
        }
        
        $fileDiskPath = getcwd() . '/' . trim($this->config['docRoot'], '/') . $file;
        $filePathInfo = pathinfo($file);
        if($this->config['cacheBusterOptions']['enabled'] === true && $cacheBasePathMatch && !empty($filePathInfo['extension']) && !empty($filePathInfo['filename']) && !empty($filePathInfo['basename']) 
            && in_array($filePathInfo['extension'], $this->config['cacheBusterOptions']['extensions']) && $fileModificationTime = filemtime($fileDiskPath)
          ){
            $final = array(
                $filePathInfo['filename'],
                $fileModificationTime,
                $filePathInfo['extension'],
            );
            $file = str_replace($filePathInfo['basename'], implode('.', $final), $file);
        }
        
        //cdn logic
        if($this->config['cdnOptions']['enabled'] === true) {
            return $this->config['cdnOptions']['defaultDomain']['http'] . $this->basePath . $file;  
        } else {
            return $this->basePath . $file;   
        }
    }
    
}
