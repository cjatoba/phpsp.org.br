<?php

namespace Phpsp\Site\EventHandlers\AfterBuild;

use Carbon\Carbon;
use DOMDocument;
use TightenCo\Jigsaw\Jigsaw;
use PODEntender\SitemapGenerator\Adapter\Jigsaw\JigsawAdapter;
use Phpsp\Site\EventHandlers\JigsawHandlerInterface;
use Phpsp\Site\RssFeedGenerator;

class GenerateRssFeed implements JigsawHandlerInterface
{
    public function handle(Jigsaw $jigsaw): void
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $rss = $dom->appendChild($dom->createElement('rss'));
        $channel = $rss->appendChild($dom->createElement('channel'));
        foreach ($jigsaw->getCollection('posts') as $post) {
            $element = $dom->createElement('item');
            $element->appendChild($dom->createElement('title', $post->title));
            $element->appendChild($dom->createElement('autor', $post->author));
            $element->appendChild($dom->createElement('autor-email', $post->authorEmail));
            $element->appendChild($dom->createElement('publicado-em', Carbon::createFromTimestamp($post->createdAt)->format('d-m-Y')));
            $channel->appendChild($element);
        }
        $dom->formatOutput = true;
        
        file_put_contents('source/feed.xml', $dom->saveXML());
    }
}
