<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//追加
use App\Photo;
use App\Http\Requests\StorePhoto;

class PhotoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'download']);
    }

    /**
     * 写真取得
     */
    public function index() {

        $photos = Photo::with(['owner'])
        ->orderBy(Photo::CREATED_AT, 'desc')->paginate();

        return $photos;
    }

    /**
     *写真ダウンロード
     */
    public function download(Photo $photo)
    {
        if (! \Storage::cloud()->exists($photo->filename)) {
            abort(404);
        };

        $disposition = 'attachment; filename="' . $photo->filename . '"';
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => $disposition,
        ];

        return response(\Storage::cloud()->get($photo->filename), 200, $headers);
    }

    /**
     * 写真投稿
     */
    public function create(StorePhoto $request)
    {
        //投稿写真の拡張子を取得する
        $extension = $request->photo->extension();

        $photo = new Photo();

        //インスタンス生成時に割り振られたランダムなID値と本来の拡張子を組み合わせてファイル名とする
        $photo->filename = $photo->id . '.' . $extension;

        //S3にファイルを保存する
        \Storage::cloud()->putFileAs('', $request->photo, $photo->filename, 'public');

        //データベースエラー時にファイル削除を行うためトランザクションを利用する
        \DB::beginTransaction();

        try {
            \Auth::user()->photos()->save($photo);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            //DBとの不整合を避けるためアップロードしたファイルを削除
            \Storage::cloud()->delete($photo->filename);
            throw $exception;
        }

        //新規作成なので201を返却する
        return response($photo, 201);
    }
}
