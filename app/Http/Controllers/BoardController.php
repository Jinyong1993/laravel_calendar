<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardComment;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $select = Board::all();
        $data = array(
            'select' => $select,
        );
        return view('board.board_view', $data);
    }

    public function content(Request $request)
    {
        $select = Board::find($request->board_id);
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

        return redirect()->route('board.index');
    }
    
    public function delete(Request $request)
    {
        $board = Board::find($request->board_id);
        $board->delete();
        return redirect()->route('board.index');
    }

    public function comment_delete(Request $request)
    {
        $comment = BoardComment::find($request->comment_id);
        $comment->delete();
        return redirect()->back();
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
        return redirect()->back();
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
