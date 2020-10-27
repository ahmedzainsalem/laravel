<?php

namespace App\Providers;

 
use Illuminate\Support\ServiceProvider;
use App\Contracts\PostContract;
use App\Repositories\PostRepository;
 

class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        PostContract::class         =>          PostRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation)
        {
            $this->app->bind($interface, $implementation);
        }
    }
}
