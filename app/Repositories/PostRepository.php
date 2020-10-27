<?php

namespace App\Repositories;

use App\Post;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use App\Contracts\PostContract;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Class CategoryRepository
 *
 * @package \App\Repositories
 */
class PostRepository extends BaseRepository implements PostContract
{
    use UploadAble;

    /**
     * CategoryRepository constructor.
     * @param Post $model
     */
    public function __construct(Post $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listPosts(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findPostById(int $id)
    {
        try {
            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
        }

    }

    /**
     * @param array $params
     * @return Post|mixed
     */
    public function createPost(array $params)
    {
        try {
            $collection = collect($params);

            $logo = null;

            if ($collection->has('logo') && ($params['logo'] instanceof  UploadedFile)) {
                $logo = $this->uploadOne($params['logo'], 'posts');
            }

            $merge = $collection->merge(compact('logo'));

            $post = new Post($merge->all());

            $post->save();

            return $post;

        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updatePost(array $params)
    {
        $post = $this->findPostById($params['id']);

        $collection = collect($params)->except('_token');

        if ($collection->has('logo') && ($params['logo'] instanceof  UploadedFile)) {

            if ($post->logo != null) {
                $this->deleteOne($post->logo);
            }

            $logo = $this->uploadOne($params['logo'], 'posts');
        }

        $merge = $collection->merge(compact('logo'));

        $post->update($merge->all());

        return $post;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deletePost($id)
    {
        $post = $this->findPostById($id);

        if ($post->logo != null) {
            $this->deleteOne($post->logo);
        }

        $post->delete();

        return $post;
    }
}
