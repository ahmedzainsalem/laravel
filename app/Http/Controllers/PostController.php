<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\PostContract;
use App\Http\Controllers\BaseController;

class PostController extends BaseController
{
    /**
     * @var PostContract
     */
    protected $postRepository;

    /**
     * CategoryController constructor.
     * @param PostContract $postRepository
     */
    public function __construct(PostContract $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $posts = $this->postRepository->listPosts();

        $this->setPageTitle('Posts', 'List of all Posts');
        return view('posts.index', compact('posts'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->setPageTitle('posts', 'Create Post');
        return view('posts.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      =>  'required|max:191',
            'image'     =>  'mimes:jpg,jpeg,png|max:1000'
        ]);

        $params = $request->except('_token');

        $post = $this->postRepository->createPost($params);

        if (!$post) {
            return $this->responseRedirectBack('Error occurred while creating Post.', 'error', true, true);
        }
        return $this->responseRedirect('posts.index', 'post added successfully' ,'success',false, false);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $post = $this->postRepository->findPostById($id);

        $this->setPageTitle('Posts', 'Edit Post : '.$post->name);
        return view('posts.edit', compact('Post'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name'      =>  'required|max:191',
            'image'     =>  'mimes:jpg,jpeg,png|max:1000'
        ]);

        $params = $request->except('_token');

        $post = $this->postRepository->updatePost($params);

        if (!$post) {
            return $this->responseRedirectBack('Error occurred while updating Post.', 'error', true, true);
        }
        return $this->responseRedirectBack('Post updated successfully' ,'success',false, false);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $post = $this->postRepository->deletePost($id);

        if (!$post) {
            return $this->responseRedirectBack('Error occurred while deleting Post.', 'error', true, true);
        }
        return $this->responseRedirect('posts.index', 'Post deleted successfully' ,'success',false, false);
    }
}
