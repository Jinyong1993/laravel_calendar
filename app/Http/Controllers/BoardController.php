<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $board_col = Schema::getColumnListing('board');
        $result = in_array($request->sort, $board_col);

        $board = Board::orderby(isset($request->sort) ? $request->sort : 'board_id', isset($request->order) ? $request->order : 'desc')->paginate(5);

        if($result){
            $board = Board::orderby($request->sort, $request->order)->paginate(5);
            
        } else {
            return redirect()->back();
        }
        $data = array(
            'board' => $board,
            'sort' => $request->sort,
            'order' => $request->order,
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

        // １：n ジョイン
        $select->comments();

        $data = array(
            'select' => $select,
            'comment_select' => $select->comments,
        );
        return view('board.board_content', $data);
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
        if($request->board_id){
            $board = Board::find($request->board_id);
        } else {
            $board = new Board();
        }
        $board->user_id = auth()->user()->id;
        $board->title = $request->title;
        $board->note = $request->note;
        $board->save();

        return redirect()->route('board.index')->with('flash_message', '投稿を完了しました。');
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