<?php

use App\ShortUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('{short_url}', function ($shortUrl) {
    if (preg_match('#[a-z0-9]{'.ShortUrl::MIN_CHAR_CNT.','.ShortUrl::MAX_CHAR_CNT.'}#', $shortUrl)) {
        $url = ShortUrl::select('url')->where('name', $shortUrl)->where(function ($query){
            $query
                ->whereNull('expired_at')
                ->orWhere('expired_at', '>=', Carbon::now());
        })->value('url');

        if (!empty($url)) {
            return redirect($url);
        }
    }

    throw new NotFoundHttpException;
});

Route::post('', function (Request $request) {
    $input = $request->all();
    $validator = Validator::make($input, [
        'url' => 'required|active_url',
        'expired_at' => 'date_format:Y-m-d H:i:s',
    ]);

    if ($validator->fails()) {
        $result = ['success' => false, 'errors' => $validator->errors()->getMessages()];
    } else {
        $data = [
            'name' => ShortUrl::generate(ShortUrl::MIN_CHAR_CNT, ShortUrl::MAX_CHAR_CNT),
            'url' => $input ['url'],
            'expired_at' => $input ['expired_at'] ?? null,
        ];

        $shortUrl = ShortUrl::create($data);

        $result = [
            'success' => true,
            'newUrl' => getenv('APP_URL') . '/' . $data ['name'],
        ];
    }

    return response()->json($result);
});
