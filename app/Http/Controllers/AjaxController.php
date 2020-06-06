<?php

namespace App\Http\Controllers;

use App\models\Tags;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getTags(Request $request)
    {
        $tag = $request->tag;
        $tags = [];

        if (strlen($tag) != 0) {
            $similar = Tags::where('tag', 'like', '%' . $tag . '%')->where('tag', '!=', $tag)->get();

            foreach ($similar as $t) {
                array_push($tags, [
                    'name' => $t->tag,
                    'id' => $t->id
                ]);
            }

            $same = Tags::where('tag', $tag)->first();
            if ($same == null)
                $same = 0;
            else {
                $same = [
                    'name' => $same->َtag,
                    'id' => $same->id
                ];
            }
            echo json_encode(['tags' => $tags, 'send' => $tag, 'same' => $same]);
        }

        return;
    }
}

