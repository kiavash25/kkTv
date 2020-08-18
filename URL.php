<?php

namespace Illuminate\Support\Facades;

/**
 * @method static \Illuminate\Contracts\Routing\UrlGenerator setRootControllerNamespace(string $rootNamespace)
 * @method static bool hasValidSignature(\Illuminate\Http\Request $request, bool $absolute = true)
 * @method static string action(string $action, $parameters = [], bool $absolute = true)
// * @method static string asset(string $path, bool $secure = null)
 * @method static string current()
 * @method static string full()
 * @method static string previous($fallback = false)
 * @method static string route(string $name, $parameters = [], bool $absolute = true)
 * @method static string secure(string $path, array $parameters = [])
 * @method static string signedRoute(string $name, array $parameters = [], \DateTimeInterface|\DateInterval|int $expiration = null, bool $absolute = true)
 * @method static string temporarySignedRoute(string $name, \DateTimeInterface|\DateInterval|int $expiration, array $parameters = [], bool $absolute = true)
 * @method static string to(string $path, $extra = [], bool $secure = null)
 * @method static void defaults(array $defaults)
 * @method static void forceScheme(string $scheme)
 *
 * @see \Illuminate\Routing\UrlGenerator
 */
class URL extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'url';
    }

    static function asset($path, $secure = null) {
        $tmp = explode('/', $path);

        if(count($tmp) > 0) {
            $stub = "";
            for($i = 1; $i < count($tmp); $i++) {
                if($i != count($tmp) - 1)
                    $stub .= $tmp[$i] . '/';
                else
                    $stub .= $tmp[$i];
            }

            if($tmp[0] == 'videos') {
                return 'https://static.koochita.com/_images/video/' . $stub;
            }
            elseif($tmp[0] == 'images' && $tmp[1] == 'video' && $tmp[2] == 'category' && isset($tmp[3]))
                return 'https://static.koochita.com/_images/' . $stub;
            elseif($tmp[0] == 'images' && $tmp[1] == 'defaultPic')
                return 'https://static.koochita.com/_images/' . $stub;

        }

        return app('url')->asset($path, $secure);
    }

}
