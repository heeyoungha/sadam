<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Board;

class BoardController extends Controller
{
    public function index(Request $request){

        $user = Auth::user();
        
        $perPage = 10;
        $searchTitle = $request->get('searchTitle');
        $selectValue = "";

        $table_data = Board::leftJoin('users as u', 'boards.user_id','=','u.id')
        ->select('boards.*','u.id as u_id','u.user_name as uname')
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

        $table_data = table_data->get();
        $filteredCollection = collect($table_data);
        $filteredArray = $filteredCollection->all();
        $countCollection = count($filteredCollection);
        $newCollection = collect($filteredArray);
        $currentPage = request()->input('page',1);
        $currentPageItems = $newCollection->slice(($currentPage -1)*$perpage, $perPage);
        $paginator = new LengthAwarePaginator($currentPageItems, count($newCollection), $perpage, $currentPage, [
            'path'=> Pagicator::resolveCurrentPath(),
        ]);

        return view('board.index',['boards']);
    }
    public function store($board_id, Request $request){
        return redirect(route('board_show'));
    }
    public function show(){
        
    }
    public function update(){
        
    }
    public function destroy(){

        
    }
}
