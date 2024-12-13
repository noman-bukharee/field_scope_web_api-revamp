<?php

namespace App\Http\Controllers;

use App\Http\Middleware\LoginAuth;
use App\Libraries\Sign\HelloSign;
use App\Libraries\Sign\SignNow;
use App\Models\Category;
use App\Models\CompanyReport;
use App\Models\Project;
use App\Models\Query;
use App\Models\Report;
use App\Models\ReportTemplate;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Mpdf\MpdfException;

class ReportController extends Controller
{

    private $templatesPath = NULL,$report,$companyDetails,$userDetails,$projectDetails,$companyTemplates,$totalPages;
    private $mpdf = NULL;

    private $optionsRequest = '{"Introduction":[{"id":2,"title":"test","identifier":"introduction","selected":"false"},{"id":6,"title":"test 2","identifier":"introduction","selected":"true"}],"Credit_Disclaimer":true,"Owner_Authorization":true,"Terms_Conditions":true,"Documents":[{"id":3,"title":"test","identifier":"documents","selected":"true"}],"categories":[{"id":7,"name":"Additional Photos","selected":true,"Estimations":true},{"id":8,"name":"Inspection Area","selected":true,"Estimations":true},{"id":12,"name":"Roof Inspection","selected":true,"Estimations":true},{"id":14,"name":"Front Elevation","selected":true,"Estimations":true},{"id":54,"name":"New Area","selected":true,"Estimations":true}],"breakdown":{"Units_Of_Measure":true,"Material_Cost":true,"Labor_Cost":true,"Equipment_Cost":true,"Supervision_Cost":true,"Margin_%":true,"Sales_Tax":true,"Line_Item_Total":true}}';
    private $ownerAuthorization = [], $breakdown, $photoSelection, $estimates;
    function __construct()
    {
        // ini_set('max_execution_time','200');
        parent::__construct();
        $this->middleware(LoginAuth::class, ['only' => [
            'store',
            'index',
            'show',
            'edit',
            'update',
            'getReportOptions',
            'createReport'
        ]
        ]);
        $this->ownerAuthorization = collect([]);
        $this->templatesPath = config("constants.REPORT_TEMPLATE_FILE_PATH");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request['keyword'] = isset($request['keyword']) ? $request['keyword'] : NULL;
        $list = Tag::getList($request->all());

        $this->__is_paginate = true;
        $this->__is_collection = true;
        return $this->__sendResponse('Tag', $list, 200, 'Tag list retrieved successfully.');
    }

    /** v2 legacy method */
    public function getReportOptions(Request $request, $projectId)
    {

        //<editor-fold desc="Validation">
        $param_rules['project_id'] = [
            'required',
            'int',
            Rule::exists('project', 'id')
                ->where('company_id',$request['company_id'])
                ->whereNull('deleted_at')
        ];
        $request['project_id'] = $projectId;
        $response = $this->__validateRequestParams($request->all(),$param_rules);

        if($this->__is_error == true)
            return $response;

        //</editor-fold>

        $report = Report::where(['project_id' => $projectId])->first(['id','options']);
        $lastSelectedOptions = [];

        if($report){
            $lastSelectedOptions = collect(json_decode($report->options));
        }

        $options = [
            'Introduction' => true,
            'Credit_Disclaimer' => true,
            'Owner_Authorization' => $lastSelectedOptions['Owner_Authorization'] ?: true,
            'Terms_Conditions' => $lastSelectedOptions['Terms_Conditions'] ?: true,
            'Documents' => true,
        ];

        $companyReportM = new CompanyReport();
        $companyReport = $companyReportM->where(['company_id' => $request['company_id']])->first(['is_disclaimer','json_data']);

        $ownAuthorization = json_decode($companyReport->json_data,true);

        $options['owner_authorization']['section_title'] = "Optional Upgrades";
        $options['owner_authorization']['item_title'] = "Optional Upgrades";

        $options['owner_authorization']['section_items'] = collect($ownAuthorization['section_item']['item'])->map(function ($el,$index) use($lastSelectedOptions){
            $matchedOption = collect($lastSelectedOptions['owner_authorization']->section_items)->where('id',$index+1)->first();
            return ['id' =>$index+1 , 'name' => $el,
                'selected' => $matchedOption->selected ?: false
            ];
        });

        $options['owner_authorization']['item_options'] = collect($ownAuthorization['item_option'])->map(function ($el,$index) use($lastSelectedOptions) {
            $matchedOption = collect($lastSelectedOptions['owner_authorization']->item_options)->where('id',$index+1)->first();

            return ['id' =>$index+1 ,'name' => $el,
                'value' => $matchedOption->value ?: '',
            ];
        });

        $options['owner_authorization']['special_instruction'] = $lastSelectedOptions['owner_authorization']->special_instruction ?:null;

        if(!$companyReport->is_disclaimer){
            unset($options['Credit_Disclaimer']);
        }

        $category = new Category();
        $categories = $category->getCompanyGroupCategories($request->all());

        $options['categories'] = $categories->map(function ($item,$key) use($lastSelectedOptions) {

            $matchedCat = collect($lastSelectedOptions['categories'])->where('id',$item->id)->first();

            $result = [
                'id' => $item->id,
                'name' => title_case($item->name),
                'selected' => $matchedCat->selected ?: FALSE,
            ];

            return $result;
        })->toArray();

        $options['Estimates'] = $categories->filter(function ($item, $key) {
            if (!in_array($item->type, [3])) {
                $result = [
                    'id' => $item->id,
                    'name' => title_case($item->name),
                    'selected' => FALSE,
                ];
                return $result;
            }
        })->map(function ($item, $key) use($lastSelectedOptions) {

            $matchedEst = collect($lastSelectedOptions['Estimates'])->where('id',$item->id)->first();
            $result = [
                'id' => $item->id,
                'name' => title_case($item->name." Estimate"),
                'selected' => $matchedEst->selected ?: FALSE ,
            ];
            return $result;

        })->values();

        $options['breakdown'] = [
            'Units_Of_Measure' =>   $lastSelectedOptions['breakdown']->Units_Of_Measure ?: FALSE,
            'Material_Cost' =>      $lastSelectedOptions['breakdown']->Material_Cost ?: FALSE,
            'Labor_Cost' =>         $lastSelectedOptions['breakdown']->Labor_Cost ?: FALSE,
            'Equipment_Cost' =>     $lastSelectedOptions['breakdown']->Equipment_Cost ?: FALSE,
            'Supervision_Cost' =>   $lastSelectedOptions['breakdown']->Supervision_Cost ?: FALSE,
            'Margin_%' =>           $lastSelectedOptions['breakdown']->Margin_ ?: FALSE,
            'Sales_Tax' =>          $lastSelectedOptions['breakdown']->Sales_Tax ?: FALSE,
            'Line_Item_Total' =>    $lastSelectedOptions['breakdown']->Line_Item_Total ?: FALSE,
        ];


        $templates = ReportTemplate::selectRaw("id,title,identifier,'false' AS selected")->where(['company_id' => $request['company_id']])->whereNull('deleted_at')->get();


        $templates = $templates->map(function ($template, $key) use($lastSelectedOptions) {
            $template->selected = !empty($lastSelectedOptions) ? $lastSelectedOptions->only(['Introduction','Documents'])->flatten()->contains('id',$template->id) : FALSE;
            return $template;
        })->groupBy('identifier')->toArray();

        $options['Introduction'] = $templates['introduction'];
        $options['Documents'] = $templates['documents'];

        $this->__is_paginate = false;
        $this->__is_collection = false;
        $this->__collection = false;
        return $this->__sendResponse('Tag', $options, 200, 'Tag list retrieved successfully.');
    }

    public function getReportOptions_v3(Request $request, $projectId)
    {
        //<editor-fold desc="Validation">
        $param_rules['project_id'] = [
            'required',
            'int',
            Rule::exists('project', 'id')
                ->where('company_id', $request['company_id'])
                ->whereNull('deleted_at')
        ];
        $request['project_id'] = $projectId;
        $response = $this->__validateRequestParams($request->all(), $param_rules);

        if ($this->__is_error == true)
            return $response;

        //</editor-fold>

        $report = Report::where(['project_id' => $projectId])->first(['id', 'options']);
        $lastSelectedOptions = collect([]);

        if ($report) {
            $lastSelectedOptions = collect(json_decode($report->options));
        }

        $options = collect();

        $companyReportM = new CompanyReport();
        $companyReport = $companyReportM->where(['company_id' => $request['company_id']])->first(
            ['is_disclaimer', 'json_data']
        );

        $ownAuthorization = json_decode($companyReport->json_data, true);

        $pageBreak = [
            'key' => "page_break",
            'title' => null,
        ];

        $rules = [
            'required' => true,
        ];

        //<editor-fold desc="Photo Selection">
        $category = new Category();
        $categories = $category->getCompanyGroupCategories($request->all());

        $selectedPhotoSelections = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'photo_selections';
        })->first();

        $photoSelectionOptions = $categories->map(function ($item, $key) use ($selectedPhotoSelections) {
            
            //Noman
            
            $matchedCat = collect($selectedPhotoSelections->options)->where('id', $item->id)->first();
            // $selected = $matchedCat ? $matchedCat->selected : false; // Ensure selected is set to false if not found
            $result = [
                'id' => $item->id,
                'title' => title_case($item->name),
                'selected' => $matchedCat->selected ?: FALSE,
                // 'selected' => $selected, // Use the selected value or default to false
            ];
            return $result;
        })->values()->toArray();
        
        $options->push([
                           'key' => "photo_selections",
                           'title' => "Photo Selection",
                           'type' => "checkbox",
                           'rules' => $rules,
                           'options' => $photoSelectionOptions
                       ]);
        //</editor-fold>

        $estimatesAreas = [];
        $estimatesAreas = $categories->filter(function ($item, $key) {
            if (!in_array($item->type, [1,3])) {
                return true;
            }
            return false;
        });

        //<editor-fold desc="Estimates">

        $selectedEstimates = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'estimates';
        })->first();

        $estimateOptions = $estimatesAreas->map(function ($item, $key) use ($selectedEstimates) {

            $matchedEst = collect($selectedEstimates->options)->where('id', $item->id)->first();
            $result = [
                'id' => $item->id,
                'title' => title_case($item->name . " Estimate"),
                'selected' => $matchedEst->selected ?: FALSE,
            ];
            return $result;

        })->values();

//        \Log::debug("".print_r([
//                                   '$estimateOptions' =>$estimateOptions->toArray()
//                               ],1));

        $options->push([
                           'key' => "estimates",
                           'title' => "Estimates",
                           'type' => "checkbox",
                           'dependent' => "photo_selections",
                           'options' => $estimateOptions
                       ]);
        //</editor-fold>

        $options->push($pageBreak);

        //<editor-fold desc="Breakdown">
        $breakdownOptions = collect(
            [
                'units_of_measure' => FALSE,
                'material_cost' => FALSE,
                'labor_cost' => FALSE,
                'equipment_cost' => FALSE,
                'supervision_cost' => FALSE,
                'margin_%' => FALSE,
                'sales_tax' => FALSE,
                'line_item_total' => FALSE,
            ]
        );

        $selectedBreakdowns = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'breakdown';
        })->first();

        $breakdownOptions = $breakdownOptions->map(function ($item, $key) use ($selectedBreakdowns) {
            $matched = collect($selectedBreakdowns->options)->where('key', $key)->first();
            return [
                'id' => $key,
                'title' => title_case(str_replace('_', ' ', $key)),
                'type' => "checkbox",
                'selected' => $matched->selected ?: false
            ];
        })->values();

        $options->push([
                           'key' => "breakdown",
                           'title' => "Report Column Selection",
                           'type' => "checkbox",
                           'options' => $breakdownOptions
                       ]);
        //</editor-fold>

        $options->push($pageBreak);

        //<editor-fold desc="Trade Items">
        $selectedTradeItems = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'trade_items';
        })->first();

        $selectedTradeItems = $estimatesAreas->map(function ($item, $key) use ($selectedTradeItems) {

            $matchedTrade = collect($selectedTradeItems->options)->where('id', $item->id)->first();
            $result = [
                'id' => $item->id,
                'title' => title_case($item->name),
                'selected' => $matchedTrade->selected ?: FALSE,
            ];
            return $result;

        })->values();


        $options->push([
                           'key' => "trade_items",
                           'title' => "Trade Items",
                           'type' => "checkbox",
                           'dependent' => "estimates",
                           'options' => $selectedTradeItems ?: [],
                       ]);

        //</editor-fold>

        //<editor-fold desc="Section Items">

        $selectedSectionItems = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'section_items';
        })->first();

        $sectionItemPrice = $ownAuthorization['section_item']['price'];
        $sectionItems = collect($ownAuthorization['section_item']['item'])->map(
            function ($el, $index) use ($sectionItemPrice, $selectedSectionItems) {
                $matchedOption = collect($selectedSectionItems->options)->where('id', $index + 1)->first();
                return ['id' => $index + 1,
                    'title' => $el,
                    'price' => $sectionItemPrice[$index],
                    'selected' => $matchedOption->selected ?: false,
                    'has_qty' => true,
                    'value' => $matchedOption->value ?: null,
                ];
            }
        );

        if (!empty($sectionItems->toArray())) {
            $options->push([
                               'key' => "section_items",
                               'title' => "Optional Upgrades",
                               'type' => "checkbox",
                               'options' => $sectionItems
                           ]);
        }
        //</editor-fold>

        //<editor-fold desc="Item Options">
        $selectedItemOptions = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'item_options';
        })->first();

        $itemOptions = collect($ownAuthorization['item_option'])->map(
            function ($el, $index) use ($selectedItemOptions) {
                $matchedOption = collect($selectedItemOptions->options)->where('id', $index + 1)->first();
                return ['id' => $index + 1,
                    'title' => $el,
                    'value' => $matchedOption->value ?: '',
                ];
            }
        );

        if (!empty($itemOptions->toArray())) {
            $options->push([
                               'key' => "item_options",
                               'title' => "Options",
                               'type' => "text",
                               'options' => $itemOptions
                           ]);
        }
        //</editor-fold>

        //<editor-fold desc="Special Instruction">
        $selectedSpecialInstruction = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'special_instruction';
        })->first();

        $options->push([
                           'key' => "special_instruction",
                           'title' => "Special Instruction",
                           'type' => "text_area",
                           'value' => $selectedSpecialInstruction->value ?: ""

                       ]);
        //</editor-fold>

        $options->push($pageBreak);

        $templates = ReportTemplate::selectRaw("id,title,identifier,'false' AS selected")->where(
            ['company_id' => $request['company_id']]
        )->whereNull('deleted_at')->get();


        //<editor-fold desc="Introduction">
        $selectedIntroduction = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'introduction';
        })->first();

        $introduction = $templates->filter(function ($item, $key) {
            return $item->identifier == 'introduction';
        });


        if ($introduction->isNotEmpty()) {
            $options->push([
                               'key' => "introduction",
                               'title' => "Introduction",
                               'type' => "toggle",
                               'selected' => $selectedIntroduction->selected ?: false
                           ]);
        }

        //</editor-fold>

        //<editor-fold desc="Documents">
        $selectedDocuments = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'documents';
        })->first();

        $documents = $templates->where('identifier','documents')->values()->map(function ($docItem) use ($selectedDocuments) {
            $matchedDocs = collect($selectedDocuments->options)->where('id', $docItem->id)->first();

            $docItem->selected = $matchedDocs->selected ?: FALSE;
            return $docItem;
        });

        if ($documents->isNotEmpty()) {
            $options->push([
                               'key' => "documents",
                               'title' => "Documents",
                               'type' => "checkbox",
                               'options' => $documents
                           ]);
        }

        //</editor-fold>

        if ($companyReport->is_disclaimer) {
            $selectedDisclaimer = $lastSelectedOptions->filter(function ($item) {
                return $item->key == 'credit_disclaimer';
            })->first();

            $options->push([
                               'key' => "credit_disclaimer",
                               'title' => "Credit Disclaimer",
                               'type' => "toggle",
                               'selected' => $selectedDisclaimer->selected ?: false,
                           ]);
        }


        //<editor-fold desc="Terms Condition">
        $termsCondition = $templates->filter(function ($item, $key) {
            return $key == 'terms_conditions';
        });

        $selectedTerms = $lastSelectedOptions->filter(function ($item) {
            return $item->key == 'terms_conditions';
        })->first();

        if ($termsCondition->isNotEmpty()) {
            $options->push([
                               'key' => "terms_conditions",
                               'title' => "Terms & Conditions",
                               'type' => "toggle",
                               'selected' => $selectedTerms->selected ?: false,
                           ]);
        }
        //</editor-fold>


        return $this->__sendResponse('Tag', $options->toArray(), 200, 'Tag list retrieved successfully.');
    }


    public function createReport(Request $request, $projectId)
    {
//        if (isset($request->request_options['owner_authorization']['section_items']) && isset($request->request_options['owner_authorization']['item_options'])) {
//            $items = [];
//            $qty = [];
//            $price = [];
//            $total = [];
//            $item_options = [];
//            foreach ($request->request_options['owner_authorization']['section_items'] as $selectd_items) {
//                array_push($items,$selectd_items['name']);
//                $getqty = (string)number_format($selectd_items['qty']);
//                $getprice = (string)number_format($selectd_items['price'], 2, '.', '');
//                $gettotal = (string)number_format($selectd_items['total'], 2, '.', '');
//                array_push($qty,$getqty);
//                array_push($price,$getprice);
//                array_push($total,$gettotal);
//            }
//            foreach($request->request_options['owner_authorization']['item_options'] as $item_option) {
//                array_push($item_options,$item_option['name']);
//            }
//
//            $new_data= (object)[];
//            $new_data->section_item = (object)[];
//            $new_data->section_item->item = $items;
//            $new_data->section_item->qty = $qty;
//            $new_data->section_item->price = $price;
//            $new_data->section_item->total = $total;
//            $new_data->item_option = $item_options;
//            $new_data = json_encode($new_data);
//            $crdata = CompanyReport::where(['company_id' => $request['company_id']])->first();
//            $crdata->json_data = $new_data;
//            $crdata->save();
//        }
//
//        $this->report = Report::firstOrNew(['project_id' => $projectId]);
//
//        if ($this->report->exists && empty($request->request_options)) {
//
//            $this->__collection = false;
//            $this->__is_paginate = false;
//            return $this->__sendResponse('Report',['url' => url($this->report['path'])],200,'Report Fetched Successfully');
//        }else if(empty($request->request_options)) {
//            return $this->__sendError("Report Not Found",['message'=> "You haven't create any report for the project"],400);
//        }
//
//        if($request->test){
//            /** Test Mode: Autofill 'optionsRequest' AND $user object */
//            $request['user-token'] = "670b4fa5948639577b80812b6d46b953";
//            $this->optionsRequest = json_decode($this->optionsRequest, true);
//            // $this->optionsRequest = $options;
//            $user = User::where(['token' => $request->header('user-token')])->first();
//        } else {
//            $user = User::where(['id' => $request['user_id']])->first();
//            $this->optionsRequest = $request['request_options'];
//            $this->ownerAuthorization = $request['request_options']['owner_authorization'];
//        }
//
//        if ($this->report->exists) {
//            /** Already Exist */
//            $request['user_id'] = $this->report->user_id;
//            $this->report->options = json_encode($this->optionsRequest);
//
//            if(!$request->update_report){
//                /** Set to Null If only we're not updating the report from sign process */
//                $this->report->inspector_sign = NULL;
//                $this->report->inspector_sign_at = NULL;
//                $this->report->customer_sign = NULL;
//                $this->report->customer_sign_at = NULL;
//            }
//
//        }else{
//            /** IF NEW */
//            $this->report->token = "report-" . uniqid() . "-" . time();
//            $this->report->user_id = $request['user_id'];
//            $this->report->options = json_encode($this->optionsRequest);
//        }
//
//        ini_set('memory_limit', '512M');
//
//        $this->userDetails = $user;
//
//        if (count((array)$user) < 1) {
//            $this->__is_ajax = true;
//            return $this->__sendError('This user token is invalid.', [['auth' => 'This user token is invalid.']], 200);
//        }
//
//        $request['user_id']             = $user['id'];
//        $request['company_id']          = $user['company_id'];
//        $request['company_group_id']    = $user['company_group_id'];
//        $request['project_id']          = $projectId;
//        $request->update_report         = $request->update_report;
//
//        if ($request['test'] != true) {
//            //<editor-fold desc="Basic Validation">
//            $params = $request->all();
//            $params['owner_authorization'] = $this->ownerAuthorization;
//
//            $param_rules['request_options'] = 'required';
//            $param_rules['user_id'] = 'required|int';
//            $param_rules['company_id'] = 'required|int';
//            $param_rules['company_group_id'] = 'required|int';
//            $param_rules['project_id'] = [
//                'required',
//                'int',
//                Rule::exists('project', 'id')->whereNull('deleted_at'),
//            ];
//            $param_rules['owner_authorization'] = 'nullable|array|min:4';
//
//            $this->__is_ajax = true;
//            $response = $this->__validateRequestParams($params, $param_rules);
//            if ($this->__is_error == true)
//                return $response;
//            //</editor-fold>
//        }
//
//        //<editor-fold desc="Setting $this->companyTemplates">
//        $identifiers = []; $selectedIds = [];
//        $selectedIntro = collect($this->optionsRequest['Introduction'])->where('selected','true');
//        $selectedDocs = collect($this->optionsRequest['Documents'])->where('selected','true');
//
//        if(!empty($selectedIntro)){
//            $selectedIntro = collect($selectedIntro)->pluck('id')->toArray();
//        }
//
//        if(!empty($selectedDocs)){
//            $selectedDocs = collect($selectedDocs)->pluck('id')->toArray();
//        }
//
//        if(!empty($this->optionsRequest['Terms_Conditions']))
//            $identifiers[] = "terms_conditions";
//
//        $selectedIds = array_merge($selectedIntro,$selectedDocs);
//        $params = ['company_id' => $request['company_id'] , 'ids' => $selectedIds , 'identifiers'=> $identifiers];
//        $reportTemplates = new ReportTemplate();
//        if (!empty($params['ids']) and !empty($params['identifiers'])) {
//            $this->companyTemplates = $reportTemplates->getSelectedTemplates($params);
//        }
//
//        //</editor-fold>
//
//        //<editor-fold desc="Setting $this->companyDetails">
//        $this->companyDetails = CompanyReport::where(['company_id' => $request['company_id']])->first();
//        $this->companyDetails->logo_path = url("uploads/report_templates/".$this->companyDetails->logo_path);
//        $this->companyDetails->report_cover_image = url("uploads/report_templates/".$this->companyDetails->report_cover_image);
//        $this->companyDetails->json_data = json_decode($this->companyDetails->json_data,true);
//
//        if(empty($this->optionsRequest['Owner_Authorization'])){
//            $this->companyDetails->json_data = NULL;
//        }
//
//        //</editor-fold>
//
//        //<editor-fold desc="Where clause mapping">
//        $whereClauses = ['whereCategory' => [], /*'whereSurvey' => [],*/ 'whereEstimates' => []];
//
//        /**Just lowering cases*/
//        $this->optionsRequest['breakdown'] = collect($this->optionsRequest['breakdown'])->mapWithKeys(function($item,$key){
//            return [strtolower($key) => $item];
//        });
//
//        foreach ($this->optionsRequest['categories'] AS $key => $item) {
//            if ($item['selected']) {
//                $whereClauses['whereCategory'][] = $item['id'];
//            }
//
////            if ($item['survey']) {
////                $whereClauses['whereSurvey'][] = $item['id'];
////            }
//
////            Commented on Jan-2023
////            if ($item['Estimates']) {
////                $whereClauses['whereEstimates'][$item['id']] = $this->optionsRequest['breakdown'];
////            }
//        }
//
//
//        foreach ($this->optionsRequest['Estimates'] as $key => $item) {
//            if ($item['selected']) {
//                $whereClauses['whereEstimates'][$item['id']] = $this->optionsRequest['breakdown'];
//            }
//        }
//        //</editor-fold>
//
//        return $this->generateWebReport($request->all(), $whereClauses);
    }

    public function createReport_v3(Request $request, $projectId)
    {
//        die("SURvey tak format sahi hogya ha. Ab aage ki method jo call ho rhe hen generateWebReport me, wo dikhne hen");

        try {
            if ($request->test) {
                /** Test Mode: Autofill 'optionsRequest' AND $user object */
                $request['user-token'] = "670b4fa5948639577b80812b6d46b953";
                $this->optionsRequest = json_decode($this->optionsRequest, true);
                // $this->optionsRequest = $options;
                $user = User::where(['token' => $request->header('user-token')])->first();
            } else {
                $user = User::where(['id' => $request['user_id']])->first();
                $this->optionsRequest = collect($request['request_options']);

                // $this->ownerAuthorization = $request['request_options']['owner_authorization'];
            }

            $requestOptions = collect($request->request_options);

            $this->photoSelection = $this->optionsRequest->where('key','photo_selections')
                ->first()['options'];

            $this->estimates = $this->optionsRequest->where('key','estimates')
                ->first()['options'];

            $this->breakdown = $this->optionsRequest->where('key','breakdown')
                ->first()['options'];

            $tradeItems = $this->optionsRequest->where('key','trade_items')
                ->first()['options'];

            $sectionItems = $this->optionsRequest->where('key','section_items')
                ->first()['options'];

            $itemOptions = $this->optionsRequest->where('key','item_options')
                ->first()['options'];

            $specialInstruction = $this->optionsRequest->where('key','special_instruction')
                ->first()['value'];

            $isCreditDisclaimer = $this->optionsRequest->where('key','credit_disclaimer')
                ->first()['selected'];

            $this->ownerAuthorization = [
                'section_items' => $sectionItems,
                'item_options' => $itemOptions,
                'special_instruction' => $specialInstruction,
                'credit_disclaimer' => $isCreditDisclaimer,
                'categories' => $tradeItems,
            ];

//            \Log::debug("createReport_v3".print_r([
//                                                      '$request->all(' => $request->all(),
//                                                      'photoSelection' => $this->photoSelection,
//                                                      'estimates' => $this->estimates,
//                                                      '$tradeItems' => $tradeItems,
//                                                      '$sectionItems' => $sectionItems,
//                                                      '$itemOptions' => $itemOptions,
//                                                      '$specialInstruction' => $specialInstruction,
//                                                      '$isCreditDisclaimer' => $isCreditDisclaimer,
//                                                  ],1));

            //<editor-fold desc="Meet work shouldn't be used 03-Jan-2024">
            if (!empty($sectionItems) && !empty($itemOptions)) {
                $items = [];
                $qty = [];
                $price = [];
                $total = [];
                $item_options = [];
                foreach ($sectionItems as $selectd_items) {
                    array_push($items, $selectd_items['title']);
                    $getqty = (string)number_format($selectd_items['qty']);
                    $getprice = (string)number_format($selectd_items['price'], 2, '.', '');
                    $gettotal = (string)number_format($selectd_items['total'], 2, '.', '');
                    array_push($qty, $getqty);
                    array_push($price, $getprice);
                    array_push($total, $gettotal);
                }

                foreach ($itemOptions as $item_option) {
                    array_push($item_options, $item_option['name']);
                }

                //            $new_data = (object)[];
                //            $new_data->section_item = (object)[];
                //            $new_data->section_item->item = $items;
                //            $new_data->section_item->qty = $qty;
                //            $new_data->section_item->price = $price;
                //            $new_data->section_item->total = $total;
                //            $new_data->item_option = $item_options;
                //            $new_data = json_encode($new_data);
                //            $crdata = CompanyReport::where(['company_id' => $request['company_id']])->first();
                //            $crdata->json_data = $new_data;
                //            $crdata->save();
            }
            //</editor-fold>


            $this->report = Report::firstOrNew(['project_id' => $projectId]);
            //<editor-fold desc="Returning Previously Built PDF link">
            if ($this->report->exists && empty($request->request_options)) {

                $this->__collection = false;
                $this->__is_paginate = false;
                return $this->__sendResponse(
                    'Report',
                    ['url' => url($this->report['path'])],
                    200,
                    'Report Fetched Successfully'
                );
            } else if (empty($request->request_options)) {
                return $this->__sendError(
                    "Report Not Found",
                    ['message' => "You haven't create any report for the project"],
                    400
                );
            }
            //</editor-fold>


            if ($this->report->exists) {
                /** Already Exist */
                $request['user_id'] = $this->report->user_id;
                $this->report->options = json_encode($this->optionsRequest);

                if (!$request->update_report) {
                    /** Set to Null If only we're not updating the report from sign process */
                    $this->report->inspector_sign = NULL;
                    $this->report->inspector_sign_at = NULL;
                    $this->report->customer_sign = NULL; // We should delete the sign image
                    $this->report->customer_sign_at = NULL;
                }

            } else {
                /** IF NEW */
                $this->report->token = "report-" . uniqid() . "-" . time();
                $this->report->user_id = $request['user_id'];
                $this->report->options = json_encode($this->optionsRequest);
            }
            ini_set('memory_limit', '512M');
            $this->userDetails = $user;
            if (count((array)$user) < 1) {
                $this->__is_ajax = true;
                return $this->__sendError(
                    'This user token is invalid.',
                    [['auth' => 'This user token is invalid.']],
                    200
                );
            }
            $request['user_id'] = $user['id'];
            $request['company_id'] = $user['company_id'];
            $request['company_group_id'] = $user['company_group_id'];
            $request['project_id'] = $projectId;

            if ($request['test'] != true) {
                //<editor-fold desc="Basic Validation">
                $params = $request->all();
                $params['owner_authorization'] = $this->ownerAuthorization;

                $param_rules['request_options'] = 'required';
                $param_rules['user_id'] = 'required|int';
                $param_rules['company_id'] = 'required|int';
                $param_rules['company_group_id'] = 'required|int';
                $param_rules['project_id'] = [
                    'required',
                    'int',
                    Rule::exists('project', 'id')->whereNull('deleted_at'),
                ];
                $param_rules['owner_authorization'] = 'nullable|array';

                $this->__is_ajax = true;
                $response = $this->__validateRequestParams($params, $param_rules);
                if ($this->__is_error == true)
                    return $response;
                //</editor-fold>

                //<editor-fold desc="Detailed Validation">
                $validatingParams = [
                    'photo_selection' => $this->photoSelection,
                    'estimates' => $this->estimates
                ];

                $detailed_rules['photo_selection.*.id'] = [
                    'required',
                    Rule::exists('category','id')
                        ->where('company_id',$request['company_id'])
                        ->where(function($q){
                            $q->whereNull('deleted_at');
                        })

                ];

                $detailed_rules['estimates.*.id'] = [
                    'required',
                    Rule::exists('category','id')
                        ->where('company_id',$request['company_id'])
                        ->where(function($q){
                            $q->whereNull('deleted_at');
                        })

                ];

                $response = $this->__validateRequestParams($validatingParams,$detailed_rules);
                if($this->__is_error == true)
                    return $response;
                //</editor-fold>
            }


            //<editor-fold desc="Setting $this->companyTemplates">

            $identifiers = collect([]);
            $selectedIds = [];

            $identifiers[] = $this->optionsRequest
                ->whereIn('key',['introduction','terms_conditions' ])
                ->where('selected', true)->pluck('key');

            $documents = $this->optionsRequest->where('key','documents')->first()['options'];

            // $selectedIntro = collect($introduction)->where('selected', 'true')->pluck('key')->toArray(); // no need cuz only one intro in web-panel
            $selectedDocs = collect($documents)->where('selected',true)->pluck('id')->toArray();
            $selectedIds = array_merge($selectedDocs);
            $params = ['company_id' => $request['company_id'], 'ids' => $selectedIds, 'identifiers' => collect($identifiers)->flatten()];
            $reportTemplates = new ReportTemplate();
            if (!empty($params['ids']) || !empty($params['identifiers'])) {
                $this->companyTemplates = $reportTemplates->getSelectedTemplates($params);
            }
            //</editor-fold>

            //<editor-fold desc="Setting $this->companyDetails">
            $this->companyDetails = CompanyReport::where(['company_id' => $request['company_id']])->first();
            $this->companyDetails->logo_path = url("uploads/report_templates/" . $this->companyDetails->logo_path);
            $this->companyDetails->report_cover_image = url(
                "uploads/report_templates/" . $this->companyDetails->report_cover_image
            );
            $this->companyDetails->json_data = json_decode($this->companyDetails->json_data, true);

            if (empty($tradeItems)) {
                $this->companyDetails->json_data = NULL;
            }
            //</editor-fold>


//            if ($isCreditDisclaimer && $this->companyDetails->is_disclaimer && !empty($this->companyDetails->credit_disclaimer)) {
//                $this->ownerAuthorization['credit_disclaimer'] = $this->companyDetails->credit_disclaimer;
//            }


            //<editor-fold desc="Where clause mapping">
            $whereClauses = ['whereCategory' => [], /*'whereSurvey' => [],*/
                'whereEstimates' => []];
            /**Just lowering cases*/

            $this->optionsRequest['breakdown'] =
                collect($this->breakdown)->mapWithKeys(function ($item, $key) {
                    return [$item['key'] => $item['selected']];
                });


//            foreach ($this->photoSelection as $key => $item) {
//                if ($item['selected']) {
//                    $whereClauses['whereCategory'][] = $item['id'];
//
//                }
//                            if ($item['survey']) {
//                                $whereClauses['whereSurvey'][] = $item['id'];
//                            }
//                            Commented on Jan-2023
//                            if ($item['Estimates']) {
//                                $whereClauses['whereEstimates'][$item['id']] = $this->optionsRequest['breakdown'];
//                            }
//            }

//            foreach ($this->estimates as $key => $item) {
//                if ($item['selected']) {
//                    $whereClauses['whereEstimates'][$item['id']] = $this->optionsRequest['breakdown'];
//                }
//            }
            //</editor-fold>

            return $this->generateWebReport($request->all());
        } catch (\Mpdf\MpdfException $e) {
            \Log::debug("MpdfException: " . $e->getMessage());
            return $this->__sendError("Exception: " . $e->getMessage(), [
                'file' => collect($e->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $e->getLine(),
            ],                        $e->getCode() ?: 400);
        } catch (\Exception $e) {
            \Log::debug("Exception: " . $e->getMessage());
            return $this->__sendError("Exception: " . $e->getMessage(), [
                'file' => collect($e->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $e->getLine(),
            ],                        $e->getCode() ?: 400);
        } catch (\Throwable $t) {
            \Log::debug("Throwable: " . $t->getMessage());
            return $this->__sendError("Throwable: " . $t->getMessage(), [
//                'file' => collect($t->getTrace())->filter(function ($value, $key) {
//                    return str_contains($value['file'], '/app/');
//                })->values(),
                'line' => $t->getLine(),
            ],                        $t->getCode());
        }
    }


    public function generateWebReport($request)
    {
        /** All cats incase of need to show unselected categories*/

        $titles = [
            'additional_photos' => 'Additional Photos',
            'required_category' => 'Included Photos',
            'damaged_category' => 'Inspection Areas',
        ];


        $photoSelectionIds = collect($this->photoSelection)->where('selected',true)->pluck('id');

        /** This Query was required cuz request params doesn't provider photo-view id
         *  and project_media refers (foreign-key) to photo-view ONLY
         */
        $areasWithParent = Category::whereIn('id',$photoSelectionIds)->orWhereIn('parent_id',$photoSelectionIds)
            ->get(['id','parent_id']);

        $photoSelectionIds = $areasWithParent->pluck('id');


//        dd(
//            'this->ownerAuthorization[categories]',$this->ownerAuthorization['categories'],
//            '$areasWithParent',$areasWithParent->groupBy('parent_id')->toArray(),
////            '$areasWithParent',$areasWithParent->pluck('parent_id'),
//            '$photoSelectionIds',$photoSelectionIds,
//        );



        $project = Project::with([
            'assigned_user',
            'project_media' => function($q) use($photoSelectionIds){
                $q->whereIn('project_media.target_id', $photoSelectionIds);
            },
            'project_media.tags',
                                  'project_media.tags.uom','project_media.target','project_media.target.parent',
                                  'project_survey','project_survey.category',
                              ])
            ->where(['id' => $request['project_id']])->first();

        $this->projectDetails = array_except(
            $project->toArray(),
            ['last_crm_sync_at',
                'project_media',
                'categories',
                'get_single_media',
                'complete_address',
                'company']
        );

        $this->projectDetails['assigned_user']['mobile_no'] = substr($this->projectDetails['assigned_user']['mobile_no'],0,-7).'-'.
            substr($this->projectDetails['assigned_user']['mobile_no'],-7,3).'-'.
            substr($this->projectDetails['assigned_user']['mobile_no'],-4);

        $this->projectDetails['latitude'] = round($this->projectDetails['latitude'], 7);
        $this->projectDetails['longitude'] = round($this->projectDetails['longitude'], 7);
        $this->projectDetails['inspection_date'] = date("m/d/Y", strtotime($this->projectDetails['inspection_date']));

        //<editor-fold desc="Custom Fonts">
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $euclidSquareFonts = [
            'R' => 'EuclidSquare-Regular.ttf',
            'I' => 'EuclidSquare-Italic.ttf',
            'B' => 'EuclidSquare-Bold.ttf',
            'BI' => 'EuclidSquare-BoldItalic.ttf'
        ];

        /*
            EuclidSquare-Bold.ttf
            EuclidSquare-BoldItalic.ttf
            EuclidSquare-Italic.ttf
            EuclidSquare-Light.ttf
            EuclidSquare-LightItalic.ttf
            EuclidSquare-Medium.ttf
            EuclidSquare-MediumItalic.ttf
            EuclidSquare-Regular.ttf
            EuclidSquare-SemiBold.ttf
            EuclidSquare-SemiBoldItalic.ttf

            Poppins-Regular.ttf
            Poppins-Italic.ttf
            Poppins-Bold.ttf
            Poppins-BoldItalic.ttf


            Poppins-Black.ttf
            Poppins-BlackItalic.ttf
            Poppins-Bold.ttf
            Poppins-BoldItalic.ttf
            Poppins-ExtraBold.ttf
            Poppins-ExtraBoldItalic.ttf
            Poppins-ExtraLight.ttf
            Poppins-ExtraLightItalic.ttf
            Poppins-Italic.ttf
            Poppins-Light.ttf
            Poppins-LightItalic.ttf
            Poppins-Medium.ttf
            Poppins-MediumItalic.ttf
            Poppins-Regular.ttf
            Poppins-SemiBold.ttf
            Poppins-SemiBoldItalic.ttf
            Poppins-Thin.ttf
            Poppins-ThinItalic.ttf
         */

        $poppinsFonts = [
            'R' =>  'Poppins-Regular.ttf',
            'I' =>  'Poppins-Italic.ttf',
            'B' =>  'Poppins-SemiBold.ttf',
            'BI' => 'Poppins-SemiBoldItalic.ttf',
        ];


        $euclidsquareFontDir = public_path('/assets/fonts/report-font/euclidsquare');
        $poppinsFontDir = public_path('/assets/fonts/report-font/Poppins');
        //</editor-fold>


        $this->mpdf = new \Mpdf\Mpdf([
                                         'mode' => 'utf-8',
//            'basepath' => 'google.com',
                                         'debug' => true,
                                         'format' => 'A4',
                                         'default_font_size' => 10,
                                         'default_font_color' => 'black',
                                         'default_font' => 'poppins',
                                         'margin' => 0,
                                         'defaultPageNumStyle' => '1',
                                         'fontDir' =>  array_merge($fontDirs, [$euclidsquareFontDir,$poppinsFontDir]),
                                         'fontdata' => collect($fontData)
                                             ->prepend($euclidSquareFonts,'euclidsquare')
                                             ->prepend($poppinsFonts,'poppins')
                                             ->toArray()
                                     ]);



        //$this->mpdf->SetDefaultBodyCSS('background-color', "red");

//        $this->mpdf->showImageErrors = true;
        $this->mpdf->useActiveForms = true;

//        $this->mpdf->formUseZapD = true;
//        $this->mpdf->form_border_color = '0.6 0.6 0.72';
//        $this->mpdf->form_button_border_width = '2';
//        $this->mpdf->form_button_border_style = 'S';
//        $this->mpdf->form_radio_color = '#619eff'; 	// radio and checkbox
//        $this->mpdf->form_radio_background_color = '#242424';

        $this->mpdf->SetTitle($this->projectDetails['name']);


        $this->coverPage();

        $url = url("image/report/bg.png");
//        $url = url("image/report/card-1.png");
        $this->mpdf->SetDefaultBodyCSS('background', "url('$url')");
        $this->mpdf->SetDefaultBodyCSS('background-image-resize', "3");

        $this->companyIntroduction();
        $this->fourImagesTemplate($project->project_media);
        $this->surveyTemplate($project->project_survey);
        $this->estimatesTemplate($project->project_media);
//        $this->addDocuments();



        $this->totalPages =  count($this->mpdf->pages);
        $this->mpdf->SetTitle($project['name']);

        /**######################## To Save File and then show via URL ########################*/
        //<editor-fold desc="To Save File and then show via URL">
        $fileName = 'project_report_' . $project['id'] . '.pdf';

        $reportPath = public_path(config('constants.PDF_PATH') . $fileName);
        $reportUrl = (env('BASE_URL') . config('constants.PDF_PATH') . $fileName);

        $this->report->path = config('constants.PDF_PATH') . $fileName;
        $this->report->save();

        //<editor-fold desc="Comment this block to stop saving the output to a file and returning its url as response">

        $this->mpdf->Output($reportPath, 'F');

        if ($request['update_report']) {
            /** update_report is used ONLY when customer signs and we need to update the report without response*/
            return true;
        }
        $this->__collection = false;
        $this->__is_paginate = false;
        return $this->__sendResponse('Report', ['url' => $reportUrl], 200, 'Report Created Succesfully');

        //</editor-fold>

        //</editor-fold>

        /** ######################## To real-time output (useful for debugging) ########################*/
        return response($this->mpdf->Output("test", "I"), 200)->header('Content-Type', 'application/pdf');
    }

    public function coverPage(){
        $this->mpdf->AddPage(
            '', // L - landscape, P - portrait
            '', // E-even|O-odd|even|odd|next-odd|next-even
            '', '', '',
            0, // margin_left
            0, // margin right
            0, // margin top
            0, // margin bottom
            0, // margin header
            0 // margin footer

        );

        $cover = view('reports/v3/cover_page',['companyDetails' => $this->companyDetails,
            'userDetails' => $this->userDetails, 'projectDetails' => $this->projectDetails ]);
        $this->mpdf->SetHTMLFooter(
            view(
                'reports/v3/cover_footer',
                ['project' => $this->projectDetails, 'companyDetails' => $this->companyDetails]
            )->render()
        );
        $this->mpdf->WriteHTML($cover);
    }

    public function companyIntroduction(){

        if(!empty($this->companyTemplates) AND !empty($this->companyDetails)){

            if($this->companyTemplates->where('identifier','introduction')->isNotEmpty()){
                $this->mpdf->SetHTMLHeader(view('reports/v3/header', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails ,'heading' => 'Company Introduction' ])->render());
                $this->mpdf->AddPage(
                    '', // L - landscape, P - portrait
                    '', // E-even|O-odd|even|odd|next-odd|next-even
                    '', '', '',
                    15, // margin_left
                    15, // margin right
                    30, // margin top
                    30, // margin bottom
                    0, // margin header
                    0);

                $this->mpdf->WriteHTML(
                    view("reports/v3/introduction",
                        ['introduction' => $this->companyTemplates->where('identifier','introduction')->values()->toArray(),
                            'companyDetails' => $this->companyDetails ]
                    )->render()
                );
                $this->mpdf->SetHTMLFooter(view('reports/v3/footer', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails])->render());
            }
        }
    }

    /** Isn't getting used anywhere so commenting so to be retired closed in date  25 feb 22
     *
     public function ownerAuthorization(){
        $this->mpdf->SetHTMLHeader(view('reports/v2/header', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails ])->render());
        $this->mpdf->AddPage(
            '', // L - landscape, P - portrait
            '', // E-even|O-odd|even|odd|next-odd|next-even
            '', '', '',
            15, // margin_left
            15, // margin right
            40, // margin top
            60, // margin bottom
            0, // margin header
            0);
        $this->mpdf->SetHTMLFooter(view('reports/v2/footer', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails])->render());
        $companyReport=$request['request_options']['owner_authorization'];
        $this->mpdf->WriteHTML(
            view("reports/v2/owner_authorization",
                ['companyDetails' => $companyReport ]
            )->render()
        );
    }*/

    public function termsNConditions()
    {
//        $this->companyTemplates = null;
        $terms = collect($this->companyTemplates)->where('identifier', 'terms_conditions')->values();
//        dd( $terms->isNotEmpty());


        if ($terms->isNotEmpty()) {

                $this->mpdf->SetHTMLHeader(view('reports/v3/header', ['heading' => 'Terms & Conditions', 'project' => $this->projectDetails, 'companyDetails' => $this->companyDetails])->render());
                $this->mpdf->AddPage(
                    '', // L - landscape, P - portrait
                    '', // E-even|O-odd|even|odd|next-odd|next-even
                    '', '', '',
                    15, // margin_left
                    15, // margin right
                    40, // margin top
                    60, // margin bottom
                    0, // margin header
                    0);
                $this->mpdf->SetHTMLFooter(view('reports/v3/footer', ['project' => $this->projectDetails, 'companyDetails' => $this->companyDetails])->render());
                $this->mpdf->WriteHTML(
                    view("reports/v3/terms_n_conditions",
                        [
                            'companyDetails' => $this->companyDetails,
                            'termsNConditions' => $terms->all(),
                            'report' => $this->report
                        ]
                    )->render()
                );

        }
    }

    public function addDocuments(){

        if(!empty($this->companyTemplates)){

            $docs = $this->companyTemplates->where('identifier','documents')->map(function($template){
                $template->path = base_path("public/{$template->path}");
                return $template;
            });

            $filesTotal = sizeof($docs);
            $fileNumber = 1;

            if(!empty($docs)){
                foreach ($docs AS $template){
                    if (file_exists($template->path)) {
                        $pagesInFile = $this->mpdf->SetSourceFile($template->path);
//                        dd($pagesInFile);

//                        $this->mpdf->SetHTMLHeader(view('reports/v2/header', [ 'heading' => "{$template->title}" , 'project' => $this->projectDetails,'companyDetails' => $this->companyDetails ])->render());

//                        $this->mpdf->SetHTMLHeader(view('reports/v3/header', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails ,'heading' => 'Company Introduction' ])->render());
                        $this->mpdf->SetHTMLHeader();
                        for ($i = 1; $i <= $pagesInFile; $i++) {
                            $tplId = $this->mpdf->importPage($i); // in mPdf v8 should be 'importPage($i)'

                            $size = $this->mpdf->getTemplateSize($tplId);
                            $this->mpdf->AddPage(
                                $size['orientation'], // L - landscape, P - portrait
                                '', // E-even|O-odd|even|odd|next-odd|next-even
                                '',
                                '',
                                '',
                                15, // margin_left
                                15, // margin right
                                30, // margin top
                                30, // margin bottom
                                0, // margin header
                                0
                            );
//                            $this->mpdf->SetHTMLFooter(view('reports/v3/footer', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails])->render());
                            $this->mpdf->SetHTMLFooter();
                            //$this->mpdf->useTemplate($tplId, 0, 20, $size['width'], $size['height'], true);
                            $this->mpdf->useTemplate($tplId, 0, 20, $size['width'], $size['height']);

                            if (($fileNumber < $filesTotal) || ($i != $pagesInFile)) {
                                $this->mpdf->WriteHTML('<pagebreak />');
                            }
                        }
                    }
                }
            }
        }
    }

    /** To be removed disabled on Feb-2024 */
    public function __fourImagesTemplate($project, $category)
    {
        $html = '';
        if (/*FALSE*/ TRUE ) {
            foreach ($category AS $mainKey => $item) {

                $category[$mainKey]['media_count'] += count(((array) $item['media']));
                $this->mpdf->SetHTMLHeader(view('reports/v2/header', ['companyDetails' => $this->companyDetails , 'heading' => $item['category_name'] ])->render(),'',true);

                /** CHILD **/
                if (!empty($category[$mainKey]['get_child']) /*FALSE*/) {
                    foreach ($category[$mainKey]['get_child'] AS $subKey => $subItem) {
                        $category[$mainKey]['get_child'][$subKey]['media_count'] += count(((array) $subItem['media']));
                    }
                }
            }
            $html .= view('reports/v2/four_images', ['project' => $this->projectDetails , 'companyDetails' => $this->companyDetails ,'category' => $category])->render();
//            $html.="<pagebreak/>";
        }
        return $html;
    }

    /*4 Images template - v3*/
    public function fourImagesTemplate($projectMedia)
    {
        $html = '';
        if (/*FALSE*/ $projectMedia->isNotEmpty() ) {

            $this->mpdf->SetHTMLHeader(view('reports/v3/header', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails ,'heading' => 'Company Introduction' ])->render());
            $this->mpdf->AddPage(
                '', // L - landscape, P - portrait
                '', // E-even|O-odd|even|odd|next-odd|next-even
                '', '', '',
                15, // margin_left
                15, // margin right
                30, // margin top
                30, // margin bottom
                0, // margin header
                0);

            $this->mpdf->SetHTMLFooter(view('reports/v3/footer', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails])->render());
            $html .= view('reports/v3/two_images', ['project' => $this->projectDetails , 'companyDetails' => $this->companyDetails ,'projectMedia' => $projectMedia])->render();
            $this->mpdf->WriteHTML($html);

//            $html.="<pagebreak/>";
        }
        return $html;
    }

    public function surveyTemplate($survey, $whereSurvey = [])
    {
        $html = '';

        $surveyGroupedByCategory = collect($survey)->mapToGroups(function ($item, $key) {
            return [$item['category']['name'] => $item];
        });

        if($surveyGroupedByCategory->isNotEmpty()){
            $this->mpdf->SetHTMLHeader(view('reports/v3/header', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails ,'heading' => 'Company Introduction' ])->render());
            $this->mpdf->AddPage(
                '', // L - landscape, P - portrait
                '', // E-even|O-odd|even|odd|next-odd|next-even
                '', '', '',
                15, // margin_left
                15, // margin right
                30, // margin top
                30, // margin bottom
                0, // margin header
                0);
            $this->mpdf->SetHTMLFooter(view('reports/v3/footer', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails])->render());
            $html .= view('reports/v3/inspection_details', [
                'project' => $this->projectDetails ,
                'companyDetails' => $this->companyDetails ,
                'surveyGrouped' => $surveyGroupedByCategory])->render();
            $this->mpdf->WriteHTML($html);
        }
//        return $html;
    }

    public function estimatesTemplate($projectMedia){

        $selectedEstimate = collect($this->estimates)/*->where('selected',true)*/;

        if ($selectedEstimate->isNotEmpty()) {
            $target = $projectMedia->map(function ($item) {
                return $item['target']['parent'] ?: $item['target'];
            })->unique('id')->keyBy('id')->toArray();

            //<editor-fold desc="Preparing tags data category wise">
            $projectMediaGroupedByCategory = collect($projectMedia)->filter(function ($item, $key) {
                if(in_array($item['target']['type'],[2])){
                    return $item;
                }
            })->mapToGroups(function ($item, $key) {
//                return [$item['target_id'] => $item['tags']->toArray()];
                $areaId = !empty($item['target']['parent']['id']) ? $item['target']['parent']['id'] : $item['target']['id'];
                return [$areaId => $item['tags']->toArray()];
            })
                ->map(function ($item, $key) {
                    // $key is category_id that we grouped with

                    // ->filter() to remove empty elements
                    return collect($item)->filter()->flatMap(function ($values) {
                        /** flatMap only to remove additional level of key/ bring objects up a level */
                        return $values;
                    })->groupBy('id')->map(function ($group) {
                        /** We have grouped by tags_ids for the case:
                         *  2 media under a single category (area) having same tags (with qty).
                         *  We sum its quantity and overwrite those repeating tags
                         */
                        $element = collect($group->last())->except(['qty']);
                        $element['qty'] = $group->sum('qty');
                        if($element['qty'] > 0){
                            return $element;
                        }
                    })->values()->filter(/**to remove empty elements*/);
                })->map(function ($item, $key) use ($target, $selectedEstimate) {

                    /** $key is PK of 'req. photo' OR 'inspection area' */

                    $estimateAreaId = !empty($target[$key]['parent']) ? $target[$key]['parent']['id'] : $target[$key]['id'];

                    if ($selectedEstimate->pluck('id')->contains($estimateAreaId)) {
                        return collect([
                                           'category' => $target[$key],
                                           'tags' => $item,
                                           //                'subtotal' => 0,
                                       ]);
                    }else{
                        return collect(['category' => $target[$key],
                                           'tags' => [] ]);
                    }

                })->filter()->values();
            //</editor-fold>

            //<editor-fold desc="Terms & Conditions">
            $terms = collect($this->companyTemplates)->where('identifier', 'terms_conditions')->values();
            $termsCondition = null ;
            if ($terms->isNotEmpty()){
//                dd($terms->first());
                $termsCondition = $terms->first();
            }
            //</editor-fold>

            $this->ownerAuthorization['categories'] = collect($this->ownerAuthorization['categories']);
            $this->ownerAuthorization['section_items'] = collect($this->ownerAuthorization['section_items']);
            $this->ownerAuthorization['item_options'] = collect($this->ownerAuthorization['item_options']);

            if($projectMediaGroupedByCategory->isNotEmpty()){
                $this->mpdf->SetHTMLHeader(
                    view(
                        'reports/v3/header',
                        ['project' => $this->projectDetails,
                            'companyDetails' => $this->companyDetails,
                            'heading' => 'Company Introduction']
                    )->render()
                );
                $this->mpdf->AddPage(
                    '', // L - landscape, P - portrait
                    '', // E-even|O-odd|even|odd|next-odd|next-even
                    '',
                    '',
                    '',
                    15, // margin_left
                    15, // margin right
                    30, // margin top
                    30, // margin bottom
                    0, // margin header
                    0
                );
                $this->mpdf->SetHTMLFooter(
                    view(
                        'reports/v3/footer',
                        ['project' => $this->projectDetails, 'companyDetails' => $this->companyDetails]
                    )->render()
                );
                $this->mpdf->WriteHTML(
                    view('reports/v3/estimates', [
                        'estimates' => $projectMediaGroupedByCategory,
                        'companyDetails' => $this->companyDetails,
                        'breakdown' => collect($this->breakdown)->pluck('selected', 'id'),
                        'breakdownColCount' => (collect($this->breakdown)->pluck('selected', 'id')->values()->filter(
                            )->count() + 2),
                        'breakdownColWidth' => ceil(
                            900 / (collect($this->breakdown)->pluck('selected', 'id')->values()->filter()->count() + 2)
                        ),
                        'report' => $this->report,
                        'projectDetails' => $this->projectDetails,
                        'userDetails' => $this->userDetails,
                        'ownerAuthorization' => $this->ownerAuthorization,
                        'termsCondition' => $termsCondition
                    ])->render()
                );
            }
        }
    }

    public function addSignImage(){

//        $this->mpdf->AddPage(
//            '', // L - landscape, P - portrait
//            '', // E-even|O-odd|even|odd|next-odd|next-even
//            '', '', '',
//            15, // margin_left
//            15, // margin right
//            40, // margin top
//            60, // margin bottom
//            0, // margin header
//            0);
//        $url = url(config('constants.SIGN_PATH')."customer_sign1641124325.svg");
//        $this->mpdf->WriteHTML("<table>
//                    <tr><td><h1>SIGNED: </h1></td></tr>
//                    <tr><td><img src='$url' alt='sign_image'/></td></tr>
//                    </table>");

    }

    public function componentTemplate($categories)
    {
        $categories = collect($categories);

        if($categories->where('media_tags','t')->isNotEmpty()){

            $this->mpdf->AddPage(
                '', // L - landscape, P - portrait
                '', // E-even|O-odd|even|odd|next-odd|next-even
                '', '', '',
                15, // margin_left
                15, // margin right
                40, // margin top
                60, // margin bottom
                0, // margin header
                0);

            $this->mpdf->SetHTMLHeader(view('reports/v2/header', ['companyDetails' => $this->companyDetails , 'heading' => "Component List" ])->render(),'',true);
            // view('reports/v2/component', ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails, 'categories' => $categories])->render();
            $this->mpdf->WriteHTML(
                view('reports/v2/component',
                    ['project' => $this->projectDetails,'companyDetails' => $this->companyDetails, 'categories' => $categories]
                )->render());
        }
    }

    public function output(){
        return response($this->mpdf->Output('test.pdf',"I"),200)->header('Content-Type','application/pdf');
    }

    public function webSample(Request $request){

        return view("reports.test");
    }

    public function hello(Request $request){

        $sign = new HelloSign();
        $sign->authentication();
        $sign->sign();
    }

    public function signNow(Request $request){

        $sign = new SignNow();
        $sign->authentication();
//        $sign->sign();
    }

    public function store(Request $request)
    {
        $this->__view = 'subadmin/tag';
        $this->__is_redirect = true;

        $param_rules['company_id'] = 'required|int';
        $param_rules['name'] = 'required|string|max:100';
        $param_rules['has_qty'] = 'required|int';
        $param_rules['category_id'] = 'required|int';
        $response = $this->__validateRequestParams($request->all(), $param_rules);
        if ($this->__is_error == true){
            $error = \Session::get('error');
            $this->__setFlash('danger','Not Updated Successfully' , $error['data']);
            return $response;
        }

        $tag = new Tag();
        $tag['company_id']  =   $request['company_id'];
        $tag['category_id'] =   $request['category_id'];
        $tag['name']        =   $request['name'];
        $tag['has_qty']     =   $request['has_qty'];

        if (!$tag->save()) {
            return $this->__sendError('Query Error', 'Unable to add record.');
        }

        $this->__setFlash('success', 'Added Successfully');
        $this->__is_paginate = false;
        $this->__is_collection = false;
        return $this->__sendResponse('Tag', [], 200,'Tag added successfully.');
    }

    public function customerSignView($token){

        $report = Report::with(['project'])->where(['token' => $token])->first();

        if(!$report->exists && !empty($report->is_signed)){
            return redirect('home');
        }
        return view("web.report_sign",['report' => $report]);
    }

    public function customerSign(Request $request,$token){

        $this->__is_ajax = true;
        $this->__is_paginate = false;
        $this->__collection = false;
//        dd($request->all());

        $request['token'] = $token;
        //<editor-fold desc="Validation">
        $param_rules['token'] = [
            'required',
            Rule::exists('reports','token')
                ->whereNull('deleted_at')
        ];

        $param_rules['signatory_name'] = 'required|regex:/^[a-zA-Z ]*$/|max:30';

        $response = $this->__validateRequestParams($request->all(),$param_rules);
        if($this->__is_error == true){
            return $response;
        }
        //</editor-fold>

        $report = Report::with(['project'])->where(['token' => $token])->first();

        //<editor-fold desc="Secondary Validation">
        if(now()->diffInDays(Carbon::parse($report->token_expires_at),false) < 0){
            return $this->__sendError("Your signing link has expired",[],400);
        }
        //</editor-fold>

        //dd(Carbon::parse($report->token_expires_at), now(),now()->diffInDays(Carbon::parse($report->token_expires_at),false), $report->token_expires_at > now());
        try {

            $fontConfig = [
                'path' => public_path('assets/fonts/creattion.ttf'),
                'color' => '#2e302f',
                'size' => 78,
                'angle' => 0,
            ];


//        $text = 'Daniyal Nasir Qureshi Achakzai Navviwala markhor';
//        $text = 'Paul Lewis';
//        $text = 'Col. Sebastian';
//        $text = 'Daniyal Nasir Qureshi';
//        $text = 'Ali';
        $text = $request['signatory_name'];

            //<editor-fold desc="Adding Text to a bounding-box to estimates its dimensions as image">
            //  0	lower left corner, X position
            //  1	lower left corner, Y position
            //  2	lower right corner, X position
            //  3	lower right corner, Y position
            //  4	upper right corner, X position
            //  5	upper right corner, Y position
            //  6	upper left corner, X position
            //  7	upper left corner, Y position
            $textSize = imageftbbox($fontConfig['size'], 0, $fontConfig['path'], $text);
            $textWidth = $textSize[2] - $textSize[0];
            $textHeight = ($textSize[1] + $textSize[7]);
            //</editor-fold>


            $img = Image::canvas(800, 300);

            /** When Text length is greater than image width */
            if ($textWidth >= $img->width()) {
                $percentIncrease = ($img->width(
                        ) / $textWidth) * 100; // calculating increased of text_width relative to image_width
                $extendedWidth = $img->width(
                    ) + (($textWidth / 100) * $percentIncrease); // adding text_width's percent to imageWidth
                $img->resize($extendedWidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                /** increasing the same percent of font, means 20% increase in text_width relative to image_width will
                 *  cause a 20% increase of orginal font-size.
                 */
                $fontConfig['size'] = $fontConfig['size'] + (($fontConfig['size'] / 100) * $percentIncrease) / 2;
            }

            //<editor-fold desc="Adding text to the image">

            // Calculate the position to center the text vertically and horizontally
            $positionX = ($img->width() - $textWidth) / 2;      // 800 - 200 / 2 => 300
            $positionY = ($img->height() + $textHeight) / 2;    // 300 + 200 / 2 => 250
            $img->text($text, $positionX, $positionY, function ($font) use ($fontConfig) {
                $font->file($fontConfig['path']);
                $font->size($fontConfig['size']);;
                $font->color($fontConfig['color']);
//            $font->align('center');
//            $font->valign('middle');
                $font->angle($fontConfig['angle']);
            });
            //</editor-fold>



            if (empty($report->customer_sign)) {
                /** creating new image-file*/
                $imageName = 'customer-sign-' . time() . '-' . rand() . '.png';
                $imagePath = public_path(config('constants.SIGN_PATH') . $imageName);
                $report->customer_sign = config('constants.SIGN_PATH') . "{$imageName}";
            } else {
                /** Using previous image-file to overwrite*/
                $imagePath = public_path($report->customer_sign);
            }
            $img->save($imagePath);


            $report->customer_sign_at = Carbon::now();
            if (!empty($report->inspector_sign)) {
                $report->is_signed = 1;
            }
            $report->save();

            if (!$report->exists) {
                return redirect('home');
            }

            /** We'll have to update report, to add the customer sign */
            //<editor-fold desc="Update Report">
            $reportRequest = new Request();
            $reportRequest->setMethod('POST');
            $reportRequest->request->add([
                                             'request_options' => json_decode($report->options,true),
                                             'user_id' => $report->user_id,
                                             'update_report' => true
                                         ]);

            $updateReport = $this->createReport_v3($reportRequest, $report->project_id);

            //</editor-fold>

            /** Email a final report email */
            if (!empty($report->customer_sign) && !empty($report->inspector_sign) && $updateReport) {
                $mailParams['LINK'] = url("report/sign/{$report->token}");
                $mailRes = $this->__sendMail('report_summary', $report->project->customer_email, $mailParams);
            }
        } catch (\Illuminate\Database\QueryException $qe) {
            \Log::debug("QueryException: " . $qe->getMessage());
            return $this->__sendError("QueryException: " . $qe->getMessage(), [
                'file' => collect($qe->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $qe->getLine(),
            ],                        500);

        } catch (\Exception $e) {
            \Log::debug("Exception: " . $e->getMessage());
            return $this->__sendError("Exception: " . $e->getMessage(), [
                'file' => collect($e->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $e->getLine(),
            ],                        $e->getCode() ?: 400);
        } catch (\Throwable $t) {
            \Log::debug("Throwable: " . $t->getMessage());
            return $this->__sendError("Throwable: " . $t->getMessage(), [
                'file' => collect($t->getTrace())->filter(function ($value, $key) {
                    return str_contains($value['file'], '/app/');
                })->values(),
                'line' => $t->getLine(),
            ],                        $t->getCode());
        }

        $this->__is_ajax = true;
        $this->__is_paginate = false;
        $this->__collection = false;
        return $this->__sendResponse('Report',[],200,'Your signature for consent on the report is added. Thanks!');
    }

    public function addSign(Request $request){

        $this->__collection = false;
        $this->__is_paginate = false;

        //<editor-fold desc="Validation">
        $param_rules['sign'] = "required|image|mimes:jpeg,svg,png";
        $param_rules['identifier'] = "required|in:customer,inspector";
        $param_rules['project_id'] =
            [
                'required',
                'int',
                Rule::exists('reports', 'project_id')->whereNull('deleted_at')
            ];

        $response = $this->__validateRequestParams($request->all(), $param_rules);

        if ($this->__is_error == TRUE)
            return $response;
        //</editor-fold>

        $report = Report::where(['project_id' => $request['project_id']])->first();

        if(empty($report)){
            return $this->__sendError('Report Not Found',[],400);
        }

        $identifierColName = $request['identifier'].'_sign';

        if ($request->hasFile('sign')) {
            try{



            $signPath = config('constants.SIGN_PATH');
            $newFileName = "{$request['identifier']}-sign-" . time() . '-' . rand();

            /** IF IMAGE EXISTS Overwrite that*/
            if(!empty($report->$identifierColName)){
                $pathInfo = pathinfo($report->$identifierColName);
                $imageName = (strpos($pathInfo['filename'],'.') > 0) ? $pathInfo['filename'] : $newFileName;
            }else{
                $imageName = $newFileName;
            }

            $uploadedName = $this->__moveUploadFile(
                $request->file('sign'),
                $imageName,
                $signPath
            );

            \Log::debug("".print_r([
                '$uploadedName' => $uploadedName
                                   ],1));

            $report->$identifierColName = $signPath.$uploadedName;
            $report->{$identifierColName.'_at'} = Carbon::now();

            $report->save();

            /** We'll have to update report, to add the inspector sign */
            //<editor-fold desc="Update Report">
            $reportRequest = new Request();
            $reportRequest->setMethod('POST');
            $reportRequest->request->add([
                                             'request_options' => json_decode($report->options,true),
                                             'user_id' => $report->user_id,
                                             'update_report' => true
                                         ]);

            $updateReport = $this->createReport_v3($reportRequest, $report->project_id);

            if($updateReport){
                return $this->__sendResponse('Report',[],200,'Sign Added Successfully');
            }

            } catch (\Illuminate\Database\QueryException $qe) {
                \Log::debug("QueryException: " . $qe->getMessage());
                return $this->__sendError("QueryException: " . $qe->getMessage(), [
                    'file' => collect($qe->getTrace())->filter(function ($value, $key) {
                        return str_contains($value['file'], '/app/');
                    })->values(),
                    'line' => $qe->getLine(),
                ],                        500);

            } catch (\Exception $e) {
                \Log::debug("Exception: " . $e->getMessage());
                return $this->__sendError("Exception: " . $e->getMessage(), [
                    'file' => collect($e->getTrace())->filter(function ($value, $key) {
                        return str_contains($value['file'], '/app/');
                    })->values(),
                    'line' => $e->getLine(),
                ],                        $e->getCode() ?: 400);
            } catch (\Throwable $t) {
                \Log::debug("Throwable: " . $t->getMessage());
                return $this->__sendError("Throwable: " . $t->getMessage(), [
//                'file' => collect($t->getTrace())->filter(function ($value, $key) {
//                    return str_contains($value['file'], '/app/');
//                })->values(),
                    'line' => $t->getLine(),
                ],                        $t->getCode());
            }

            //</editor-fold>
        }

        return $this->__sendError('Sign failed to be added',['Sign failed to be added'],400);
    }

    public function sendCustomerSignMail(Request $request){
        $this->__collection = false;
        $this->__is_paginate = false;

        //<editor-fold desc="Validation">
        $param_rules['project_id'] =
            [
                'required',
                'int',
                Rule::exists('reports', 'project_id')->whereNull('deleted_at')
            ];

        $response = $this->__validateRequestParams($request->all(), $param_rules);

        if ($this->__is_error == TRUE)
            return $response;
        //</editor-fold>

        $report = Report::selectRaw("reports.*,project.customer_email,inspector.id AS inspector_id,inspector.first_name AS inspector_first_name,inspector.last_name AS inspector_last_name,inspector.email AS inspector_email")
            ->join('project','project.id','reports.project_id')
            ->join('user AS inspector','inspector.id','project.assigned_user_id')
            ->where(['project_id' => $request['project_id']])->first();


        //<editor-fold desc="Secondary Validation">

        if(empty($report->customer_email)){
            return $this->__sendError("Customer Email Not Found", [], 400);
        }
        //</editor-fold>

        //<editor-fold desc="Sending Email">
        $mailParams['LINK'] = url("report/sign/{$report->token}");
        $mailParams['APP_URL'] = dynamicBaseUrl('');

        $mailParams['INSPECTOR_FIRST_NAME'] = $report->inspector_first_name;
        $mailParams['INSPECTOR_LAST_NAME'] = $report->inspector_last_name;
        $mailParams['INSPECTOR_EMAIL'] = $report->inspector_email;
        // [CUSTOMER_FIRST_NAME],[CUSTOMER_LAST_NAME]
        $emailIdentifier = 'report_customer_sign'; //!empty($report->customer_sign) ? 'report_summary': 'report_customer_sign';
        $mailRes = $this->__sendMail($emailIdentifier,$report->customer_email,$mailParams);
        //</editor-fold>

        $report->token_expires_at = now()->addDays(7);
        $report->is_signed = 0;
        $report->save();

        if($mailRes){
            return $this->__sendResponse('Report',[],200,'Email has been sent to the customer for signature');
        }
    }


    //<editor-fold desc="Report Management Web-Panel">
    public function listView(Request $request){

        $report    = CompanyReport::where(['company_id' => $request->company_id ])->first();
        $templates = ReportTemplate::where(['company_id' => $request->company_id ])->get();
        $data['documents']       = ReportTemplate::where('company_id',$request->company_id)->where('identifier','documents')->get();
        $data['introductions']   = ReportTemplate::where('company_id',$request->company_id)->where('identifier','introduction')->first();
        $data['termsConditions'] = ReportTemplate::where('company_id',$request->company_id)->where('identifier','terms_conditions')->first();
        $data['report']    = $report;
        $data['templates'] = $templates;
        return view('admin/reports',$data);
    }

    public function storeLogo(Request $request)
    {
        $this->__is_ajax = true;

        // $param_rules['logo'] = 'required_unless:image_set,true|image|mimes:jpeg,bmp,png|
        // dimensions:width=500,height=167';
        $param_rules['logo'] = 'required_unless:image_set,true|image|mimes:jpeg,bmp,png';
        $messages['required_unless'] = "The logo field is required";
        $response = $this->__validateRequestParams($request->all(), $param_rules, $messages);
        if ($this->__is_error == true){
            return $response;
        }

        if($request['image_set']){
            $this->__is_paginate = false;
            $this->__is_collection = false;
//            $this->__setFlash('success', 'Added Successfully');
            $this->__sendResponse('Tag', [], 200,'Tag added successfully.');
        }

        if ($request->hasFile('logo')) {
            // $obj is model
            $uploadedLogo = $this->__moveUploadFile(
                $request->file('logo'),
                md5($request['email'] . $request['device_token']),
                "{$this->templatesPath}/logo"
            );
            $logoUpdate = CompanyReport::updateOrCreate(['company_id' => $request->company_id],['logo_path' => "logo/".$uploadedLogo]);
        }

//        $this->__setFlash('success', 'Added Successfully');
        $this->__is_ajax = true;
        $this->__is_paginate = false;
        $this->__is_collection = false;
        $this->__collection = false;
        return $this->__sendResponse('Tag', [], 200,'Logo Uploaded successfully.');
    }

    public function storeInfo(Request $request)
    {
        $this->__view = 'admin/reports';
        $this->__is_redirect = true;

        //<editor-fold desc="Validation">
        if(!empty($request['name']) OR !empty($request['email']) OR !empty($request['phone']) OR !empty($request['website']) ){
            $param_rules['name'] = "required|min:3|max:50";
            $param_rules['email'] = "required|min:3|max:50";
            $param_rules['phone'] = "required|min:3|max:50";
            $param_rules['website'] = "required|min:3";
            // $param_rules['services'] = "required|min:3|max:500";
        }else if(!empty($request['credit_disclaimer']) OR !empty($request['is_disclaimer'])){
            $param_rules['credit_disclaimer'] = "required|min:3";
            $param_rules['is_disclaimer'] = "required|in:0,1";
        }



        $response = $this->__validateRequestParams($request->all(), $param_rules);
        if ($this->__is_error == true){
            $error = \Session::get('error');
            $this->__setFlash('danger','Not Added Successfully' , $error['data']);
            return $response;
        }
        //</editor-fold>

        $cReport = CompanyReport::firstOrNew(['company_id' => $request->company_id]);

        $cReport->fill($request->all());

//        dd($cReport->getAttributes());

        $cReport->save();

        /*$addFields = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'website' => $request->website,
            'services' => $request->services,
        ];
        CompanyReport::updateOrCreate(['company_id' => $request->company_id],$addFields);*/

        $this->__setFlash('success', 'Added Successfully');
        $this->__is_paginate = false;
        $this->__is_collection = false;
        return $this->__sendResponse('Tag', [], 200,'Tag added successfully.');
    }

    public function storeCoverInfo(Request $request)
    {
        $this->__is_ajax = true;

        //<editor-fold desc="Validation">
        $param_rules['report_name'] = "required|min:3|max:50";
        // $param_rules['cover_image'] = "required_unless:image_set,true|image|mimes:jpeg,bmp,png|dimensions:width=800,height=700";
        $param_rules['cover_image'] = "required_unless:image_set,true|image|mimes:jpeg,bmp,png";
        
        $param_rules['user_name']    = "nullable|in:true,false";
        $param_rules['user_email']    = "nullable|in:true,false";
        $param_rules['user_number']  = "nullable|in:true,false";
        $messages['required_unless'] = "The logo field is required";
        $response = $this->__validateRequestParams($request->all(), $param_rules);
        if ($this->__is_error == true){
            return $response;
        }
        //</editor-fold>

        // Prepare additional fields
        $addFields = [
            'report_name' =>  $request->report_name,
            'is_footer_user_name'   =>  $request->user_name == 'true' ? 1 : 0,
            'is_footer_user_email'  =>  $request->user_email == 'true' ? 1 : 0,
            'is_footer_user_phone'  =>  $request->user_number == 'true' ? 1 : 0,
        ];

        // Check if a new cover image is uploaded
        if ($request->hasFile('cover_image')) {
            // Upload and store the new cover image
            $uploadedLogo = $this->__moveUploadFile(
                $request->file('cover_image'),
                md5($request['email'] . $request['device_token']),
                "{$this->templatesPath}/cover"
            );
            $addFields['report_cover_image'] = "cover/" . $uploadedLogo;
        } elseif (!$request->input('image_set')) {
            // If no new image is uploaded and image_set is false, remove the existing cover image
            $addFields['report_cover_image'] = null;
        }

        // Save or update the company report with the new or existing image details
        CompanyReport::updateOrCreate(['company_id' => $request->company_id], $addFields);

        $this->__setFlash('success', 'Store cover added successfully');
        $this->__is_paginate = false;
        $this->__is_collection = false;
        return $this->__sendResponse('Tag', [], 200, 'Tag added successfully.');
    }
    public function storeIntroduction(Request $request){
        $this->__view = 'admin/reports';
        $this->__is_redirect = true;

//        dd($request->all());

        //<editor-fold desc="Validation">
        // $param_rules['title'] = "required|min:3|max:100";
        // $param_rules['editor1'] = "required|min:3";
        $param_rules['id'] = "nullable|int";
        $messages['editor1.required'] = "The Introduction field is required";
        $response = $this->__validateRequestParams($request->all(), $param_rules);
        if ($this->__is_error == true){
            $error = \Session::get('error');
            $this->__setFlash('danger','Not Added Successfully' , $error['data']);
            return $response;
        }
        //</editor-fold>

        $request['content'] = $request['editor1'];

        $where = ['id' => $request->template_id,'company_id' => $request->company_id , 'identifier' => 'introduction'];
        $cReport = ReportTemplate::where('company_id',$request->company_id)->whereNull('deleted_at')->where('identifier','introduction')->first();
        if($cReport){
        $cReport['content'] = $request['editor1'];
        $cReport->save();
        $this->__setFlash('success', 'Updated Successfully');
        } else {
            $reportTemplate = new ReportTemplate();
            $cReport = $reportTemplate->firstOrNew($where);
            $cReport->fill($request->all());
            $cReport->save();
            $this->__setFlash('success', 'Added Successfully');
        }

        $this->__is_paginate = false;
        $this->__is_collection = false;
        return $this->__sendResponse('Tag', [], 200,'Introduction added successfully.');
    }

    public function getIntroduction(Request $request){

        $report = ReportTemplate::find($request->id);


        $this->__is_ajax = true;
        $this->__is_paginate = false;
        $this->__is_collection = false;
        $this->__collection = false;
        return $this->__sendResponse('Tag',$report, 200,'Introduction added successfully.');
//        return response()->json(['json' => true]);
    }

    public function deleteIntroduction(Request $request){

        $report = ReportTemplate::where('id',$request->id)->delete();

        $this->__is_ajax = true;
        $this->__is_paginate = false;
        $this->__is_collection = false;
        $this->__collection = false;
        return $this->__sendResponse('Tag',$report, 200,'Introduction deleted successfully.');
//        return response()->json(['json' => true]);
    }

    public function companyColor(Request $request)
    {
       \DB::table('company_reports')
          ->where('company_id',$request['company_id'])
          ->update([
             'primary_color'   => $request['primary_color'],
             'secondary_color' => $request['secondary_color'],
          ]);
       return redirect()->back()->with('success','Company color added successfully');
    }

    public function companyTermsConditions(Request $request)
    {
        //delete old data
        \DB::table('report_templates')
            ->where('company_id',$request['company_id'])
            ->where('identifier','terms_conditions')
            ->delete();
        //insert new data
        \DB::table('report_templates')
            ->insert([
               'company_id' => $request['company_id'],
               'identifier' => 'terms_conditions',
               'content'    => $request['editor2']
            ]);
        return redirect()->back()->with('success','Company terms & conditions added successfully');
    }

    public function reportIntroduction(Request $request)
    {
         //delete old data
        \DB::table('report_templates')
            ->where('company_id',$request['company_id'])
            ->where('identifier','introduction')
            ->delete();
        //insert new data
        \DB::table('report_templates')
            ->insert([
               'company_id' => $request['company_id'],
               'identifier' => 'introduction',
               'title'      => $request['title'],
               'content'    => $request['editor1']
            ]);
        return redirect()->back()->with('success','Report introduction added successfully');
    }

    public function saveDocument(Request $request)
    {
        $uploadedDocument = '';
        if ($request->hasFile('document')) {
            // $obj is model
            $uploadedDocument = $this->__moveUploadFile(
                $request->file('document'),
                md5(time() . uniqid()),
                "{$this->templatesPath}document"
            );
        }
         \DB::table('report_templates')
            ->insert([
               'company_id' => $request['company_id'],
               'identifier' => 'documents',
               'title'      => $request['title'],
               'path'       => !empty($uploadedDocument) ? "{$this->templatesPath}/document/" . $uploadedDocument : NULL,
            ]);
        return redirect()->back()->with('success','Document added successfully');
    }

    public function deleteDocument(Request $request)
    {
         $path = $request['path'];
         \DB::table('report_templates')
            ->where('company_id',$request['company_id'])
            ->where('identifier','documents')
            ->whereRaw("md5(path) = '$path' ")
            ->delete();
         return response()->json(['code' => 200, 'message' => 'Document deleted successfully']);
    }

    public function storeOwnerAuthorization(Request $request)
    {
        $json_data = [];
        if( !empty($request['section_item']) ){
           for( $i=0; $i < count($request['section_item']); $i++ )
           {
               $json_data['section_item']['item'][]  = $request['section_item'][$i];
               $json_data['section_item']['price'][] = $request['section_price'][$i];
           }
        } else {
            $json_data['section_item']['item'][]  = [];
            $json_data['section_item']['price'][] = [];
        }
        $json_data['item_option'] = !empty($request['item_option']) ? $request['item_option'] : [];
        \DB::table('company_reports')
            ->where('company_id',$request['company_id'])
            ->update([
                'estimate_terms'    => $request['estimate_terms'],
                'footer_disclaimer' => $request['footer_disclaimer'],
                'json_data'         => !empty($json_data) ? json_encode($json_data) : null
            ]);
        return redirect()->back()->with('success','Company terms & conditions added successfully');
    }
    //</editor-fold>

}