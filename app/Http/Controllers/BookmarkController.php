<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Board;
use Illuminate\Database\Query\Builder;
use Auth;
use App\Policies\BoardPolicy;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BookmarkController extends Controller
{
    
    public function index(Request $request) {
        try{

            $user = Auth::user();
            if ($request->user()->cannot('view', Bookmark::class)) {
                abort(403, '죄송합니다. 조회 권한이 없습니다.');
            }
            
            $query = Board::leftJoin('users as u', 'boards.user_id', '=', 'u.id')
            ->select('boards.*', 'u.id as u_id', 'u.name as uname')
            ->leftJoinSub(function (Builder $query) {
                $query->select('board_id', \DB::raw('count(*) as reaction_count'))
                    ->from('board_reactions')
                    ->groupBy('board_id');
            }, 'reactions', 'boards.id', '=', 'reactions.board_id')
            ->selectRaw('IFNULL(reactions.reaction_count, 0) as reaction_count')
            ->having('reaction_count', '>', 0)
            ->orderByDesc('reaction_count')
            ->orderByDesc('boards.created_at');

            $sn = $request->get('sn');
            if ($sn) {
                $query->where('u.name', 'like', "%$sn%");
            }


            $newCollection = $query->get()->map(function ($item) {
                $created_at = Carbon::parse($item['created_at']);
                
                // 24시간 이전인 경우
                $item['formatted_created_at'] = ($created_at->diffInHours(now()) < 24)
                    ? $created_at->diffForHumans()
                    : $created_at->format('Y-m-d');
                
                return $item;
            });
            
            $newCollection = $this->paginate($newCollection);
            $newCollection->withPath('/bookmark');
            $countCollection = $newCollection->total();
            return view('bookmark.index', compact('user','sn','countCollection','newCollection'));

        } catch (\Exception $e){

            return response([
                'status' => 'error',
                'message' => '에러가 발생했습니다',
                'error' => $e->getMessage()
            ]);
        }
        
    }
    private function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        
        $items = $items instanceof Collection ? $items : Collection::make($items);
        
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), 
                            $perPage, $page, $options);
    }
}
