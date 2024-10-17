<?php

namespace App\RSS;

use App\Models\News;
use Carbon\Carbon;
use SimplePie\SimplePie;
use Vedmant\FeedReader\Facades\FeedReader;

class SportsRSSParser implements ParserInterface
{
    protected $url = 'https://www.sports.ru/rss/all_news.xml';

    public function parse(): int
    {
        $feed = FeedReader::read($this->url);
        /* @var SimplePie $feed */

        $count = 0;

        foreach ($feed->get_items() as $item) {
            if (News::whereProvider('sports')->whereGuid($item->get_id())->exists()) {
                continue;
            }

            News::create([
                'guid' => $item->get_id(),
                'provider' => 'sports',
                'title' => $item->get_title(),
                'description' => $item->get_description(),
                'link' => $item->get_link(),
                'published_at' => Carbon::parse($item->get_date()),
            ]);

            $count++;
        }

        return $count;
    }
}
