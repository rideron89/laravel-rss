<?php

namespace App\Console\Commands;

use App\Feed;
use App\Post;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class LoadPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'load:posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load posts for groups of feeds';

    /**
     * Get the console command arguments.
     *
     * Array entry: [$name, $mode, $description, $defaultValue]
     *
     * @return array
     */
    protected function getArguments()
    {

        return [
            ['ids', InputArgument::OPTIONAL, 'List of comma-separated IDs to load posts for'],
        ];
    }

    /**
     * Get the console command options.
     *
     * Array entry: [$name, $shortcut, $mode, $description, $defaultValue]
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', null, InputOption::VALUE_NONE, 'Load posts for all feeds (this operation might take a while)'],
        ];
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ids = explode(',', $this->argument('ids'));
        $do_all = $this->option('all');

        if ($do_all) {
            $this->loadPosts($ids, true);
        } else {
            if (count($ids) < 1 || $ids[0] === '') {
                $this->call('help', ['command_name' => 'load:posts', 'format' => 'raw']);
            } else {
                $this->loadPosts($ids);
            }
        }
    }

    protected function loadPosts($ids, $all = false)
    {
        if ($all) {
            $feeds = Feed::all();

            $this->info('Loading posts for all ' . count($feeds) . ' feeds...');
        } else {
            $this->info('Loading posts for ' . count($ids) . ' feeds...');
            $feeds = Feed::whereIn('id', $ids)->get();
        }

        $start_count = Post::count();
        $posts = [];

        foreach ($feeds as $feed) {
            // load the latest posts for that feed and parse the XML
            $xml = file_get_contents($feed->url);
            $parser = xml_parser_create();

            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
            xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
            xml_parse_into_struct($parser, $xml, $values, $tags);
            xml_parser_free($parser);

            $count = 0;

            foreach ($tags as $key => $val) {
                if ($key === 'item') {
                    $range = $val;

                    for ($i = 0; $i < count($range); $i += 2) {
                        $offset = $range[$i] + 1;
                        $len = $range[$i + 1] - $offset;
                        $arr = array_slice($values, $offset, $len);

                        $post = [
                            'feed_id' => $feed->id,
                            'created_at' => time(),
                            'updated_at' => time(),
                        ];

                        for ($j = 0; $j < count($arr); $j++) {
                            if ($arr[$j]['tag'] === 'link') {
                                $post['url'] = $arr[$j]['value'];
                            } else if ($arr[$j]['tag'] === 'pubDate') {
                                $post['date_published'] = date('Y-m-d H:i:s', strtotime($arr[$j]['value']));
                            } else if ($arr[$j]['tag'] === 'description') {
                                if (strlen($arr[$j]['value']) > 255) {
                                    // return smaller version
                                    $post['description'] = substr($arr[$j]['value'], 0, 248) . '...';
                                } else {
                                    $post['description'] = $arr[$j]['value'];
                                }
                            } else {
                                if (isset($arr[$j]['value'])) {
                                    $post[$arr[$j]['tag']] = $arr[$j]['value'];
                                }
                            }
                        }

                        $posts[] = $post;
                        $count += 1;

                        Post::updateOrCreate(['feed_id' => $feed->id, 'url' => $post['url']], $post);
                    }
                }
            }

            $this->info('Fetched ' . (string)$count . ' posts for feed ' . (string)$feed->id . ' (' . $feed->url . ')');
        }

        $end_count = Post::count();

        $this->info('Added ' . (string)($end_count - $start_count) . ' new entries to the database.');
    }
}
