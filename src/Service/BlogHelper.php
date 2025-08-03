<?php
namespace App\Service;

use Doctrine\Common\Collections\Collection;

class BlogHelper
{
    public function renderBlogWithImages(string $content, Collection $images): string
    {
        return preg_replace_callback('/\{\{img:(\d+)\}\}/', function ($matches) use ($images) {
            $index = (int) $matches[1] - 1;
            $image = $images[$index] ?? null;
            if ($image) {
               return '<img src="/uploads/' . $image->getFilePath() . '" alt="Obrazek" class="img-fluid blog-image">';
            }
            return '';
        }, nl2br(htmlspecialchars($content)));
    }
}
