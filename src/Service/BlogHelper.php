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
            '/\{\{img:([^}]+)\}\}/',
            function ($matches) use ($images, $galleryId) {

                $definition = trim($matches[1]);

                // Domyślna szerokość
                $imageWidth = 200;

                // Odczyt parametru size
                $definition = preg_replace_callback(
                    '/;\s*size\s*:\s*(\d+)/',
                    function ($sizeMatch) use (&$imageWidth) {
                        $imageWidth = (int) $sizeMatch[1];
                        return '';
                    },
                    $definition
                );

                // Ograniczenie rozmiaru
                $imageWidth = max(50, min(1000, $imageWidth));

                // Pobranie wszystkich numerów zdjęć
                preg_match_all('/(?:^|,)\s*(?:img:)?\s*(\d+)/', $definition, $numbers);

                $html = '<div class="blog-images">';

                foreach ($numbers[1] as $number) {

                    $index = (int) $number - 1;

                    $image = $images[$index] ?? null;

                    if (!$image) {
                        continue;
                    }

                    $src = '/uploads/' . $image->getFilePath();

                    $escapedSrc = htmlspecialchars(
                        $src,
                        ENT_QUOTES,
                        'UTF-8'
                    );

                    $html .= '<a href="' . $escapedSrc . '"
                        class="blog-lightbox"
                        data-gallery="' . $galleryId . '">
                        <img src="' . $escapedSrc . '"
                            alt="Obrazek"
                            class="img-fluid blog-image"
                            style="width: ' . $imageWidth . 'px;">
                    </a>';
                }

                $html .= '</div>';

                return $html;
            },
            nl2br(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'))
        );
    }
}
    