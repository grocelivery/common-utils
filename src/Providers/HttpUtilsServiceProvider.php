<?php

declare(strict_types=1);

namespace Grocelivery\HttpUtils\Providers;

use Grocelivery\HttpUtils\Requests\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

/**
 * Class HttpUtilsServiceProvider
 * @package Grocelivery\HttpUtils\Providers
 */
class HttpUtilsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '../../../config/http-utils.php' => app()->basePath() . '/config/http-utils.php',
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
        $form->initialize(
            $current->query(),
            $current->request->all(),
            $this->getRouteParameters($current),
            $current->cookies->all(),
            $this->getFiles($current),
            $current->server(),
            $current->getContent()
        );

        $form->setJson($current->json());
    }

    /**
     * @param Request $current
     * @return array
     */
    protected function getFiles(Request $current): array
    {
        $files = $current->files->all();
        return is_array($files) ? array_filter($files) : $files;
    }

    /**
     * @param Request $current
     * @return array
     */
    protected function getRouteParameters(Request $current): array
    {
        $route = $current->route();
        return is_array($route) ? end($route) : $route->parameters();
    }
}