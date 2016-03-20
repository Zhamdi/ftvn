<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;

/**
 * Load data fixtures.
 */
class LoadArticleData implements FixtureInterface
{
    protected $articles = array(
        array(
            'title' => 'Introduction au composant Form',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eros diam, ornare sit amet urna nec, placerat commodo mi',
            'Leading' => 'Lorem ipsum',
            'CreatedBy' => 'Alain',
        ),
        array(
            'title' => 'Introduction au composant Config',
            'body' => 'Donec egestas sapien posuere justo blandit tincidunt. Ut ex ipsum, molestie commodo pharetra quis, pharetra at massa.',
            'Leading' => 'Donec egestas',
            'CreatedBy' => 'Marie',
        ),
    );

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->articles as $article) {
            $entity = new Article();
            $entity->setTitle($article['title']);
            $entity->setBody($article['body']);
            $entity->setLeadin($article['Leading']);
            $entity->setCreatedBy($article['CreatedBy']);
            $manager->persist($entity);
        }
        $manager->flush();
    }
}
