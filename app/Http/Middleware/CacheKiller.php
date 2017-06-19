<?php namespace App\Http\Middleware;
use Closure;
class CacheKiller
{
  public function handle($request, Closure $next)
  {
    $cachedViewsDirectory = app('path.storage').'/framework/views/';
    if ($handle = opendir($cachedViewsDirectory))
    {
      while (false !== ($entry = readdir($handle)))
      {
        if (strstr($entry, '.'))
        {
          continue;
        }
        @unlink($cachedViewsDirectory.$entry);
      }
      closedir($handle);
    }
    return $next($request);
  }
}