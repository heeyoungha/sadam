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
                case 'titleContents':
                    $table_data = $table_data
                        ->where('boards.content', 'like','%',$searchTitle.'%')
                        ->orwhere('boards.title', 'like','%',$searchTitle.'%');
                    break;
                case 'titleContents':
                    $table_data = $table_data
                        ->where('boards.content', 'like','%',$searchTitle.'%');
                    break;
                case 'titleContents':
                    $table_data = $table_data
                        ->where('boards.content', 'like','%',$searchTitle.'%');
                    break;
            }
        }

        $table_data = $table_data->get();
        $filteredCollection = collect($table_data);
        $filteredArray = $filteredCollection->all();
        $countCollection = count($filteredCollection);
        $newCollection = collect($filteredArray);
        $currentPage = request()->input('page',1);
        $currentPageItems = $newCollection->slice(($currentPage -1)*$perPage, $perPage);
        $paginator = new LengthAwarePaginator($currentPageItems, count($newCollection), $perPage, $currentPage, [
            'path'=> Paginator::resolveCurrentPath(),
        ]);

        return view('board.index',['paginator'=>$paginator], compact('user','searchTitle','selectValue','countCollection','currentPageItems'));
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
            
            $board = Board::find($board_id);//dd($board) -> 객체 출력됨
            $board->increment('view_cnt');
            $photoUrl = User::find($board->user_id)->photoUrl ?? ''; //dd($photoUrl) -> ""출력됨
            //$baord->user_thumbnail = $photoUrl;//Attempt to assign property 'user_thumbnail' on null" 오류 출력됨
            $board->user_name = User::find($board->user_id)->name;

            return view('board.show', compact('board','user'));

        }catch (\Exception $e){

            return response([
                'status' => 'error',
                'message' => '에러가 발생했습니다',
                'error' => $e->getMessage()
            ]);
        }
        
        
    }
    public function update(){
        
    }
    public function destroy(){

        
    }
}
