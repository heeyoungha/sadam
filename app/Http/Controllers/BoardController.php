<?php

namespace App\Http\Controllers;

use Illuminate\support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Board;
use Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class BoardController extends Controller
{
    public function index(Request $request){

        $user = Auth::user();

        $perPage = 10;
        $searchTitle = $request->get('searchTitle');
        $selectValue = "";

        $table_data = Board::leftJoin('users as u', 'boards.user_id','=','u.id')
        ->select('boards.*','u.id as u_id','u.name as uname')
        ->orderBy('boards.created_at','desc');
        
        if($searchTitle){
            switch($request->filter){
                case 'titleContent':
                    $table_data = $table_data
                        ->where('boards.content', 'like','%'.$searchTitle.'%')
                        ->orwhere('boards.title', 'like','%'.$searchTitle.'%');
                    break;
                case 'content':
                    $table_data = $table_data
                        ->where('boards.content', 'like','%'.$searchTitle.'%');
                    break;
                case 'actor':
                    $table_data = $table_data
                        ->where('u.name', 'like','%'.$searchTitle.'%');
                    break;
            }
        }

        $table_data = $table_data->get();
        // dd($table_data);
        $filteredCollection = collect($table_data);
        $filteredArray = $filteredCollection->all();
        $countCollection = count($filteredCollection);
        $newCollection = collect($filteredArray);

        return view('board.index', compact('user','searchTitle','selectValue','countCollection','newCollection'));
    }
    public function create(){
        $user = Auth::user();
        return view('board.create',compact('user'));
    }

    public function store(Request $request){

        $user = Auth::user();
        try{
            DB::beginTransaction();
            $board = new Board;
            $board->title = $request->title;
            $board->content = $request->editordata;
            $board->user_id = $user->id;
            $board->save();
            DB::commit();

            return redirect() -> route('board_index');
        } catch (\Exception $e){
            DB::rollBack();
            return response([
                'status' => 'error',
                'message' => '에러가 발생했습니다',
                'error' => $e->getMessage()
            ]);
        }
        
    }
    public function show($board_id, Request $request){

        try{
            $user = Auth::user();
            
            //게시판 출력
            $board = Board::find($board_id);//dd($board) -> 객체 출력됨
            $board->increment('view_cnt');
            $photoUrl = User::find($board->user_id)->photoUrl ?? ''; //dd($photoUrl) -> ""출력됨
            //$baord->user_thumbnail = $photoUrl;//Attempt to assign property 'user_thumbnail' on null" 오류 출력됨
            $board->user_name = User::find($board->user_id)->name;

            //댓글 출력
            $replies = $board->replies()->get();
            foreach($replies as $reply){
                $reply->user_name= User::find($reply->user_id)->name;
                $reply->user_thumbnail = User::find($reply->user_id)->photoUrl;
            }
            $replyCount = count($replies);


            return view('board.show', compact('board','user','replyCount','replies'));

        }catch (\Exception $e){

            return response([
                'status' => 'error',
                'message' => '에러가 발생했습니다',
                'error' => $e->getMessage()
            ]);
        }
        
        
    }

    public function edit($board_id, Request $request){
        $board = Board::find($board_id);
        $user = Auth::user();

        return view('board.edit', compact('board','user'));
    }
    public function update($board_id, Request $request){

        $board = Board::find($board_id);
        $board->title = $request->title;
        $board->content = $request->content;
        $board->save();
        

        return redirect()->route('board_show', ['board_id' => $board->id]);
        
    }
    public function destroy($board_id){

        try{
            
            DB::beginTransaction();
            $board = Board::find($board_id);
            $board->delete();
            DB::commit();

            return redirect() -> route('board_index');
        
        }catch(\Exception $e){

            DB::rollback();
            return response([
                'status' => 'error',
                'message' => '에러가 발생했습니다',
                'error' => $e->getMessage()
            ]);
        }

        
    }
}
