<?php

declare(strict_types=1);

namespace Grocelivery\HttpUtils\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Grocelivery\HttpUtils\Requests\FormRequest;

/**
 * Class HttpUtilsServiceProvider
 * @package Grocelivery\HttpUtils\Providers
 */
class HttpUtilsServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '../../../config/http-utils.php' => app()->basePath().'/config/http-utils.php',
        ]);

        $this->app->resolving(FormRequest::class, function (FormRequest $request) {
            $this->initializeRequest($request, app()->make('request'));
        });

        $this->app->afterResolving(FormRequest::class, function (FormRequest $form) {
            $form->validate();
        });
    }

    /**
     * @param FormRequest $form
     * @param Request $current
     */
    protected function initializeRequest(FormRequest $form, Request $current): void
    {
        $files = $current->files->all();
        $files = is_array($files) ? array_filter($files) : $files;
        $route = $current->route();
        $form->initialize($current->query(), $current->request->all(), end($route), $current->cookies->all(), $files, $current->server(), $current->getContent());
        $form->setJson($current->json());
    }
}