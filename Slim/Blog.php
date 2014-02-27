<?php
/**
 * Created by PhpStorm.
 * User: dolgov
 * Date: 03.02.14
 * Time: 16:03
 */

namespace Slim;


class Blog {

    public function getList($path)
    {
        $dir = new \DirectoryIterator($path);
        $articles = array();
        foreach ($dir as $file) {
            if ($file->isFile()) {

                $handle = fopen($path . '/' . $file->getFilename(), 'r');
                $content = stream_get_contents($handle);
                $content = explode("\n\n", $content);
                $rawMeta = array_shift($content);
                $meta = json_decode($rawMeta, true);

                $content = implode("\n\n", $content);

                $content =  substr(strip_tags($content), 0, 200);

                $articles[$file->getFilename()] = array('meta' => $meta, 'content' => $content);

            }
        }

        return $articles;
    }
} 