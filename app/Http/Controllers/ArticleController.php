<?php

namespace App\Http\Controllers;
use app;
use App\Article;
use function GuzzleHttp\Psr7\get_message_body_summary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use \Statickidz\GoogleTranslate;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();
        return response()->json($articles);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'text' => 'required',
        ];

        $this->validate($request, $rules);
        $data = $request->all();
        $data['online'] =true;
        $article = Article::create($data);
        $article->online = true;
        $article->save();
        ///
        ///

        foreach (['en', 'nl', 'fr', 'de','ar'] as $locale) {
            $source = 'en';
            $target = $locale;
            $trans = new GoogleTranslate();
            $result_name = $trans->translate($source, $target, $request->name);
            $result_text= $trans->translate($source, $target, $request->text);
            $article->translateOrNew($locale)->name =  $result_name ;
            $article->translateOrNew($locale)->text = $result_text;
        }

        $article->save();
        return response()->json($article);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return response()->json($article);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
    public function locale(Article $article,$locale)
    {
        try {
            app()->setLocale($locale);
            $query = DB::table('article_translations')->where('article_id', '=', $article->id)->where('locale', '=', $locale)->first();
            return response()->json($query);
        }
        catch ( Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
