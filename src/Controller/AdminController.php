<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Topic;
use App\Form\TopicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TopicRepository;
use App\Repository\BlogRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Blog;
use App\Form\BlogType;



final class AdminController extends AbstractController
{
    #[Route('/{_locale}/admin', name: 'admin_index')]
    public function index(Request $request, EntityManagerInterface $entityManager, string $_locale, TopicRepository $topicRepository, SluggerInterface $slugger,): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($topic->getName())->lower();

            if ($topicRepository->findOneBy(['slug' => $slug])) {
                $this->addFlash('error', 'Temat o takiej nazwie już istnieje.');
                return $this->redirectToRoute('admin_index');
            }

            $topic->setSlug($slug);

            $entityManager->persist($topic);   
            $entityManager->flush();           

            $this->addFlash('success', 'Temat został zapisany!');

            return $this->redirectToRoute('admin_index', ['_locale' => $_locale]);
        }

        $topics = $topicRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'current_locale' => $_locale,
            'site' => 'admin',
            'form' => $form->createView(),
            'topics' => $topics,
        ]);
    }

    #[Route('/{_locale}/blog/{slug}/new', name: 'blog_new')]
    public function new(string $_locale, Request $request, Topic $topic, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $blog = new Blog();
        $blog->setTopic($topic); // Przypisanie tematu

        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($blog->getTitle())->lower();
            $blog->setSlug($slug);

            $em->persist($blog);
            $em->flush();

            $this->addFlash('success', 'Blog zapisany!');

            return $this->redirectToRoute('topic_show', [
                'slug' => $blog->getTopic()->getSlug(),
                '_locale' => $_locale,
            ]);

        }

        return $this->render('blog/new.html.twig', [
            'form' => $form->createView(),
            'current_locale' => $_locale,
            'site' => 'blog/new',
        ]);
    }

    #[Route('/{_locale}/blog/{slug}', name: 'topic_show')]
    public function show(string $_locale, string $slug, TopicRepository $topicRepository, BlogRepository $blogRepository): Response
    {

        $topic = $topicRepository->findOneBy(['slug' => $slug]);

        if (!$topic) {
            throw $this->createNotFoundException('Temat nie istnieje.');
        }

        $blogs = $blogRepository->findBy(['topic' => $topic]);

        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
            'current_locale' => $_locale,
            'site' => 'blog/' . $slug,
            'blogs' => $blogs,
        ]);
    }

    #[Route('/{_locale}/topic/{id}/edit', name: 'topic_edit')]
    public function edit(string $_locale, int $id, Request $request, Topic $topic, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            // Generujemy slug z tytułu
            $slug = $slugger->slug($topic->getName())->lower();
            $topic->setSlug($slug);
            $entityManager->persist($topic);
            $entityManager->flush();

            $this->addFlash('success', 'Temat został zaktualizowany.');

            return $this->redirectToRoute('admin_index', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'topic' => $topic,
            'current_locale' => $_locale,
            'site' => 'topic/' . $id . '/edit',
        ]);
    }

    #[Route('/{_locale}/topic/{id}/delete', name: 'topic_delete', methods: ['POST'])]
    public function delete(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    { 
        if ($this->isCsrfTokenValid('delete' . $topic->getId(), $request->request->get('_token'))) {
            $entityManager->remove($topic);
            $entityManager->flush();

            $this->addFlash('success', 'Temat został usunięty.');
        }

        return $this->redirectToRoute('admin_index', ['_locale' => $request->getLocale()]);
    }

}
