<?php

declare(strict_types=1);

namespace App\Service;

use Exception;

class PostApiService
{
    const API_URL = 'https://jsonplaceholder.typicode.com';
    private PostService $postService;

    /**
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getPostsFromApi(): array
    {
        $response = file_get_contents(self::API_URL.'/posts');

        if (!$response) {
            throw new Exception("Error: Failed to fetch data from API\n");
        }

        return json_decode($response, true);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function importPostsFromApi(): void
    {
        $posts = $this->setUserToPosts($this->getPostsFromApi());
        $this->postService->savePostsToDatabase($posts);
    }

    /**
     * @param int $userId
     * @return string
     * @throws Exception
     */
    private function getPostUserFromApi(int $userId): string
    {
        $response = file_get_contents(self::API_URL.'/users/'.$userId);

        if (!$response) {
            throw new Exception("Error: Failed to fetch user from API\n");
        }

        $user = json_decode($response, true);

        return $user['name'];
    }

    /**
     * @param array $posts
     * @return array
     * @throws Exception
     */
    private function setUserToPosts(array $posts): array
    {
        $postsWithUser = [];

        foreach ($posts as $post) {
            $post['author'] = $this->getPostUserFromApi($post['userId']);
            $postsWithUser[] = $post;
        }

        return $postsWithUser;
    }
}