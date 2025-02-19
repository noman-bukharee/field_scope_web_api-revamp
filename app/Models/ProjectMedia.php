<?php

namespace App\Models;

use App\Events\ProjectMediaTagUpdated;
use App\Events\ProjectMediaUpdated;
use App\Events\ProjectUpdated;
use App\Traits\GeneralModelTrait;
use App\Traits\TimezoneTrait;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;


class ProjectMedia extends Model
{
    protected $table = "project_media";
    protected $fillable = ['project_id', 'target_type', 'target_id', 'path', 'note', 'created_at','media_type','ref_id'];

    use SoftDeletes, GeneralModelTrait, TimezoneTrait;

    public static function createBulk($project_id, $category_id, $note, $media_type, $data)
    {
        $qry_params = [];
        $media_type_map['image'] = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
        $media_type_map['pdf'] = ['pdf'];

        foreach ($data as $column => $media) {
            $ext = explode('.', $media)[1];

            foreach ($media_type_map as $ext_key => $ext_types) {
                if (in_array($ext, $ext_types))
                    $media_type = $ext_key;
            }

            $qry_params[] = "($project_id, '$category_id', '$media_type', '$media','$note', NOW()) ";
        }

        \DB::statement('INSERT INTO project_media (project_id, category_id, media_type,path, note, created_at) VALUES ' . implode(', ', $qry_params) . "");

        return true;

    }

//    public static function createUniqueMedia($path,$uId, $categoryId = 0,$note = ""){
//
//        $insert = [];
//        $insert['project_id']  = $uId;
//        $insert['category_id']  = $categoryId;
//        $insert['ref_id']  = $uId;
//        $insert['path'] =  $path;
//        $insert['note'] = $note;
//        $insert['created_at']  = date('Y-m-d H:i:s');
//        $insert['media_type']   = 'image';
//        return self::insertGetId($insert);
//    }

    public static function createOrUpdateMedia($path, $refId, $targetType, $targetId = 0){

        $where = [];
        $where['ref_id'] = $refId;
        $where['target_type'] = $targetType;
        $where['target_id'] = $targetId;

        $update = [];
        $update['project_id']  = 0;
        $update['ref_id']  = $refId;
        $update['path'] =  $path;


        $update['media_type']   = 'image';
        return self::updateOrCreate($where,$update);
    }

    public static function updateCategoryId_AndMediaTags($media, $projectId = NULL)
    {
        $mediaRefIds = array_column($media, 'ref_id');

        $hasProjectUpdatedEventFired = false;
        foreach ($media as $key => $item) {

            if(empty($projectId) && empty($item['project_id'])){
                throw new \Exception("Can't find a project id with media object");
            }

            $projectId = $projectId ?: $item['project_id'];

            $update = [
                'project_id' => $projectId ?: $item['project_id'],
                'target_id' => $item['category_id'],
                'target_type' => 'category',
                'note' => $item['note'],
                'ref_id' => $item['ref_id']
            ];

            $projectMedia = self::firstOrNew(['id' => $item['id']],$update);

            if ($projectMedia->exists) {
                // id is given AND model exists > UPDATE
                $projectMedia->fill($update);
            } else if (!$projectMedia->exists) {
                // id is given AND model not exist
                throw new \Exception("Can't find the image id");
            }

            $projectMedia->save(); // Creating & Updating Both

//            \Log::debug(
//                "Updated: " . print_r([
//                  'wasChanged' => $projectMedia->wasChanged(),
//                  'wasRecentlyCreated' => $projectMedia->wasRecentlyCreated,
//              ], 1));

            if (($projectMedia->wasChanged() || $projectMedia->wasRecentlyCreated) && !$hasProjectUpdatedEventFired) {
                event(new ProjectMediaUpdated($projectMedia));
                $hasProjectUpdatedEventFired = true;
            }


            /**
             *  Project Media Tag Starts
             */


            $requestedPmTags = collect($item['tags'])->keyBy('id')->map(function ($el){
                return ['qty' => $el['quantity']];
            });


            // btm = belongsToMany
            $pMTag = $projectMedia->btm_Tags()->syncWithoutDetaching($requestedPmTags);
            /** syncWithoutDetaching will only add or update the data but won't detach any of the missing entries
             *  like sync does.
             */


//            $relatedPmTags = $projectMedia->btm_Tags;
//
//            /** $requestedPmTags = data from request , $relatedPmTags data from db
//             *  Below block is to determine if qty columns of project_media_tag have changed
//             *  IF YES: need to fire the event
//             */
//            $diffCount = $requestedPmTags->map(function ($el){ return $el['qty']; })
//                ->diffAssoc(
//                    collect($relatedPmTags)->keyBy('id')
//                        ->map(function ($el) { return $el->pivot->getAttributes()['qty']; })
//                )->count();
//
//
//            \Log::debug(
//                "ProjectMediaTags syncWithoutDetaching" . print_r(
//                    [
//                        'pMTag' => $pMTag,
//                        'request' => $requestedPmTags->map(function ($el){
//                            return $el['qty'];
//                        }),
//                        'related' => collect($relatedPmTags)->keyBy('id')
//                            ->map(function ($el) { return $el->pivot->getAttributes()['qty']; }) ,
//                        'diffCount' => $diffCount,
//
//                    ],1)
//            );
//
//            \DB::commit();
//            die('end');


            if ((count($pMTag['attached']) || count($pMTag['detached']) || count($pMTag['updated']) ) &&
                !$hasProjectUpdatedEventFired) {

                $pMTagCollapsed =  array_collapse($pMTag);

                $evParam = ProjectMediaTag::where(['project_media_tag.target_id' => $item['id'],
                                                      'project_media_tag.target_type' => 'media',
                                                      'project_media_tag.tag_id' => $pMTagCollapsed[0]])
                    ->join('project_media AS pm','pm.id','project_media_tag.target_id')->first();

//                dd($evParam);
                event(new ProjectMediaTagUpdated($evParam));
                $hasProjectUpdatedEventFired = true;
            }


            //<editor-fold desc="Deleted Tags Block">
            /** We can use detach() instead of ->delete() but it wont softDelete*/
            if (!empty($item['deleted_tags'])) {
                $mediaWithTrashed = self::withTrashed()->where(['id' => $item['id']])->first();

                if (count(((array) $mediaWithTrashed)) > 0) {
                    ProjectMediaTag::whereIn('tag_id', $item['deleted_tags'])
                        ->where(['target_id' => $mediaWithTrashed['id'], 'target_type' => 'media'])->delete();
                } else {
                    \Log::warning("Can't find the deleted_tags in ProjectMediaTag ");
                }
            }
            //</editor-fold>

            if (!empty($projectMedia['id'])) {

                if(!empty($item['tags'])){
                    $tagsIds = array_column($item['tags'], 'id');
                    $tags = Tag::whereIn('id', $tagsIds)->count();

                    if ($tags < 1) {
                        //<editor-fold desc="Create New Tags for additional photos">
                        foreach ($item['tags'] AS $tagKey => $tagItem) {
                            $tagFields['created_at'] = date('Y-m-d H:i:s');

                            $tagFields['company_id'] = $tagItem['company_id'];
                            $tagFields['ref_id'] = $tagItem['ref_id'];
                            $tagFields['ref_type'] = $tagItem['ref_type'];
                            $tagFields['name'] = $tagItem['name'];
                            $tagFields['has_qty'] = $tagItem['has_qty'];
                            $item['tags'][$tagKey]['id'] = Tag::insertTag($tagFields);
                        }
                        //</editor-fold>
                    }

//                    $pMediaTag = ProjectMediaTag::createRecords($projectMedia['id'], $item['tags']);
                }else{
                    \Log::warning("Unable to find tags in request.");
                }


            } else if (!empty($item['tags']) && empty($projectMedia['id'])) {

                $res['error_data'] = $item;
                $res['error'] = 'Unable to find Media for tags creation';
                \Log::warning("Unable to find Media for tags creation",$item);
                //return $res;
            } else {
                $res['error_data'] = $item;
                $res['error'] = 'Unable to find Media';
                \Log::warning("Unable to find Media for tags creation",$item);
                return $res;
            }

        } // End foreach


        return self::with(['tags'])->whereIn('ref_id', $mediaRefIds)->get();
    }

    public static function getByProjectId($project_id)
    {
        $query = self::select();
        return $query->where('project_id', $project_id)
            ->get();
    }

    public static function getAllBy($where)
    {
        $query = self::select();
        $mediaPath = Config::get('constants.MEDIA_IMAGE_PATH');
        $query->selectRaw("CONCAT('$mediaPath',path) image_path");
        return $query->where($where)
            ->get();
    }

    public static function getById($id,array $with = [])
    {
        $query = self::select();

        if(!empty($with)){
            $query->with($with);
        }
        return $query->where('id', $id)
            ->first();
    }

    public static function getMediaForCategories($categories,$projectId)
    {

        $where['project_id'] = $projectId;
        
        foreach ($categories AS $key => $item) {
            $where['target_id'] = $item['id'];
            $where['target_type'] = "category";

            $projectMedia = self::getAllBy($where);

            $categories[$key]['media'] = !empty($projectMedia->toArray()) ? $projectMedia->toArray() : [];

            if (!empty($projectMedia)) {
                $categories[$key]['media_count'] += count(((array) $projectMedia->toArray()));
            }

            if (count(((array) $item['get_child'])) > 0) {
                foreach ($item['get_child'] AS $keyChild => $itemChild) {
                    $projectMedia = [];
                    $where['target_id'] = $itemChild['id'];

                    $projectMedia = self::getAllBy($where);
                    $categories[$key]['get_child'][$keyChild]['media'] = NULL;
                    $categories[$key]['get_child'][$keyChild]['media'] = !empty($projectMedia) ? $projectMedia->toArray() : NULL;
                    if (!empty($projectMedia)) {
                        $categories[$key]['media_count'] += count(((array) $projectMedia->toArray()));
                    }
                }
            }
        }
        
        return $categories;
    }

    public static function checkProjectRequiredMedia($categories, $projectId)
    {
        $categoryIds = array_column( $categories , 'id');
        $catIm = implode(',',$categoryIds);
        $sql = "SELECT pm.*,c.`name`,c.`min_quantity`, IF( media_count = min_quantity , TRUE , FALSE) AS match_result
                    FROM (
                                SELECT
                                  category_id,
                                  COUNT(id) AS media_count
                                FROM
                                  `project_media`  
                                WHERE `project_id` = $projectId
                                  AND `category_id` IN ($catIm)
                                GROUP BY `category_id`
                    ) pm
                    LEFT JOIN category c ON c.id = pm.category_id ";
//        echo $sql;
        $query = \DB::select($sql);
        return $query;
    }

    public static function addImageText ($param = []){

        $category = Category::getById($param['category_id']);
        $user = User::getById($param['user_id']);

        $fontConfig = [
            'path' => public_path('assets/fonts/report-font/Axiforma/FontsFree-Net-Axiforma2woff2.ttf'),
            'color' => '#464648',
            'size' => 10,
            'angle' => 0,
        ];

        $imagePath = public_path(config('constants.MEDIA_IMAGE_PATH') . $param['image_path']);

        $img = Image::make($imagePath);
        $img->resize(1080, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $orgHeight = $img->height();
        $prevWidth = $img->width();

        if($param['mode'] == 'update'){

        }else{
            $img->resizeCanvas(0, 80, 'top-left', true,'#E6E6E6');
        }

        $colOneTextX = 10;
        $textBr = 15;
        $colOneTextY = $orgHeight ;
        $colOneTextY = $colOneTextY + $textBr;

        //<editor-fold desc="Column 01">

        /*Col 1 Pt 1*/
        $img->text('Area: '.$category['name'], $colOneTextX , $colOneTextY ,function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);;
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col 1 Pt 2*/
        $nameImploded = implode(', ',array_column($param['tags'],'name'));
        $nameImploded = strlen($nameImploded) > 20 ? substr($nameImploded,0,20)."..." : $nameImploded;
        $colOneTextY = $colOneTextY + $textBr;
        $img->text('Photo Tag: '.$nameImploded, $colOneTextX , $colOneTextY ,function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);;
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col 1 Pt 3*/
        $qtyImploded = '';
        foreach ($param['tags'] as $tag) {
            /*Not imploded cuz 'quantity' is optional key */
            if(isset($tag['quantity']) ){
                $qtyImploded .= $tag['quantity'].', ';
            }else{
                $qtyImploded .= 'N.A, ';
            }
        }
        $qtyImploded = strlen($qtyImploded) > 20 ? substr($qtyImploded,0,20)."..." : $qtyImploded;
        $colOneTextY = $colOneTextY + $textBr;
        $img->text('Qty: '.$qtyImploded, $colOneTextX , $colOneTextY ,function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        $colOneTextY = $colOneTextY + $textBr;
        $img->text('Annotation: '.$param['note'], $colOneTextX , $colOneTextY ,function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });


        /*Col 1 Pt 4*/
        $colOneTextY = $colOneTextY + $textBr;
        $img->text('Powered By: Field Scope', $colOneTextX , (int)($colOneTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });
        //</editor-fold>


        //<editor-fold desc="Column 02">
        $colTwoTextX = ($prevWidth/2) - $colOneTextX;
        $colTwoTextY = $orgHeight ;

        /*Col2 Pt 1*/
        $img->text('Location Verified: Lat: '.$param['latitude'].',', $colTwoTextX , (int)($colTwoTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col2 Pt 1*/
        $colTwoTextY = $colTwoTextY + $textBr;
        $img->text('Long: '.$param['longitude'], $colTwoTextX , (int)($colTwoTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col2 Pt 2*/
        $colTwoTextY = $colTwoTextY + $textBr;
        $img->text('Inspector: '.$user['first_name'].' '.$user['last_name'], $colTwoTextX , (int)($colTwoTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col2 Pt 3*/
        $colTwoTextY = $colTwoTextY + $textBr;
        $img->text('Claim #: '.$param['claim_no'], $colTwoTextX , (int)($colTwoTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col2 Pt 4*/
        $param['inspection_date'] = date('Y-m-d', strtotime($param['inspection_date']));
        $colTwoTextY = $colTwoTextY + $textBr;
        $img->text('Inspection Date: '.$param['inspection_date'], $colTwoTextX , (int)($colTwoTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });
        //</editor-fold>

        $img->save($imagePath);
        return true;
    }

    public static function addImageText_2 ($param = []){

        /** Docmentation:
         * public Intervention\Image\Image resizeCanvas (int $width, int $height, [string $anchor, [boolean $relative, [mixed $bgcolor]]])
         * public Intervention\Image\Image rectangle(int $x1, int $y1, int $x2, int $y2, [Closure $callback])
         */

        $category = Category::getById($param['category_id']);
        $user = User::getById($param['user_id']);

        $fontConfig = [
            'path' => public_path('assets/fonts/report-font/Axiforma/Kastelov - Axiforma SemiBold.otf'),
            'color' => '#464648',
            'size' => 13,
            'angle' => 0,
        ];

        $imagePath = public_path(config('constants.MEDIA_IMAGE_PATH') . $param['image_path']);

        $img = Image::make($imagePath);

        if($param['mode'] != 'update'){
            $img->resize(1080, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $orgHeight = $img->height();
        $prevWidth = $img->width();


        /** Configs  */
        $detailCanvasHeight = 165;
        $tCols = 2;
        $textBr = 25;
        $footerHeight = 20;

        $colOneTextX = 10;
        $colOneTextY = $orgHeight + $textBr;

        $colTwoTextX = ($prevWidth/$tCols);
        $colTwoTextY = $orgHeight  + $textBr;

        if($param['mode'] == 'update'){
            /** When blue-ribbon / watermark is already a part of image*/

            $blueRectY1 = ($orgHeight - ($detailCanvasHeight+$footerHeight));
            $blueRectY2 = ($orgHeight - ($footerHeight));

            // +2 is for removing "top white line"
            $colOneTextY = ($orgHeight - ($detailCanvasHeight+$footerHeight)) + $textBr;
            $colTwoTextY = ($orgHeight - ($detailCanvasHeight+$footerHeight)) + $textBr;

            $footerY =

            // public Intervention\Image\Image rectangle(int $x1, int $y1, int $x2, int $y2, [Closure $callback])
            // draw a blue rectangle
            $img->rectangle(0, $blueRectY1, $prevWidth, $blueRectY2, function ($draw) {
                $draw->background('#E6E6E6');
                //#d5db14
            });
        }else{
            /** When blue-ribbon / watermark isn't already a part of image*/
            //adding top white line
            $img->resizeCanvas(0, 2, 'top-left', true, '#ffffff');

            // adding top white line
            $img->resizeCanvas(0, $detailCanvasHeight, 'top-left', true,'#E6E6E6');
        }



        //<editor-fold desc="Col 1">
        /*Col 1 Pt 1*/
        $img->text('Area: '.$category['name'], $colOneTextX , $colOneTextY ,function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);;
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col 1 Pt 2*/
        $colOneTextY = $colOneTextY + $textBr;
        $img->text('Inspector: '.$user['first_name'].' '.$user['last_name'], $colOneTextX , $colOneTextY ,function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);;
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col 1 Pt 3*/
        $colOneTextY = $colOneTextY + $textBr;
        $img->text('Claim #: '.$param['claim_no'], $colOneTextX , $colOneTextY ,function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });


        // draw horizontal line (rectangle)
        $img->rectangle(10, $colOneTextY+7, $prevWidth-10, $colOneTextY+7+1, function ($draw) {
            $draw->background('#ffffff');
        });

        /*Col 1 Pt 4*/
        $colOneTextY = $colOneTextY + $textBr;

        $nameImploded = implode(', ',array_column($param['tags'],'name'));
        $nameImploded = strlen($nameImploded) > 40 ? substr($nameImploded,0,40)."..." : $nameImploded;
//        $nameImploded = "100 char string Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenassean dolor. Aenea end";
        $img->text('Photo Tag: '.$nameImploded, $colOneTextX , (int)($colOneTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col 1 Pt 5*/
        $colOneTextY = $colOneTextY + $textBr;

        $qtyImploded = '';
        foreach ($param['tags'] as $tag) {
            /** Not imploded cuz 'quantity' is optional key */
            if(isset($tag['quantity']) ){
                $qtyImploded .= $tag['quantity'].', ';
            }else{
                $qtyImploded .= 'N.A, ';
            }
        }
        $qtyImploded = strlen($qtyImploded) > 40 ? substr($qtyImploded,0,40)."..." : $qtyImploded;
        $img->text('Qty: '.$qtyImploded , $colOneTextX , (int)($colOneTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col 1 Pt 6*/
        $colOneTextY = $colOneTextY + $textBr;
        $img->text('Annotation: '.$param['note'], $colOneTextX , $colOneTextY ,function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });
        //</editor-fold>

        //<editor-fold desc="Col 2">

        /*Col 2 Pt 1*/

        // draw vertical line (rectangle)
        $img->rectangle($colTwoTextX-10, $colTwoTextY-15, $colTwoTextX-9, $colTwoTextY+50, function ($draw) {
            $draw->background('#ffffff');
        });

        /*Col 2 Pt 1*/
        $img->text('Location Verified: Lat: '.$param['latitude'].',', $colTwoTextX , (int)($colTwoTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col 2 Pt 2*/
        $colTwoTextY = $colTwoTextY + $textBr;
        $img->text('Long: '.$param['longitude'], $colTwoTextX , (int)($colTwoTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col 2 Pt 3*/
        $param['inspection_date'] = date('Y-m-d', strtotime($param['inspection_date']));

        $colTwoTextY = $colTwoTextY + $textBr;
        $img->text('Inspection Date: '.$param['inspection_date'], $colTwoTextX , (int)($colTwoTextY),function($font) use($fontConfig) {
            $font->file($fontConfig['path']);
            $font->size($fontConfig['size']);
            $font->color($fontConfig['color']);
            /*$font->align('center');
            $font->valign('top');*/
            $font->angle($fontConfig['angle']);
        });

        /*Col 2 Pt 4*/
//        $colTwoTextY = $colTwoTextY + $textBr;


        /*Col 2 Pt 5*/
//        $colTwoTextY = $colTwoTextY + $textBr;

        //</editor-fold>

        if($param['mode'] != 'update'){
            //<editor-fold desc="Footer ">
            $img->resizeCanvas(0, $footerHeight, 'top-left', true,'#ffffff');

            $fontConfig = [
                'path' => public_path('assets/fonts/report-font/Axiforma/Kastelov - Axiforma SemiBold.otf'),
                'color' =>   '#E6E6E6', // '#ff0000',
                'size' => 10,
                'angle' => 0,
            ];

            $colOneTextY = $orgHeight + $detailCanvasHeight + 15;
            $footerY =
                $img->text('Powered By: Field Scope', $colOneTextX , (int)($colOneTextY),function($font) use($fontConfig) {
                    $font->file($fontConfig['path']);
                    $font->size($fontConfig['size']);
                    $font->color($fontConfig['color']);
                    /*$font->align('center');
                    $font->valign('top');*/
                    $font->angle($fontConfig['angle']);
                });
            //</editor-fold>
        }


        $img->save($imagePath);
        return true;
    }
    //Noman
//     public static function addImageText_3($param = [])
//     {

//         /** Docmentation:
//          * public Intervention\Image\Image resizeCanvas (int $width, int $height, [string $anchor, [boolean $relative, [mixed $bgcolor]]])
//          * public Intervention\Image\Image rectangle(int $x1, int $y1, int $x2, int $y2, [Closure $callback])
//          */

//         $fontConfig = [
//             'path' => public_path('assets/fonts/report-font/Axiforma/Kastelov - Axiforma SemiBold.otf'),
//             'color' => '#464648',
//             'size' => 11,
//             'angle' => 0,
//         ];

//         $imagePath = public_path(config('constants.MEDIA_IMAGE_PATH') . $param['imageName']);

//         // For testing
//         // 9:16+aspect+ratio-white.png 9:16+aspect+ratio-black.png
//         // $imagePath = public_path(config('constants.MEDIA_IMAGE_PATH') . '9:16+aspect+ratio-black.png');
//         // $resultImagePath = public_path(config('constants.MEDIA_IMAGE_PATH') . 'water-result.jpg');

//         $img = Image::make($param['image_url']);

//         $exif = $img->exif();

//         \Log::debug("@addImageText_3: ".print_r([
//                 'imageName'=> $param['imageName'],
//                 'exif'=> $exif,

//         ],1));

// //        $img->resize(1080, null, function ($constraint) {
// //            $constraint->aspectRatio();
// //            $constraint->upsize();
// //        });

//         $locationIcon = Image::make(public_path('image/inspection-area-icon-dark.png'));
//         $locationIcon->resize(9, null, function ($constraint) {
//             $constraint->aspectRatio();
//             $constraint->upsize();
//         });

//         $calendarIcon = Image::make(public_path('image/calender-icon.png'));
//         $calendarIcon->resize(9, null, function ($constraint) {
//             $constraint->aspectRatio();
//             $constraint->upsize();
//         });

//         //<editor-fold desc="First Field">
//         $img->rectangle(12, 15, 185, 33, function ($draw) {
//             $draw->background('rgba(255, 255, 255, 0.5)');
// //            $draw->border(1, '#424242');
//         });

//         // insert watermark at bottom-right corner with 10px offset
//         $img->insert($locationIcon, 'top-left', 20, 18);

//         $param['lat'] = number_format($param['lat'],7);
//         $param['long'] = number_format($param['long'],7);
//         $img->text("{$param['lat']}, {$param['long']}", 34, 29, function ($font) use ($fontConfig) {
//             $font->file($fontConfig['path']);
//             $font->size($fontConfig['size']);;
//             $font->color($fontConfig['color']);
//             /*$font->align('center');
//             $font->valign('top');*/
//             $font->angle($fontConfig['angle']);
//         });
//         //</editor-fold>


//         //<editor-fold desc="Second Field">
//         $secondFieldY = 30;
//         $img->rectangle(12, $secondFieldY + 15, 168, $secondFieldY + 33, function ($draw) {
//             $draw->background('rgba(255, 255, 255, 0.5)');
// //            $draw->border(1, '#424242');
//         });

//         // insert watermark at bottom-right corner with 10px offset
//         $img->insert($calendarIcon, 'top-left', 20, $secondFieldY + 19);

//         $img->text($param['date'], 34, $secondFieldY + 29, function ($font) use ($fontConfig) {
//             $font->file($fontConfig['path']);
//             $font->size($fontConfig['size']);;
//             $font->color($fontConfig['color']);
//             /*$font->align('center');
//             $font->valign('top');*/
//             $font->angle($fontConfig['angle']);
//         });
//         //</editor-fold>

//         $img->save($imagePath);
//         return true;
//     }
public static function addImageText_3($param = [])
{
    $fontConfig = [
        'path' => public_path('assets/fonts/report-font/Axiforma/Kastelov - Axiforma SemiBold.otf'),
        'color' => '#464648',
        'size' => 16,
        'angle' => 0,
    ];

    $imagePath = public_path(config('constants.MEDIA_IMAGE_PATH') . $param['imageName']);
    $img = Image::make($param['image_url']);

    // Location icon
    $locationIcon = Image::make(public_path('image/inspection-area-icon-dark.png'));
    $locationIcon->resize(9, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });

    // Calendar icon
    $calendarIcon = Image::make(public_path('image/calender-icon.png'));
    $calendarIcon->resize(9, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });

    // Set top margin
    $topMargin = 140;
    
    // Define new width by increasing the right coordinate
    $newRight = 185 + 50; // Increase the width by 50 units (adjust as needed)

    // First field (latitude and longitude)
    $img->rectangle(12, $topMargin + 15, $newRight, $topMargin + 33, function ($draw) {
        $draw->background('rgba(255, 255, 255, 1)');
    });

    $img->insert($locationIcon, 'top-left', 20, $topMargin + 18);
    $param['lat'] = number_format($param['lat'],6);
    $param['long'] = number_format($param['long'],6);
    $img->text("{$param['lat']}, {$param['long']}", 34, $topMargin + 29, function ($font) use ($fontConfig) {
        $font->file($fontConfig['path']);
        $font->size($fontConfig['size']);
        $font->color($fontConfig['color']);
        $font->angle($fontConfig['angle']);
    });

    // Second field (date)
    $secondFieldY = $topMargin + 30;
    // Define new width by increasing the right coordinate
    $newRights = 168 + 50; // Increase the width by 50 units (adjust as needed)
    
    $img->rectangle(12, $secondFieldY + 15, $newRights, $secondFieldY + 33, function ($draw) {
        $draw->background('rgba(255, 255, 255, 1)');
    });

    $img->insert($calendarIcon, 'top-left', 20, $secondFieldY + 19);
    $img->text($param['date'], 34, $secondFieldY + 29, function ($font) use ($fontConfig) {
        $font->file($fontConfig['path']);
        $font->size($fontConfig['size']);
        $font->color($fontConfig['color']);
        $font->angle($fontConfig['angle']);
    });

    // Save the modified image
    $img->save($imagePath);

    return true;
}







    public static function getLatestPhotos($params)
    {
        $output = [];
        parse_str($params['custom_search'], $output);
        
        $params['project_ids'] = !empty($params['project_ids']) ? [$params['project_ids']] : [];
        $params['user_ids'] = !empty($params['user_ids']) ? [$params['user_ids']] : [];
        $params['tag_ids'] = !empty($params['tag_ids']) ? [$params['tag_ids']] : [];

        

        $q = self::select()->with(['media_tags']);

        $pmCols = self::customColumn(
            '0',
            ['id', 'project_id', 'category_id', 'path', 'created_at', 'updated_at', 'note']
        );
        $uCols = User::customColumn(1, ['first_name', 'last_name', 'email', 'image_url'],'u');
        $pCols = Project::customColumn(1, ['id','name','user_id','assigned_user_id', 'created_at'], 'p');
        $q->select(array_merge($pmCols,$uCols,$pCols));
        $q->join('project','project.id','=','project_id');
        $q->join('user','user.id','=','project.user_id');
        $q->where(['project.company_id' => $params['company_id']]);


//        $params['project_ids'] = ['159'];
//        $params['user_ids'] = 0;
//        $params['tag_ids'] = [1];
//        $params['date'] = '09-Jun-20';


        //region Filters Block
        if(!empty($params['project_ids']) AND is_array($params['project_ids']))
          $q->whereIn('project.id',$params['project_ids']);

        if(!empty($params['user_ids']) AND is_array($params['user_ids']))
          $q->whereIn('project.user_id',$params['user_ids']);

        if (!empty($params['tag_ids']) AND is_array($params['tag_ids'])) {
            $q->whereHas('media_tags', function ($q) use ($params) {
                $q->whereIn('project_media_tag.tag_id', $params['tag_ids']);
            });
        }

        if (!empty($params['date'])) {
            $date = $params['date'];
            $q->whereRaw("DATE(project_media.created_at) = '$date'");
        }
        //endregion

        $q->orderBy('project_media.created_at', 'DESC');
        return $q->paginate(400);
    }

    public static function updateTagsQtyWithHover($hoverFields, $projectId,$overwriteAll = false){

        $projectMedia = self::where(['project_id' => $projectId])->with("btm_Tags")->get();

        $updated = false;
        foreach ($projectMedia as $mediaItem) {
            $hoverFields = collect($hoverFields)->keyBy('id');

            // could have been used as alternative to pivot->save(); but needed testing
            // $projectMedia->btm_Tags()->syncWithoutDetaching($requestedPmTags);
            $mediaItem->btm_Tags->map(function ($el)use($hoverFields,$overwriteAll,&$updated){
                $value = !empty($hoverFields[$el->hover_field_id]['hover_value']) ?
                    $hoverFields[$el->hover_field_id]['hover_value'] :
                    $hoverFields[$el->hover_field_id]['value'];

                if (!empty($overwriteAll) && !empty($value)) {
                    $el->pivot->qty = $value; // overwriteAll
                } else if (!empty($value)) {
                    $el->pivot->qty = $el->pivot->qty ?: $value; // update only if its currently empty
                }

                if($el->pivot->isDirty()){
                    $el->pivot->save();
                    $updated = true;
                }
            });
        }
        return $updated;
    }

    //<editor-fold desc="Relationships">
    /*using at around 100+ places (remove tags_data and use media_tags)*/
    public function media_tags(){
        return $this->hasMany('App\Models\ProjectMediaTag','target_id' , 'id')
            ->join('tag AS t','t.id','=','project_media_tag.tag_id')
            ->selectRaw('
            t.id,
            t.name,
            t.has_qty,
            t.annotation,
            t.price,
            t.uom_id,
            t.material_cost,
            t.labor_cost,
            t.equipment_cost,
            t.supervision_cost,
            t.margin,
            project_media_tag.qty,
            project_media_tag.target_id')
            ->where('target_type', 'media');
    }
    /*using at around 20 places (replace this with media_tags)*/
    public function tags_data(){
        return $this->hasMany('App\Models\ProjectMediaTag','target_id' , 'id')
            ->join('tag AS t','t.id','=','project_media_tag.tag_id')
            ->selectRaw('
            project_media_tag.tag_id,
            project_media_tag.qty,
            project_media_tag.target_id,            
            t.name,
            t.spec_type,
            t.build_spec')
            ->where('project_media_tag.target_type', 'media');
    }

    /** this relationship binds with ref_id which is received from app */
    public function media_tags_extended(){
        return $this->hasMany('App\Models\ProjectMediaTag','target_id' , 'ref_id')
            ->where('target_type', 'media');
    }

    /*alias function*/
    public function tags(){
        return $this->media_tags();
    }

    public function category(){
        return $this->belongsTo('App\Models\Category' ,'target_id','id')
            ->selectRaw("id, name, company_id, type, parent_id, min_quantity, thumbnail");
    }

    /** Parent Model <--> Polymorphic  <--> Related
     * ProjectQuery <--> ProjectMedia <--> Query
     *                       ^^
     * This is only being used for ProjectQuery's Media for type sign
     */
    public function target()
    {
        return $this->morphTo();
    }

    /** Only being used to insert & update via attach() & sync()
     * Also, tags & media_tags relations are pointing to same model (App\Models\Tag) with variation.
     * Ideally, tags, media_tags & btm_Tags should be unified as one
     *  btm stands for belongsToMany
     */
    public function btm_Tags()
    {
        return $this->belongsToMany("App\Models\Tag",'project_media_tag','target_id',
                                    'tag_id','id','id')->withPivot('qty')
//            ->withTimestamps()
            ->using('App\Models\ProjectMediaTag');
        // withTimestamps is commented because with this, syncWithoutDetaching always return tag_id even when the qty isn't being changed at app/Models/ProjectMedia.php:135

         /** belongsToMany is a connection between 3 tables media <----> media_tag (pivot) <----> tag
          *  $related = child model
          *  $table = pivot table (media_tag)
          *  $foreignPivotKey = baseModel's key in pivot table (target_id from media_tag)
          *  $parentKey = baseModel's primary key that will be inserted in pivot (id from media)
          *  $relatedKey = relatedModel's primary key will be inserted in pivot (id from tag)
          */
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo('App\Models\Project', 'project_id', 'id');
    }
    //</editor-fold>
}
