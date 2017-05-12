<?php

namespace App\Observers;

use App\Post;

class PostObserver
{
    private function cleanText($text, $convertEntities = true) {
        // regex strings for possible 'Read More' snippets
        $read_more = implode('|', [
            'read more[\.]*',
            '\[.+\]$',
        ]);

        // remove JavaScript tags
        $text = preg_replace('/\<script.*\>.*\<\/script\>/', '', $text);

        // remove HTML tags
        $text = preg_replace('/\<[\/]?[^\<\>]+\>/', '', $text);

        // remove 'Read more' text
        $text = preg_replace('/(' . $read_more . ')/i', '', $text);

        // clean html entities (after removing unwanted HTML tags)
        if ($convertEntities) {
            $text = htmlentities($text);
        }

        // replace some HTML entities with their browser-friendly counterparts
        $text = preg_replace('/&nbsp;/', ' ', $text);

        // convert HTML entities back to characters (this normalizes incorrect entities)
        if ($convertEntities) {
            $text = htmlspecialchars_decode($text);
        }

        return $text;
    }

    /**
    * Clean incoming Post data when during saves (updates and creates).
    *
    * @param App\Post $post
    */
    public function saving(Post $post)
    {
        $post->title = $this->cleanText($post->title);
        $post->description = $this->cleanText($post->description);
    }
}
