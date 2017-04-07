<?php

namespace App\Http\Controllers;

use App\Feed;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    public function load(Request $request)
    {
        $feeds = Feed::where(['user_id' => $request->input('user_id')])->get();
        $posts = [];

        foreach ($feeds as $feed) {
            // load the latest posts for that feed and parse the XML
            $xml = file_get_contents($feed->url);
            $parser = xml_parser_create();

            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
            xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
            xml_parse_into_struct($parser, $xml, $values, $tags);
            xml_parser_free($parser);

            foreach ($tags as $key => $val) {
                if ($key === 'item') {
                    $range = $val;

                    for ($i = 0; $i < count($range); $i += 2) {
                        $offset = $range[$i] + 1;
                        $len = $range[$i + 1] - $offset;
                        $arr = array_slice($values, $offset, $len);

                        $post = [
                            'feed_id' => $feed->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];

                        for ($j = 0; $j < count($arr); $j++) {
                            if ($arr[$j]['tag'] === 'link') {
                                $post['url'] = $arr[$j]['value'];
                            } else if ($arr[$j]['tag'] === 'pubDate') {
                                $post['date_published'] = $arr[$j]['value'];
                            } else {
                                $post[$arr[$j]['tag']] = $arr[$j]['value'];
                            }
                        }

                        $posts[] = $post;

                        Post::updateOrCreate(['feed_id' => $feed->id, 'url' => $post['url']], $post);
                    }
                }
            }
        }

        return redirect()->back();
    }
}
