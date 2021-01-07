<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//追加
use App\Photo;
use App\Comment;
use App\Http\Requests\StorePhoto;
use App\Http\Requests\StoreComment;

class PhotoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'download', 'show']);
    }

    /**
     * 写真取得
     */
    public function index()
    {

        $photos = Photo::with(['owner'])
            ->orderBy(Photo::CREATED_AT, 'desc')->paginate();

        return $photos;
    }

    /**
     * 写真詳細
     */
    public function show(String $id)
    {

        $photo = Photo::where('id', $id)->with(['owner', 'comments.author'])->first();

        return $photo ?? abort(404);
    }

    /**
     * お気に入り追加
     */
    public function like(String $id)
    {
        $photo = Photo::where('id', $id)->with(['likes'])->first();

        if (!$photo) {
            abort(404);
        }

        //2回お気に入り出来ないように消してから登録する
        $photo->likes()->detach(\Auth::user()->id);
        $photo->likes()->attach(\Auth::user()->id);

        return ["photo_id" => $id];
    }

    /**
     * お気に入り削除
     */
    public function unlike(String $id)
    {
        $photo = Photo::where('id', $id)->with(['likes'])->first();

        if (!$photo) {
            abort(404);
        }

        $photo->likes()->detach(\Auth::user()->id);

        return ["photo_id" => $id];
    }

    /**
     * コメント追加
     */
    public function addComment(Photo $photo, StoreComment $request)
    {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = \Auth::user()->id;
        $photo->comments()->save($comment);

        $new_comment = Comment::where('id', $comment->id)->with(['author'])->first();

        return response($new_comment, 201);
    }

    /**
     *写真ダウンロード
     */
    public function download(Photo $photo)
    {
        if (!\Storage::cloud()->exists($photo->filename)) {
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
