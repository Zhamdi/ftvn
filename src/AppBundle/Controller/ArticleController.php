<?php

namespace AppBundle\Controller;

use AppBundle\Form\ArticleType;
use AppBundle\Entity\Article;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Rest controller for articles.
 *
 * @author Hamdi Zayati <hamdi.zayati@gmail.com>
 */
class ArticleController extends FOSRestController
{
    /**
     * List all articles.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request the request object
     *
     * @return array
     */
    public function getArticlesAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articles = $entityManager->getRepository('AppBundle:Article')->findAll();

        return array('articles' => $articles);
    }

    /**
     * Get a single article.
     *
     * @ApiDoc(
     *   output = "AppBundle\Model\Article",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the article is not found"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request the request object
     * @param int     $id      the article id
     *
     * @return array
     *
     * @throws NotFoundHttpException when article not exist
     */
    public function getArticleAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository('AppBundle:Article')->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article does not exist.');
        }

        return array('article' => $article);
    }

    /**
     * Presents the form to use to create a new article.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @return FormTypeInterface
     */
    public function newArticleAction()
    {
        return $this->createForm(new ArticleType());
    }

    /**
     * Creates a new article from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\ArticleType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "AppBundle:Article:newArticle.html.twig",
     *   statusCode = Response::HTTP_BAD_REQUEST
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface[]|View
     */
    public function postArticlesAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->routeRedirectView('get_article', array('id' => $article->getId()));
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Presents the form to use to update an existing article.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     200 = "Returned when successful",
     *     404 = "Returned when the article is not found"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request the request object
     * @param int     $id      the article id
     *
     * @return FormTypeInterface
     *
     * @throws NotFoundHttpException when article not exist
     */
    public function editArticlesAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository('AppBundle:Article')->find($id);
        if (false === $article) {
            throw $this->createNotFoundException('Article does not exist.');
        }

        $form = $this->createForm(new ArticleType(), $article);

        return $form;
    }

    /**
     * Update existing article from the submitted data or create a new article at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "AppBundle\Form\ArticleType",
     *   statusCodes = {
     *     201 = "Returned when a new resource is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template="AppBundle:Article:editArticle.html.twig",
     *   templateVar="form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the article id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when article not exist
     */
    public function putArticlesAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository('AppBundle:Article')->find($id);
        if (!$article) {
            $article = new Article();
            $statusCode = Response::HTTP_CREATED;
        } else {
            $statusCode = Response::HTTP_NO_CONTENT;
        }
        $form = $this->createForm(new ArticleType(), $article, array('method' => 'PUT'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->routeRedirectView('get_article', array('id' => $article->getId()), $statusCode);
        }

        return $form;
    }

    /**
     * Removes a article.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the article id
     *
     * @return View
     */
    public function deleteArticlesAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository('AppBundle:Article')->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article does not exist.');
        }
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->routeRedirectView('get_articles', array(), Response::HTTP_NO_CONTENT);
    }

    /**
     * Removes a article.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the article id
     *
     * @return View
     */
    public function removeArticlesAction(Request $request, $id)
    {
        return $this->deleteArticlesAction($request, $id);
    }
}
