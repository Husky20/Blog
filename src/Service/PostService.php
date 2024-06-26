<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Post $post
     * @return void
     */
    public function createPost(Post $post): void
    {
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    /**
     * @param Post $post
     * @return void
     */
    public function deletePost(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

   /**
    * @param array $posts
    * @return void
    */
    public function savePostsToDatabase(array $posts): void
    {
        $postAdded = false;
        $existingTitles = array_column($this->entityManager->createQueryBuilder()->select('p.title')->from(Post::class, 'p')->getQuery()->getResult(), 'title');
        foreach ($posts as $post) {
            if (!in_array($post['title'], $existingTitles)) {
                $postEntity = new Post();
                $postEntity->setTitle($post['title']);
                $postEntity->setBody($post['body']);
                $postEntity->setAuthor($post['author']);
                $postEntity->setCreatedAt(new \DateTimeImmutable());
                $postEntity->setUpdatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($postEntity);
                $postAdded = true;
            }
        }
        if ($postAdded) {
            $this->entityManager->flush();
        } else {
            echo "No new posts to add.\n\n";
        }
    }

    /**
     * @param Post $post
     * @return void
     */
    public function updatePost(Post $post): void
    {
        $this->entityManager->flush();
    }
}

