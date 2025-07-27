<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TopicRepository;
use App\Repository\BlogRepository;

final class BlogController extends AbstractController
{
    #[Route('/{_locale}/blog-topics', name: 'blog_index')]
    public function index(string $_locale, TopicRepository $topicRepository): Response
    {
        $topics = $topicRepository->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'AdminController',
            'current_locale' => $_locale,
            'site' => 'blog-topics',
            'topics' => $topics,
        ]);
    }

    #[Route('/{_locale}/blog/{slug}', name: 'public_topic_show')]
    public function show(string $_locale, string $slug, TopicRepository $topicRepository, BlogRepository $blogRepository): Response
    {

        $topic = $topicRepository->findOneBy(['slug' => $slug]);

        if (!$topic) {
            throw $this->createNotFoundException('Temat nie istnieje.');
        }

        $blogs = $blogRepository->findBy(['topic' => $topic]);

        return $this->render('topic/public-show.html.twig', [
            'topic' => $topic,
            'current_locale' => $_locale,
            'site' => 'blog/' . $slug,
            'slug' => $slug,
            'blogs' => $blogs,
        ]);
    }
}
