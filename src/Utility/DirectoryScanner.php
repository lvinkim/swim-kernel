<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 23/09/2018
 * Time: 7:10 AM
 */

namespace Lvinkim\SwimKernel\Utility;


use HaydenPierce\ClassFinder\ClassFinder;

class DirectoryScanner
{

    /**
     * @param $directory
     * @param $namespace
     * @return array
     */
    public static function getClassesRecursion($directory, $namespace)
    {
        try {
            $classes = ClassFinder::getClassesInNamespace($namespace);

            $subDirectories = DirectoryScanner::scanChildNamespaces($directory);
            foreach ($subDirectories as $subDirectory) {
                $subClasses = ClassFinder::getClassesInNamespace($namespace . $subDirectory);
                $classes = array_merge($classes, $subClasses);
            }
        } catch (\Exception $exception) {
            $classes = [];
        }

        return $classes;
    }

    /**
     * 获取目录下的所有子命名空间
     * @param $directory
     * @param string $root
     * @return array
     */
    public static function scanChildNamespaces($directory, $root = "")
    {
        $allChildDirectories = [];
        if (is_dir($directory)) {

            $childFiles = scandir($directory);

            foreach ($childFiles as $childFile) {
                if ($childFile != '.' && $childFile != '..') {
                    $childDirectoryFullPath = $directory . DIRECTORY_SEPARATOR . $childFile;
                    if (is_dir($childDirectoryFullPath)) {

                        $childDirectory = $root . "\\" . $childFile;
                        $allChildDirectories[] = $childDirectory;

                        $childDirectories = self::scanChildNamespaces($childDirectoryFullPath, $childDirectory);
                        $allChildDirectories = array_merge($allChildDirectories, $childDirectories);

                    }
                }
            }
        }

        return $allChildDirectories;
    }

}