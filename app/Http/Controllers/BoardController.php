<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardComment;
use App\Models\BoardFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class BoardController extends Controller
{
    public function index(Request $request)
    {
        if(isset($request->keyword_search) || isset($request->sort)){
            session()->put('index', $request->all());
        }

        $get = session()->get('index');

        $category = $get['category'] ?? $request->category;
        $keyword_search = $get['keyword_search'] ?? $request->keyword_search;
        $date_from = $get['date_from'] ?? $request->date_from;
        $date_to = $get['date_to'] ?? $request->date_to;
        $sort = $get['sort'] ?? $request->sort;
        $order = $get['order'] ?? $request->order;

        //　ボードテーブルのカラムを取得
        $board_col = Schema::getColumnListing('board');
        $board_col[] = 'file_count';
        $board_col[] = 'file_size';
        $board_col[] = 'comment_count';

        if($sort){
            // テーブルのカラム名とマッチさせる
            $sort_available = in_array($sort, $board_col);
            // マッチされたのがなかったら、リターン
            if(!$sort_available) {
                return redirect()->back();
            }
        }

        // セレクトクエリー
        $query = Board::select('board.*', 
                DB::raw('count(distinct board_file.file_id) as file_count'), 
                DB::raw('if(count(board_comment.comment_id) > 0, 
                            (sum(board_file.size) / count(distinct board_comment.comment_id)),
                            (sum(board_file.size) / 1)) as file_size'),
                DB::raw('count(distinct board_comment.comment_id) as comment_count'))
                ->leftjoin('board_file', 'board_file.board_id', '=', 'board.board_id')
                ->leftjoin('board_comment', function ($join){
                    $join->on('board_comment.board_id', '=', 'board.board_id')
                    ->whereNull('board_comment.deleted_at');
                })
                ->groupBy('board_id');
                // ->whereNotNull('board_file.board_id') -> inner join
                // ->whereNull('board_file.board_id') -> anti join
        
        // マッチされたのがあったら、並ばせる
        if(isset($sort_available)) {
            $query->orderby($sort, $order);
        }

        // 期間検索
        if(isset($date_from) && isset($date_to)) {
            $query->whereBetween('board.created_at', [$date_from, $date_to]);
        } else if (isset($date_from)) {
            $query->where('board.created_at', '>=', $date_from);
        } else if (isset($date_to)) {
            $query->where('board.created_at', '<=', $date_to);
        }
        
        $category_available = in_array($category, $board_col);
        // キーワード検索
        if(isset($keyword_search)) {
            if(is_null($category)){
                // カテゴリーが選択されなかった場合
                $query->where('board.title', 'like', "%{$keyword_search}%")
                    ->orWhere('board.note', 'like', "%{$keyword_search}%")
                    ->orWhere('board.user_id', 'like', "%{$keyword_search}%");
            } else {
                // カテゴリーが選択された場合
                $query->where('board.'.$category, 'like', "%{$keyword_search}%");
            }
        } else if(!$category_available){
            // URLパラメーターを触られ、一致しない場合
        }
        
        $board = $query->paginate(10);

        $data = array(
            'board' => $board,
            'board_col' => $board_col,
            'sort' => $sort,
            'order' => $order,
            'category' => $category,
            'keyword_search' => $keyword_search,
            'date_from' => $date_from,
            'date_to' => $date_to,
        );

        return view('board.board_view', $data);
    }

    public function content(Request $request)
    {
        $select = Board::find($request->board_id);

        // 削除済みの投稿をクリックした時
        if(empty($select->board_id)){
            return redirect()->back()->withErrors('エラーが発生しました。');
        }

        //　アクセス数を増やす
        $select->hit += 1;
        $select->save();

        $data = array(
            'select' => $select,
            'comment_select' => $select->comments,
            'board_file_select' => $select->board_file,
        );
        return view('board.board_content', $data);
    }

    public function download(Request $request)
    {
        $file = BoardFile::find($request->file_id);
        $file_path = $file->route;
        $file_name = $file->name.'.'.$file->extension;
        $file_exist = Storage::exists($file->route);

        // ファイルが存在するかどうか
        if($file_exist){
            $mime_type = Storage::mimeType($file_path);
            $headers = [['Content-Type' => $mime_type]];
            return Storage::download($file_path, $file_name, $headers);
        } else {
            return redirect()->back()->withErrors('ファイルが存在しません。');
        }
    }

    public function create_view(Request $request)
    {
        $select = Board::find($request->board_id);

        $data = array(
            'select' => $select,
        );
        return view('board.board_create', $data);
    }
    
    public function create(Request $request)
    {
        if(empty($request->title) || empty($request->note)){
            return redirect()->back()->withErrors('タイトルや内容欄に入力は必須です。');
        }

        // フォームからアップロードされたファイル
        $files = $request->file('file');
        
        if($request->board_id){
            $board = Board::find($request->board_id);
        } else {
            $board = new Board();
        }
        $board->user_id = auth()->user()->id;
        $board->title = $request->title;
        $board->note = $request->note;
        $board->save();

        // 投稿をDBに保存したあと、ファイルがあったら
        if(isset($files)){
            foreach($files as $file){
                // フルネーム、拡張子を含んでいる
                $file_name_with_ext = $file->getClientOriginalName();
                // 拡張子を無くす
                $file_name = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
                // ファイルの拡張子だけ抽出する
                $file_extension = $file->getClientOriginalExtension();
                // ファイルのサイズを収得
                $file_size = $file->getSize();
    
                $board_file = new BoardFile();
                $board_file->board_id = $board->board_id;
                $board_file->name = $file_name;
                $board_file->size = $file_size;
                $board_file->extension = $file_extension;
                // not nullのため, 適当にルートの名前をつける
                $board_file->route = 'fwefw';
                $board_file->save();
    
                // DBにファイルを保存した後、プライマリキーで名前を変えて保存する
                $file_name_to_store = $board_file->file_id.'.'.$file_extension;
                // パブリックディレクトリに保存
                $path = $file->storeAs('public', $file_name_to_store);
                $board_file->route = $path;
                $board_file->save();
            }
        }

        return redirect()->route('board.index')->with('flash_message', '投稿を完了しました。');
    }

    public function file_delete(Request $request)
    {
        $board_file = BoardFile::find($request->file_id);
        $board_file->delete();
        return redirect()->back()->with('flash_message', '削除しました。');
    }
    
    public function delete(Request $request)
    {
        $board = Board::find($request->board_id);
        // 投稿がない場合
        if(empty($board)){
            return redirect()->back()->withErrors('エラーが発生しました。');
        }
        // 投稿したユーザーと接続しているユーザーと違う場合
        if($board->user_id != auth()->user()->id){
            return redirect()->back()->withErrors('エラーが発生しました。');
        }
        
        $board->delete();
        return redirect()->route('board.index')->with('flash_message', '削除しました。');
    }

    public function comment_delete(Request $request)
    {
        $comment = BoardComment::find($request->comment_id);
        if(empty($comment)){
            return redirect()->back()->withErrors('エラーが発生しました。');
        }
        if($comment->user_id != auth()->user()->id){
            return redirect()->back()->withErrors('エラーが発生しました。');
        }

        $comment->delete();
        return redirect()->back()->with('flash_message', '削除しました。');

    }

    public function comment_create(Request $request)
    {
        if($request->comment_id){
            $comment = BoardComment::find($request->comment_id);
        } else {
            $comment = new BoardComment();
        }
        $comment->user_id = auth()->user()->id;
        $comment->board_id = $request->board_id;
        $comment->note = $request->comment_note;
        $comment->save();
        return redirect()->back()->with('flash_message', '投稿を完了しました。');
    }

    public function comment_update_ajax(Request $request)
    {
        $comment = BoardComment::find($request->comment_id);
        $comment->note = $request->comment_note;
        $comment->save();

        $response = array(
            'success' => true
        );

        return json_encode($response);
    }
}
