<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Metatag;
use Route;
use Meta;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setMetaTags($replacement = null, $title = null, $description = null, $keywords = null) {
        $metatags = Metatag::where('route', Route::currentRouteName())->first();

        $res = false;

        $name = ($title ?: (!empty($metatags) ? $metatags->name : null));
        $title = ($title ?: (!empty($metatags) ? $metatags->title : null));
        $description = ($description ?: (!empty($metatags) ? $metatags->description : null));
        $keywords = ($keywords ?: (!empty($metatags) ? $metatags->keywords : null));

        if($title || $description || $keywords) {
            if($replacement) {
                $title       = strtr($title, $replacement);
                $description = strtr($description, $replacement);
                $keywords    = strtr($keywords, $replacement);
            }

            Meta::setTitle($title)
                ->setMetaDescription($description)
                ->setMetaKeywords($keywords);

            $res = $metatags;
        }
        return $res;
    }
}
