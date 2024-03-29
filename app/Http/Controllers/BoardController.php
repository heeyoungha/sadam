<?php

namespace App\Http\Controllers;

use Illuminate\support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Board;
use App\Models\BoardReaction;
use Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BoardController extends Controller
{
    public function index(Request $request){

        try{

            $user = Auth::user();
            $table_data = Board::leftJoin('users as u', 'boards.user_id','=','u.id')
            ->select('boards.*','u.id as u_id','u.name as uname')
            ->orderBy('boards.created_at','desc');

            $sn = $request->get('sn');
            $sf = $request->get('sf');
            $sfs = ['title', 'content', 'writer'];

            if ($sn) {
                $table_data = $this->searchTableData($table_data, $request);
            }

            $newCollection = $table_data->get()->map(function ($item) {
                $created_at = Carbon::parse($item['created_at']);
                
                // 24시간 이전인 경우
                $item['formatted_created_at'] = ($created_at->diffInHours(now()) < 24)
                    ? $created_at->diffForHumans()
                    : $created_at->format('Y-m-d');
                
                return $item;
            });

            $newCollection = $this->paginate($newCollection);
            $newCollection->withPath('/board');
            $countCollection = $newCollection->total();
            
            return view('board.index', compact('user','sn','sf','sfs','countCollection','newCollection'));

        } catch (\Exception $e){

            return response([
                'status' => 'error',
                'message' => '에러가 발생했습니다',
                'error' => $e->getMessage()
            ]);
        }
    }
    private function searchTableData($table_data, $request, $sn, $sf, $sfs){

        switch($request->sf){
            
            case $sfs[0] :
                $table_data = $table_data
                    ->where('boards.'.$sf, 'like','%'.$sn.'%');
                break;
            case $sfs[1] :
                $table_data = $table_data
                    ->where('boards.'.$sf, 'like','%'.$sn.'%');
                break;
            case $sfs[2] :
                $table_data = $table_data
                    ->where('u.name', 'like','%'.$sn.'%');
                break;
        }

        return $table_data;

    }
    private function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        
        $items = $items instanceof Collection ? $items : Collection::make($items);
        
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), 
                            $perPage, $page, $options);
    }


    public function create(){
        try{

            $user = Auth::user();
            return view('board.create',compact('user'));

        } catch (\Exception $e){

            return response([
                'status' => 'error',
                'message' => '에러가 발생했습니다',
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function store(Request $request){

        
        try{
            $user = Auth::user();
            $request->validate([
                'title' => 'required|max:255',
                'editordata' => 'required',
            ]);

            DB::beginTransaction();
            $board = new Board;
            $board->title = $request->title;
            $board->content = $request->editordata;
            $board->user_id = $user->id;
            $board->save();
            DB::commit();

            return redirect() -> route('board.index');
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
            $board->user_name = User::find($board->user_id)->name;

            //댓글 출력
            $replies = $board->replies()->get();
            foreach($replies as $reply){
                $reply->user_name= User::find($reply->user_id)->name;
                $reply->user_thumbnail = User::find($reply->user_id)->photoUrl;
            }
            $replyCount = count($replies);

            $boardLike = $board->boardLikeReactions()->get();
            $boardLikeCnt = count($boardLike);
            $boardBookmark = $board->boardBookmarkReactions()->get();
            $boardBookmarkCnt = count($boardBookmark);

            //작성자와 로그인 유저가 동일한 경우 'active'값 뷰로 넘기기
            $post_user_id = User::find($board->user_id)->id;
            if($user->id === $post_user_id || $user->role_id == 1){
                $is_post_author = 'active';
            }else{
                $is_post_author = '';
            }

            return view('board.show', compact('board','user','replyCount','replies','boardLikeCnt','boardBookmarkCnt','is_post_author'));

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
        if ($request->user()->cannot('update', $board)) {
            abort(403, '죄송합니다. 글을 수정할 권한이 없습니다.');
        }

        $board = Board::find($board_id);
        $user = Auth::user();

        return view('board.edit', compact('board','user'));
    }
    public function update($board_id, Request $request){

        try{

            $board = Board::find($board_id);
            if ($request->user()->cannot('update', $board)) {
                abort(403, '죄송합니다. 글을 수정할 권한이 없습니다.');
            }
            
            $request->validate([
                'title' => 'required|max:255',
                'content' => 'required',
            ]);

            DB::beginTransaction();
            $board = Board::find($board_id);
            $board->title = $request->title;
            $board->content = $request->content;
            $board->save();
            DB::commit();

            return redirect()->route('board_show', ['board_id' => $board_id]);

        } catch (\Exception $e){

            return response([
                'status' => 'error',
                'message' => '에러가 발생했습니다',
                'error' => $e->getMessage()
            ]);
        }

    }
    public function destroy($board_id){
        $user = Auth::user();
        $board = Board::find($board_id);
        if ($user->cannot('delete', $board)) {
            abort(403, '죄송합니다. 글을 삭제할 권한이 없습니다.');
        }
        
        try{

            $board = Board::findOrFail($board_id);


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

    public function boardReaction($board_id, Request $request){
    
        try {

            DB::beginTransaction();
            
            $user = User::find($request->user_id);
            $type = $request->input('type');

            $existingReaction = BoardReaction::where('user_id', $user->id)
                ->where('board_id', $board_id)
                ->first();
            
            switch($type){
                case 'like':
                    $this->handleLike($existingReaction, $user, $board_id);
                    break;
                case 'bookmark':
                    
                    $this->handleBookmark($existingReaction, $user, $board_id);
                    break;
            }
            
            $likeCnt = BoardReaction::where('board_id',$board_id)->where('type','like')->count();
            $likeCnt = $likeCnt === 0 ? '' : $likeCnt;
            $bookmarkCnt = BoardReaction::where('board_id',$board_id)->where('type','bookmark')->count();
            $bookmarkCnt = $bookmarkCnt === 0 ? '' : $bookmarkCnt;

            DB::commit();

            return response()->json([
                'success' => true, 
                'likeCnt' => $likeCnt,
                'bookmarkCnt' => $bookmarkCnt,
                'message' => 'Board reaction updated successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred'
            ], 500);
        }
    }

    private function handleLike($existingReaction, $user, $board_id) {
        if ($existingReaction) {
            switch ($existingReaction->type) {
                case 'like':
                    $existingReaction->delete();
                    break;
                case 'bookmark':
                    $existingReaction->update(['type' => 'like']);
                    break;
            }
        } else {
            $boardReaction = new BoardReaction();
            $boardReaction->user_id = $user->id;
            $boardReaction->board_id = $board_id;
            $boardReaction->type = 'like';
            $boardReaction->save();
        }
    }
    private function handleBookmark($existingReaction, $user, $board_id) {
        
        if ($existingReaction) {
            
            switch ($existingReaction->type) {
                case 'like':
                    $existingReaction->update(['type' => 'bookmark']);
                    break;
                case 'bookmark':
                    $existingReaction->delete();
                    break;
            }
        } else {
            $boardReaction = new BoardReaction();
            $boardReaction->user_id = $user->id;
            $boardReaction->board_id = $board_id;
            $boardReaction->type = 'bookmark';
            $boardReaction->save();
        }
    }
}
