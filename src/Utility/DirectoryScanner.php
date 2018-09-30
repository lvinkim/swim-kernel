<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 23/09/2018
 * Time: 7:10 AM
 */

namespace Lvinkim\SwimKernel\Utility;


class DirectoryScanner
{
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