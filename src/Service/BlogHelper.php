<?php
namespace App\Service;

use Doctrine\Common\Collections\Collection;

class BlogHelper
{
    public function renderBlogWithImages(
        string $content,
        Collection $images,
        string $galleryId = 'blog-gallery'
    ): string {
        $galleryId = htmlspecialchars($galleryId, ENT_QUOTES, 'UTF-8');

        return preg_replace_callback(
            '/\{\{img:(\d+)\}\}/',
            function ($matches) use ($images, $galleryId) {

                $index = (int) $matches[1] - 1;
                $image = $images[$index] ?? null;

                if ($image) {
                    $src = '/uploads/' . $image->getFilePath();
                    $escapedSrc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8');

                    return '<a href="' . $escapedSrc . '"
                                class="blog-lightbox"
                                data-gallery="' . $galleryId . '">
                                <img src="' . $escapedSrc . '"
                                    alt="Obrazek"
                                    class="img-fluid blog-image">
                            </a>';
                }

                return '';
            },
            nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'))
        );
    }
}
