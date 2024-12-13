<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class CompanyGroup extends Model
{
    protected $table = "company_group";

    use SoftDeletes;






    protected $dates = ['deleted_at'];

    public static function getById($id){
        $query = self::select();
        return $query->where('id', $id)
            ->first();
    }

    public static function getByCompanyId($id){
        $query = self::select();
        return $query->where('company_id', $id)
            ->first();
    }

    public static function getCompanyGroupList($param){
        $query = self::select();

        $query->where('company_id',$param['company_id']);

        if(!empty($param['keyword']) ){
            $keyword = $param['keyword'];
            $query->whereRaw("( id LIKE '%$keyword%' OR title LIKE '%$keyword%' )");
        }
        $query->orderBy('id','DESC');
        return $query->paginate(Config::get('constants.PAGINATION_PAGE_SIZE'));
    }


//     public static function  getCompanyGroupDatatable($params){
//         $output = [];
//         parse_str($params['custom_search'], $output);

//         $query = self::selectRaw('company_group.*')->with(['assigned_user']);
//         $query->where('company_id',$params['company_id']);
// //        $query->join('users AS u','u.company_group_id','company_group.id');
//         if(!empty($output['keyword']) ){
//             $keyword = $output['keyword'];
//             $query->whereRaw("( id LIKE '%$keyword%' OR title LIKE '%$keyword%' )");
//         }

//         $sortMap = [
//             'title',
//         ];

//         $data['total_record'] = $query->count();
//         $params['column_index'] = empty($sortMap[$params['column_index']]) ? 0 : $params['column_index'];
//         $query = $query->take($params['length'])->skip($params['start'])->orderBy($sortMap[$params['column_index']],$params['sort']);


//         $query = $query->get();
// //        \Log::debug('$records'.print_r($query->toArray(),1));
//         $data['records'] = $query;
//         return $data;
//     }
    public static function getCompanyGroupDatatable($params)
    {
        $output = [];
        parse_str($params['custom_search'], $output);

        // Initialize query
        $query = self::selectRaw('company_group.*')->with(['assigned_user']);
        $query->where('company_id', $params['company_id']);

        // Keyword search
        if (!empty($output['keyword'])) {
            $keyword = $output['keyword'];
            $query->whereRaw("(id LIKE '%$keyword%' OR title LIKE '%$keyword%')");
        }

        // Sorting
        $sortMap = ['title'];
        $params['column_index'] = isset($sortMap[$params['column_index']]) ? $params['column_index'] : 0;

        // Count total records before applying pagination
        $data['total_record'] = $query->count();

        // Ensure length and start are set for pagination
        $params['length'] = isset($params['length']) ? (int)$params['length'] : 10; // Default to 10 records per page
        $params['start'] = isset($params['start']) ? (int)$params['start'] : 0; // Default offset is 0

        // Apply pagination and sorting
        // $query = $query->orderBy($sortMap[$params['column_index']], $params['sort'])
        //             ->skip($params['start'])
        //             ->take($params['length']);
        $query = $query->orderBy($sortMap[$params['column_index']], $params['sort']);            

        // Get paginated records
        $data['records'] = $query->get();

        return $data;
    }

    public function assigned_user(){
        return $this->hasMany('App\Models\User','company_group_id','id')
            ->selectRaw("id, first_name, last_name, company_group_id")
            ;
    }


    public function categories(){
        return $this->belongsToMany('App\Models\Category','company_group_category','company_group_id','category_id')
            ;
    }
}
