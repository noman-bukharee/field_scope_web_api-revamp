<style>
    .bk_border{ border: 1px solid;}
    .re_border{ border: 1px solid red;}
    .gr_border{ border: 1px solid darkgreen;}
    .bl_border{ border: 1px solid darkblue;}
    .ye_border{ border: 1px solid darkgoldenrod;}

    .bk_bg{ background-color: #777777;}
    .re_bg{ background-color: red;}
    .gr_bg{ background-color: darkgreen;}
    .bl_bg{ background-color: darkblue;}
    .ye_bg{ background-color: darkgoldenrod;}

    #own_auth > tbody > tr > td {

    }
    #own_auth, #trade_items , #optional_upgrades, #grand_total, #special_instruction, #footer_disclaimer {
        width: 100%;
    }
    #trade_items tr{
        /*background-color: #00ade6;*/
        width: 100%;
    }

    .trade_break_down{
        font-size: 9px;
        /*border: 1px solid red;*/
    }

    #grand_total{
        margin-bottom: 30px;
    }

    #optional_upgrades td{
        border-bottom: solid 2px  #ededed;
    }


    table.estimate-terms td{
        padding: 0;
    }

    caption{
        font-size: 12px;
        color: black;
        font-weight: bold;
        text-align: left;
        padding: 5px 0px 0px 0px;
        text-transform: uppercase;
    }

    .trade-items tr th.label,
    .optional-upgrades th.label,
    .options th.label,

    .trade-items tr td.label,
    .optional-upgrades td.label,
    .options td.label{
        text-align: left;
    }

    .grand-total td.label{
        text-align: right;
    }

    .trade-items tr th.value,
    .optional-upgrades th.value,
    .options th.value,

    .trade-items tr td.value,
    .optional-upgrades td.value,
    .options td.value,
    .grand-total td.value {
        text-align: right;
    }

    .trade-items tr th,
    .optional-upgrades th,
    .options th,
    .grand-total th{
        color: #FFFFFF;
        text-transform: uppercase;
        background-color: rgba(46,48,47,255);
        font-size: 10px;
    }

    .trade-items tr th,
    .trade-items tr td,
    .optional-upgrades th,
    .optional-upgrades td,
    .options th,
    .options td,
    .grand-total th,
    .grand-total td{
        padding: 5px 10px;
        font-size: 10px;
        text-align: center;
        /*border: 1px solid rgba(128, 128, 128);*/
        /*font-size: 16px;*/
    }

    .trade-items       tbody tr:nth-child(odd),
    .optional-upgrades tbody tr:nth-child(odd),
    .options           tbody tr:nth-child(odd){
        background-color: #f4f4f5;
    }

    .trade-items tr td.value{
        text-align: right;
    }

    .signature td {
        font-size: 22px;
    }

</style>

{{--    Estimate Terms  Section --}}
<table class="estimate-terms"
        style="
            width: 100%;
            border-collapse: collapse;
        ">
    <tr class="">
        <td class=""
                style="text-transform:uppercase;">
            {{ucwords($companyDetails['estimate_terms'])}}
        </td>
    </tr>
</table>

@if($ownerAuthorization['item_options']->where('value','<>','')->isNotEmpty())
    <caption class=""
    >
        Options
    </caption>
    <table class="options" style="
                width: 100%;
				margin: 0 auto 30px auto;
				padding: 0px 0px 20px 0px;
				border-collapse: collapse;
">
        <thead>

        <tr style="background-color: rgba(46,48,47,255)">
            <th class="label">
                Items
            </th>
            <th
                    class="value"
                    style="
							width: 300px;
						"
            >
                Selections
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($ownerAuthorization['item_options'] AS $item)
            @if(!empty($item['value']))

                <tr>
                    <td class="label">
                        <p>{{$item['title']}} </p>
                        <!--            <p>The base layer of the roof, usually made of plywood</p>-->
                    </td>

                    <td class="value">
                        {{$item['value']}}
                    </td>
                </tr>
            @endif
        @endforeach

        </tbody>
    </table>
@endif

@if($ownerAuthorization['categories']->where('selected',true)->isNotEmpty())
<caption style=""
>
    Trade Selections
</caption>
<table class="trade-items" style="
                width: 100%;
				margin: 0 auto 5px auto;
				padding: 0px 0px 20px 0px;
				border-collapse: collapse;
			" >
    <thead>

    <tr style="background-color: rgba(128, 128, 128, 0.427)">
        <th style="width: 30px;"
        >

        </th>
        <th class="label"
        >
            Areas
        </th>
        <th
                class="value"
                style="width: 100px;"
        >
            Totals
        </th>
    </tr>
    </thead>
    <tbody>

    @php
        $ownerAuthorization['categories'] = collect($ownerAuthorization['categories'])->keyBy('id');
        $url = url("assets/images/checkmark-img.png");
        $grandTotal = 0;
        $totalTradesBackgroundColor  = (count($estimates) % 2) > 0 ? "#ffffff"   :    "#f4f4f4";

    @endphp
    @foreach($estimates AS $estimateItem)
        @php
            if($ownerAuthorization['categories'][$estimateItem['category']['id']]['selected'] || $ownerAuthorization['categories'][$estimateItem['category']['parent']['id']]['selected']){
                $grandTotal += $estimateItem['subtotal'];
            }
        @endphp
{{--    @if($ownerAuthorization['categories'][$estimateItem['category']['id']]['selected'] || $ownerAuthorization['categories'][$estimateItem['category']['parent']['id']]['selected'])--}}
    <tr >
        <td class="">
            @if($ownerAuthorization['categories'][$estimateItem['category']['id']]['selected'] || $ownerAuthorization['categories'][$estimateItem['category']['parent']['id']]['selected'])
                <img src="{{url("assets/images/checkmark-img.png")}}"
                     style="width: 10px;border: #2e302f 1px solid ;"
                />
            @endif

        </td>
        <td class="label" >
            <p>{{$estimateItem['category']['name']}} </p>
<!--            <p>The base layer of the roof, usually made of plywood</p>-->
        </td>

        <td class="value" >
            ${{number_format($estimateItem['subtotal'],2)}}
        </td>
    </tr>
{{--    @endif--}}
    @endforeach
    <tr style="background-color: {{$totalTradesBackgroundColor}};" >
        <td class="value"
            style="font-weight: bold;"
            colspan="3"
            >Trades Total ${{number_format($grandTotal,2)}}</td>
    </tr>

    </tbody>
</table>
@endif

@if(!empty($ownerAuthorization['credit_disclaimer']) && !empty($companyDetails['is_disclaimer']))
{{-- Credit Disclaimer --}}
<table style="
				width: 900px;
				margin: 0 auto 10px auto;
				padding: 0 20px;
				border-collapse: collapse;
">
    <tbody>
    <tr >
        <td style="
							padding: 10px;
							border: 0px solid rgba(128, 128, 128);
							border-collapse: collapse;
							text-align: right;
						"
        >
            {{ucwords($companyDetails['credit_disclaimer'])}}
        </td>
    </tr>
    </tbody>
</table>
@endif

@php
      // dd($ownerAuthorization['section_items']);
$sectionItemsTotal = 0;
$totalUpgradesBackgroundColor  = (count($ownerAuthorization['section_items']) % 2) > 0 ? "#ffffff"   :    "#f4f4f4";
@endphp

@if($ownerAuthorization['section_items']->where('value','<>','')->isNotEmpty())
<caption style="">
    Optional Upgrades
</caption>
<table class="optional-upgrades" style="
                width: 100%;
				margin: 0 auto 50px auto;
				padding: 0px 0px 20px 0px;
				border-collapse: collapse;

">
    <thead style="background-color: rgba(128, 128, 128, 0.427);">

    <tr style="background-color: rgba(128, 128, 128, 0.427)">
        <th style="width: 30px;"
        >

        </th>
        <th  class="label"
        >
            Areas
        </th>

        <th
                class="label"
                style="

						"
        >
            Qty
        </th>
        <th class="label"
                style="

						"
        >
            Price
        </th>
        <th
                class="value"
                style="
							width: 100px;
						"
        >
            Total
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($ownerAuthorization['section_items'] AS $item)
        @php
            if(!empty($item['value']) && !empty($item['selected']) ){
                $sectionItemsTotal += ($item['price']*$item['value']);
            }
        @endphp


            <tr >
                <td>
                    @if(!empty($item['selected']) )
                    <img src="{{url("assets/images/checkmark-img.png")}}"
                         style="width: 10px;border: #2e302f 1px solid ;"
                    />
                        @endif
                </td>

                <td class="label" >
                    <p>{{$item['title']}} </p>
                    <!--            <p>The base layer of the roof, usually made of plywood</p>-->
                </td>

                <td class="label">
                    {{$item['value']}}
                </td>

                <td class="label">
                    ${{number_format($item['price'],2)}}
                </td>

                <td class="value">
                    ${{number_format($item['price']*$item['value'],2) }}
                </td>
            </tr>


    @endforeach
    <tr style="background-color: {{$totalUpgradesBackgroundColor}}">
        <td class="value" style="font-weight: bold;" colspan="5">
            Upgrades Total ${{number_format($sectionItemsTotal,2)}}
        </td>
    </tr>
    </tbody>
</table>
@endif

{{--Grand Total (Trade Items + Upgrades)--}}
<table class="grand-total" style="
				width: 100%;
				margin: 0 auto 50px auto;
				padding: 0px 0px 20px 0px;
				border-collapse: collapse;
">
    <tbody>
    <tr style="background-color: #f4f4f4;">

        <td class="value" style=" font-weight: bold; width: 100px;"
        >
            Trade Item Total ${{ number_format($grandTotal,2) }}
        </td>
    </tr>
    <tr style="background-color: #f4f4f4;">
        <td
                class="value"
                style="font-weight: bold;"
        >
            Upgrades Total ${{ number_format($sectionItemsTotal,2) }}
        </td>
    </tr>
    <tr style="background-color: rgb(43,43,44);">
        <td
                class="value"
                style="
                    font-weight: bold;
                    color: white;
                "
        >
            Grand Total ${{ number_format($grandTotal + $sectionItemsTotal,2) }}
        </td>
    </tr>
    </tbody>
</table>



<caption class="heading"
>
    Special Instructions
</caption>
<table class=""
        style="
                width: 100%;
				margin: 0 auto 50px auto;
				padding: 10px 0px 20px 0px;
				border-collapse: collapse; ">
    <tbody>
    <tr>
        <td style="
            padding: 10px 0px 5px 0px;
            /*border: 1px solid #2e302f;*/
            border-collapse: collapse;
            height: 80px;
            vertical-align: top;
						"
        >
            <p>{{$ownerAuthorization['special_instruction']}}</p>
            <!--            <p>The base layer of the roof, usually made of plywood</p>-->
        </td>
    </tr>
    </tbody>
</table>

{{--Terms and Conditions--}}

@if(!empty($termsCondition))
    <pagebreak/>
<caption class="heading">
    Terms and Conditions
</caption>
<table style="
                width: 100%;
				margin: 0 auto 50px auto;
				padding: 10px 0px 20px 0px;
				border-collapse: collapse; "
>
    <tbody>
    <tr>
        <td style="padding-top: 2rem;" colspan="2">
            {!! $termsCondition['content'] !!}
{{--            <p style="padding-top: 10px; color: {{$companyDetails->secondary_color}};text-align: justify; font-size: 12px;">--}}
{{--                --}}
{{--            </p>--}}
        </td>
    </tr>
    </tbody>
</table>
@endif

{{--Footer Disclaimer--}}
<caption class="heading"
>
    Disclaimer
</caption>
<table style="
                width: 100%;
				margin: 0 auto 50px auto;
				padding: 0px 0px 20px 0px;
				border-collapse: collapse; ">
    <tbody>
{{--    <tr>--}}
{{--        <td style="--}}
{{--            padding: 5px 10px 10px 10px;--}}
{{--            border-collapse: collapse;--}}
{{--            font-weight: bold;--}}
{{--            font-size: 18px;--}}
{{--						"--}}
{{--        >--}}
{{--            <h4>Disclaimer:</h4>--}}
{{--            <!--            <p>The base layer of the roof, usually made of plywood</p>-->--}}
{{--        </td>--}}
{{--    </tr>--}}
    <tr>
        <td style="
            padding: 10px 0px 5px 0px;
            border-top: 0;
            border-left: 0;
            border-right: 0;
            border-collapse: collapse;
						"
        >
            <p>{{$companyDetails['footer_disclaimer']}}</p>
            <!--            <p>The base layer of the roof, usually made of plywood</p>-->
        </td>
    </tr>
    </tbody>
</table>

{{-- Signature --}}
@if( TRUE /*!empty($report->inspector_sign) || !empty($report->customer_sign)*/)

<table>
    <tbody>
    <tr>
    <td class="">

        <table class="signature" style="
                    width: 100%;
                    margin: 0 auto 50px auto;
                    padding: 0px 0px 0px 0px;
                    border-collapse: collapse; ">
            <tbody>
            <tr>
                <td style="letter-spacing: 2px;font-weight:bold;text-transform:uppercase;">Representative
                </td>
                <td class="" style="text-align: right;">
                    <p style="color: {{$companyDetails->secondary_color}};letter-spacing: 2px;font-weight:bold;text-transform:uppercase;">{{!empty($report->inspector_sign_at) ? \Carbon\Carbon::parse($report->inspector_sign_at)->format('m/d/Y') : 'Signed Date'}}</p>
                </td>
            </tr>
            <tr class="" style="border-radius:4px;">
                <td class="" style="padding:20px 0px 70px 0px;" colspan="2">
                    <img class=""
                         {{--
                             https://via.placeholder.com/600x200.png?text=Sign%20here
                             https://placehold.co/600x200/f4f4f4/ebebeb/png?text=Sign%20Here
                        --}}
                         src="{{ !empty($report->inspector_sign) ? url($report->inspector_sign) : "https://placehold.co/600x150/f4f4f4/ebebeb/png?text=Sign%20Here" }}"
                         style="width: 600px;height: 150px; "
                    />
                </td>
            </tr>
            <tr>
                <td class="" style="letter-spacing: 2px;font-weight:bold;text-transform:uppercase;">Customer</td>
                <td class="" style="text-align: right;">
                    <p style="color: {{$companyDetails->secondary_color}};letter-spacing: 2px;font-weight:bold;text-transform:uppercase;">{{!empty($report->customer_sign_at) ? \Carbon\Carbon::parse($report->customer_sign_at)->format('m/d/Y') : 'Signed Date'}}</p>
                </td>
            </tr>
            <tr class="" style="border-radius:4px;">
                <td style="padding:20px 0px 0px 0px;" colspan="2">
                    <img class=""
                         src="{{ !empty($report->customer_sign) ? url($report->customer_sign) : "https://placehold.co/600x150/f4f4f4/ebebeb/png?text=Sign%20Here" }}"
                         style="width: 600px;height: 150px; "
                    />
                </td>
            </tr>

            </tbody>
        </table>

    </td>
        <td style="width: 50%;"></td>
    </tr>
    </tbody>
</table>
@endif

{{--<table style="--}}
{{--                width: 900px;--}}
{{--				margin: 0 auto;--}}
{{--				padding: 0 20px;--}}
{{--				border: 1px solid rgba(0, 0, 0, 0.193);--}}
{{--				border-collapse: collapse;--}}
{{--">--}}
{{--    <tbody>--}}
{{--    <tr>--}}
{{--        <td style="text-align: right;">Grand Total</td>--}}
{{--        <td>{{$grandTotal}}</td>--}}
{{--    </tr>--}}
{{--    </tbody>--}}
{{--</table>--}}


@if(false)

<table id="own_auth"  style="padding-top: 4rem;border-collapse: collapse;">
    <tbody>
    {{--    Estimate Terms  Section --}}
    <tr class="">
        <td style="text-align:right;text-transform:uppercase;padding-bottom:5px;color: {{$companyDetails->secondary_color}}">
            <table style="width:100%;"> <tbody> <tr><td><p>{{ucwords($companyDetails->estimate_terms)}}</p></td></tr></tbody></table>
        </td>
    </tr>


    {{--    Trade Items + Grand Total + Optional Upgrade  Section --}}
    <tr class="" style="margin: 50px;">
        <td style="padding: 0 0 40px 0; margin: 0;">
            <!-- first inner table -->
            <table style="border-collapse: collapse;" id="trade_items"
                   class="">
                <!-- inner tables header-->
                @php
                     //dd('reset',reset($subTotals)['breakdown'],$subTotals);

                    $colCount = 0;
                    $selectedBreakdowns = reset($subTotals)['breakdown'];

                   $colCount += !empty($selectedBreakdowns['material_cost']) ? 1 : 0;
                   $colCount += !empty($selectedBreakdowns['labor_cost']) ? 1 : 0;
                   $colCount += !empty($selectedBreakdowns['equipment_cost']) ? 1 : 0;
                   $colCount += !empty($selectedBreakdowns['supervision_cost']) ? 1 : 0;
                   $colCount += !empty($selectedBreakdowns['margin_%']) ? 1 : 0;
                   $colCount += !empty($selectedBreakdowns['sales_tax']) ? 1 : 0;
                   $colCount += !empty($selectedBreakdowns['line_item_total']) ? 1 : 0;


                // max cols are 8 including title
                if($colCount<1){
                    $width = "width=100%;";
                }else{
                    $width = "width=500px;";
                }
                @endphp

                <thead>
                <tr style="margin: 0; padding: 0;padding-top: 1rem;" class="">
                    <td colspan="2" class=""
                        style="background-color: #e7e7e7; padding-left:5px; {{$width}}">

                        <p style="text-transform: uppercase;font-weight: bold;color: {{$companyDetails->secondary_color}};letter-spacing: 2px;font-size:16px;">
                            Trade Items</p>
                    </td>

                    @if($selectedBreakdowns['material_cost'])
                        <td class="trade_break_down" style=" padding: 8px 0;background-color: #e7e7e7;width: 65px;"><p
                                    style="color: {{$companyDetails->secondary_color}}; ">Material</p></td> @endif
                    @if($selectedBreakdowns['labor_cost'])
                        <td class="trade_break_down" style=" padding: 8px 0;background-color: #e7e7e7;width: 65px;"><p
                                    style="color: {{$companyDetails->secondary_color}}; ">Labor</p></td> @endif
                    @if($selectedBreakdowns['equipment_cost'])
                        <td class="trade_break_down" style=" padding: 8px 0;background-color: #e7e7e7;width: 65px;"><p
                                    style="color: {{$companyDetails->secondary_color}}; ">Equipment</p></td> @endif
                    @if($selectedBreakdowns['supervision_cost'])
                        <td class="trade_break_down" style=" padding: 8px 0;background-color: #e7e7e7;width: 65px;"><p
                                    style="color: {{$companyDetails->secondary_color}}; ">Supervision</p></td> @endif
                    @if($selectedBreakdowns['margin_%'])
                        <td class="trade_break_down" style=" padding: 8px 0;background-color: #e7e7e7;width: 50px;"><p
                                    style="color: {{$companyDetails->secondary_color}}; ">Margin %</p></td> @endif
                    @if($selectedBreakdowns['sales_tax'])
                        <td class="trade_break_down" style=" padding: 8px 0;background-color: #e7e7e7;width: 65px;"><p
                                    style="color: {{$companyDetails->secondary_color}}; ">Sales Tax</p></td> @endif
                    @if($selectedBreakdowns['line_item_total'])
                        <td class="trade_break_down" style=" padding: 8px 0;background-color: #e7e7e7;width: 85px;"><p
                                    style="color: {{$companyDetails->secondary_color}}; ">Total</p></td> @endif
                </tr>
                </thead>

                <tbody>
                <!-- first inner table first row-->
                @php
                    $grandTotal = 0;
                    $cats = collect($ownerAuthorization['categories'])->keyBy('id')->toArray();

                @endphp
                @forelse($subTotals AS $subKey => $sTotalItem)
                    @php
                        if($cats[$subKey]['selected']){
                            $grandTotal += $sTotalItem['lineItemTotal'];
                        }

                        $image = asset('assets/images/'. (!empty($cats[$subKey]['selected'])? 'checkmark-img.png' :'blank-img.png'));

                    @endphp
                    <tr>
                        <td style="padding:5px 0;border-bottom: solid 2px  #ededed;width: 10px;">
                            <input type="checkbox" name="selected_areas-{{$subKey}}" value="1"
                                   @if(!empty($cats[$subKey]['selected'])) checked="checked"
                                   @endif disabled="disabled"/>
                        </td>
                    <!--                       <td  style="padding:5px 0;border-bottom: solid 2px  #ededed;"><img src="{{$image}}" style="width:10px;height:10px;padding:4px;float:left;border:1px solid #000;"/></td> -->
                        <td colspan=1 class="" style="padding:5px 0px 5px 10px;border-bottom: solid 2px  #ededed;{{$width}}">
                            <p style="color: {{$companyDetails->secondary_color}}; font-size:10px;text-transform:uppercase;float:right;">{{$sTotalItem['name']}} Total</p>
                        </td>

                        @if($sTotalItem['breakdown']['material_cost'])
                            <td class="trade_break_down" style="padding:5px 0;border-bottom: solid 2px  #ededed;"><p
                                        style="color: {{$companyDetails->secondary_color}}; text-transform:uppercase;">

                                    $ {{number_format($sTotalItem['materialTotal'],2)}}</p></td> @endif
                        @if($sTotalItem['breakdown']['labor_cost'])
                            <td class="trade_break_down" style="padding:5px 0;border-bottom: solid 2px  #ededed;"><p
                                        style="color: {{$companyDetails->secondary_color}}; text-transform:uppercase;">
                                    $ {{number_format($sTotalItem['laborTotal'],2)}}</p></td> @endif
                        @if($sTotalItem['breakdown']['equipment_cost'])
                            <td class="trade_break_down" style="padding:5px 0;border-bottom: solid 2px  #ededed;"><p
                                        style="color: {{$companyDetails->secondary_color}}; text-transform:uppercase;">
                                    $ {{number_format($sTotalItem['equipmentTotal'],2)}}</p></td> @endif
                        @if($sTotalItem['breakdown']['supervision_cost'])
                            <td class="trade_break_down" style="padding:5px 0;border-bottom: solid 2px  #ededed;"><p
                                        style="color: {{$companyDetails->secondary_color}}; text-transform:uppercase;">
                                    $ {{number_format($sTotalItem['supervisionTotal'],2)}}</p></td> @endif
                        @if($sTotalItem['breakdown']['margin_%'])
                            <td class="trade_break_down" style="padding:5px 0;border-bottom: solid 2px  #ededed;"><p
                                        style="color: {{$companyDetails->secondary_color}}; text-transform:uppercase;">
                                    {{$sTotalItem['margin']}}%</p></td> @endif
                        @if($sTotalItem['breakdown']['sales_tax'])
                            <td class="trade_break_down" style="padding:5px 0;border-bottom: solid 2px  #ededed;"><p
                                        style="color: {{$companyDetails->secondary_color}}; text-transform:uppercase;">
                                    $ {{number_format($sTotalItem['salesTaxTotal'],2)}}</p></td> @endif
                        @if($sTotalItem['breakdown']['line_item_total'])
                            <td class="trade_break_down" style="padding:5px 0;border-bottom: solid 2px  #ededed;"><p
                                        style="color: {{$companyDetails->secondary_color}}; text-transform:uppercase;">
                                    {{--{{number_format($sTotalItem['lineItemTotal'],2,'.',',')}}--}}
                                    $ {{number_format($sTotalItem['lineItemTotal'],2,'.',',')}}</p></td> @endif
                    </tr>
                @empty
                    <tr>
                        <td><h1>EMPTY</h1></td>
                    </tr>
                @endforelse

                </tbody>
            </table>

            @php
                // 999999999 -> 999 million
                // 9999999999 -> 9 billion

                /** IF greater than 99,999 AND less than */
                if($grandTotal > 99999 && $grandTotal <= 999999999){
                    /*Works Fine till 999 million */
                    $fontSize = "font-size:9px;";
                }else if($grandTotal >= 1000000000) { /** IF >= 1 billion */
                    $fontSize = "font-size:8px;";
                }
            @endphp


            <table id="grand_total" style="">
                <!-- Estimate Grand Total -->
                <tr>
                    <td style="padding-bottom:5px;padding-top:10px;text-align: right">
                        <p style="text-transform:uppercase;letter-spacing:2px;font-weight:bold;color: {{$companyDetails->secondary_color}};">
                            Estimate Grand Total</p>
                    </td>
                    <td class="" style="padding-bottom:5px;padding-top:10px; width:85px;">
                        <p style="{{$fontSize}}font-weight:bold;color: {{$companyDetails->secondary_color}};">
                            ${{number_format($grandTotal,2,'.',',')}}

                        </p>
                    </td>
                </tr>

                @if(!empty($ownerAuthorization['credit_disclaimer']))
                    <tr>
                        <td style="padding-bottom:5px;padding-top:10px;text-align: right">
                            <p style="text-transform:uppercase;letter-spacing:2px;color: {{$companyDetails->secondary_color}};">
                                {{$ownerAuthorization['credit_disclaimer']}}</p>
                        </td>
                    </tr>
                @endif
            </table>


            <table id="optional_upgrades"
                   class=""
                   style="border-collapse: collapse;">
                <!-- second inner table header row-->
                <thead>
                <tr>
                    {{--                    <td style="background-color: #e7e7e7;width:10px;"></td>--}}
                    <td colspan="2" style="background-color: #e7e7e7;padding-left:10px;">
                        <p style="text-transform: uppercase;font-weight: bold;letter-spacing: 2px;font-size:16px;color: {{$companyDetails->secondary_color}};">
                            optional upgrade </p>
                    </td>
                    <td style=" padding: 8px 0;background-color: #e7e7e7;width: 90px;">
                        <p style="color: {{$companyDetails->secondary_color}}; ">Qty</p>
                    </td>
                    <td style=" padding: 8px 0;background-color: #e7e7e7;width: 90px;">
                        <p style="color: {{$companyDetails->secondary_color}}; ">Price</p>
                    </td>
                    <td style=" padding: 8px 0;background-color: #e7e7e7;width: 90px;">
                        <p style="color: {{$companyDetails->secondary_color}}; ">Total</p>
                    </td>
                </tr>
                </thead>

                @php
                    $upgradesTotal = 0;
                @endphp

                <tbody>
                @empty(! $companyDetails['json_data']['section_item']['item'] )
                    @forelse($companyDetails['json_data']['section_item']['item'] as $key => $item)
                        <!-- second inner table first row-->
                        <tr>
                            <td style="padding:5px 0;width: 10px;">
                                {{--                                <img src="{{asset('assets/images/blank-img.png')}}" style="width:10px;height:10px;padding:4px;float:left;border:1px solid #000;"/>--}}
                                <input type="checkbox" name="optional_upgrade-{{$key}}" value="1"
                                       @if(!empty($ownerAuthorization['section_items'][$key]['value'])) checked="checked"
                                       @endif disabled="disabled"/>
                            </td>
                            <td style="padding:5px 0px 5px 10px">
                                <p style="color: {{$companyDetails->secondary_color}}; font-weight:bold;font-size:12px;text-transform:uppercase;">{{$item}}</p>
                            </td>
                            <td style="padding:5px 0;">
                                <p style="color: {{$companyDetails->secondary_color}}; font-weight:bold;font-size:12px;text-transform:uppercase;">
                                    {{ $ownerAuthorization['section_items'][$key]['value'] }}</p>
                            </td>
                            <td style="padding:5px 0;">
                                <p style="color: {{$companyDetails->secondary_color}}; font-weight:bold;font-size:12px;text-transform:uppercase;">
                                    ${{ $companyDetails['json_data']['section_item']['price'][$key]}}</p>
                            </td>
                            @php
                                $total = $ownerAuthorization['section_items'][$key]['value'] * $companyDetails['json_data']['section_item']['price'][$key];
                            @endphp
                            <td style="padding:5px 0;">
                                <p style="color: {{$companyDetails->secondary_color}}; font-weight:bold;font-size:12px;text-transform:uppercase;">
                                    ${{$total}}</p>
                            </td>
                        </tr>
                        @php
                            $selectedSectionItem =  collect($ownerAuthorization['section_items'])->keyBy('id')->toArray();

                           if($ownerAuthorization['section_items'][$key]['value']){
                               $upgradesTotal += $total ;
                           }
                        @endphp
                    @empty
                        <tr>
                            <td>EMPTY</td>
                        </tr>
                    @endforelse
                @endempty

                <!-- second inner table final row-->
                <tr>

                    <td colspan="3" style="text-align:right;padding:10px 10px 10px 0; border: 0;">
                        <p style="text-transform:uppercase;letter-spacing:1.5px;font-weight:bold;color: {{$companyDetails->secondary_color}};">
                            estimate grand total with selected upgrades</p>
                    </td>
                    <td style="padding:10px 10px 10px 0; border: 0;">
                        <p style="letter-spacing:2px;font-weight:bold;color: {{$companyDetails->secondary_color}};">
                            ${{$grandTotal+$upgradesTotal}}</p>
                    </td>
                </tr>

                </tbody>
            </table>
        </td>
    </tr>



    {{-- special_instruction + Product Selection Section--}}
    <tr class="" style="padding: 0; margin: 0;"><td>

        <table  style="width:100%;padding-top: 4rem;border-collapse: collapse;">
            <thead>
            <tr>
                <td  class="" style="width:50%; background-color: #e7e7e7;padding:10px 0px 10px 10px; margin-right: 20px;">
                    <p style="text-transform: uppercase;font-weight: bold;color: {{$companyDetails->secondary_color}};letter-spacing: 2px;font-size:16px;">special instructions </p></td>
                <td  class="" style="background-color: #e7e7e7;padding:10px 0px 10px 10px;">
                    <p style="text-transform: uppercase;font-weight: bold;color: {{$companyDetails->secondary_color}};letter-spacing: 2px;font-size:16px;">
                        Product selections
                    </p>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="" style="width:50%;background-color: #f9f9f9;padding-left: 10px;">
                    <p>
                        {{$ownerAuthorization['special_instruction']}}
                    </p>
                </td>
                <td class="" style="width:50%;padding-left: 10px; vertical-align: top">
                    <table class="" style="border-collapse: collapse;vertical-align: top;width: 100%;">
                        <tbody>
                        @empty(!$companyDetails['json_data']['item_option'])
                            @forelse($companyDetails['json_data']['item_option'] AS $opKey => $opItem)
                                <tr >
                                    <td class="" style="padding:0;border-bottom: 1px solid  #ededed;">
                                        <p style="text-transform: uppercase;font-weight: bold;color: {{$companyDetails->secondary_color}};letter-spacing: 2px;">{{$opItem}}</p>
                                    </td>
                                    <td class="" style="border-bottom: 1px solid  #ededed;padding:0 0 0 10px; margin: 0;">
                                        {{$ownerAuthorization['item_options'][$opKey]['value']}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>EMPTY</td>
                                </tr>
                            @endforelse
                        @endempty
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </td></tr>



    {{--    footer disclaimer Section --}}
    <tr class="" style="padding: 0; margin: 0;" ><td>
            <table class="" id="footer_disclaimer">
                <tr>
                    <td style="padding: 20px 0 20px 0;">
                        <p style="color: {{$companyDetails->secondary_color}};font-size:12px;">
                            {{$companyDetails->footer_disclaimer}}
                        </p>
                    </td>
                </tr>
            </table>
    </td></tr>



    @if(!empty($report->inspector_sign) || !empty($report->customer_sign))
        {{--    Project Details  Section --}}
        <tr class="" style="padding: 0; margin: 0;"><td>
                <table class="" style="width:100%;">
                    <tbody>
                    <tr >
                        <td class="" style="border-bottom: 2px solid {{$companyDetails->primary_color}};width:50%;padding:10px 0px 0px 0px;">
                            <img class=""
                                 src="{{ !empty($report->inspector_sign) ? url($report->inspector_sign) : "https://via.placeholder.com/550x200.png?text=Sign%20here" }}"
                                 style="width: 130px;height: auto;"
                            />
                        </td>
                        <td  style="border-bottom: 2px solid  {{$companyDetails->primary_color}};width:30%;padding:10px 0px 0px 0px;">
                            <p style="color: {{$companyDetails->secondary_color}};letter-spacing: 2px;font-weight:bold;text-transform:uppercase;">{{!empty($report->inspector_sign_at) ? \Carbon\Carbon::parse($report->inspector_sign_at)->format('Y-m-d') : 'Signed Date'}}</p>
                        </td>
                    </tr>
{{--                    <tr><td colspan="2"><hr style="width:100%;height:3px;border-width:0;color:{{$companyDetails->primary_color}};"></td></tr>--}}
                    <tr class="">
                        <td  style="border-bottom: 2px solid {{$companyDetails->primary_color}};width:50%;padding:10px 0px 0px 0px;">
                            <img class=""
                                 src="{{ !empty($report->customer_sign) ? url($report->customer_sign) : "https://via.placeholder.com/550x200.png?text=Sign%20here" }}"
                                 style="width: 130px;height: auto;"
                            />
                        </td>
                        <td  style="border-bottom: 2px solid {{$companyDetails->primary_color}};width:30%;padding:10px 0px 0px 0px;">
                            <p style="color: {{$companyDetails->secondary_color}};letter-spacing: 2px;font-weight:bold;text-transform:uppercase;">{{!empty($report->customer_sign_at) ? \Carbon\Carbon::parse($report->customer_sign_at)->format('Y-m-d') : 'Signed Date'}}</p>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </td>
        </tr>
    @endif
    </tbody>
</table>
@endif