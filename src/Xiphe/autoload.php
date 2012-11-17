<?php
/**
 * Autoload File for Xiphe
 *
 * @category autoload
 * @package  Xiphe
 * @author   Hannes Diercks <xiphe@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE
 * @link     https://github.com/Xiphe/
 */

spl_autoload_register(
    function ($class) {
        if (strpos($class, 'Xiphe\\') === 0) {
            $path = explode('\\', $class);
            $name = end($path);
            $path = array_splice($path, 1, -1);
            $path[] = $name.'.php';
            $path = implode(DIRECTORY_SEPARATOR, $path);
            $file = dirname(__FILE__).DIRECTORY_SEPARATOR.$path;
            if (file_exists($file)) {
	            include $file;
            }
        }
    }
);