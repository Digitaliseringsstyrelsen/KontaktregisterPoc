<?php

namespace Digist\Providers;

use Api\Exceptions\BadRequestException;
use Api\Exceptions\ForbiddenException;
use Api\Exceptions\NotFoundException;
use Api\Models\Contact;
use Api\Models\Subscription;
use Api\Models\Term;
use Auth;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Digist\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::bind('subscription', function ($val, $route) {
            if (! $subscription = $route->parameters('contact')['contact']->subscriptions()->find($val)) {
                throw new BadRequestException();
            }

            return $subscription;
        });

        Route::model('term', Term::class, function ($value) {
            throw new NotFoundException(sprintf('Term %s is not found', $value));
        });

        Route::bind('contact', function($val, $route) {
            Auth::shouldUse('api-basic');

            if (nemid()->canAccess($val) === false && Auth::check() === false) {
                throw new ForbiddenException(sprintf('You do not have the required privilege to access contact %s', $val));
            }

            if (! $contact = Contact::where('identifier', $val)->first()) {
                throw new NotFoundException(sprintf('Contact %s is not found', $val));
            }

            return $contact;
        });

        Route::bind('media', function ($val, $route) {
            if (! $media = $route->parameters('contact')['contact']->medias()->find($val)) {
                throw new BadRequestException();
            }

            return $media;
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware(['api', 'nemid'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
