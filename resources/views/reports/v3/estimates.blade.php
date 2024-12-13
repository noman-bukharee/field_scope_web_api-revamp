<style>

    table {
        /*margin: auto;*/
        /*border-collapse: collapse;*/
        padding: 0;
        margin: 0;
    }
    .page6-inspection > p {
        margin-top: 5rem;
        font-size: 2.5rem;
        text-transform: uppercase;
        color: #0000FF;
    }

    .bk_border{ border: 1px solid;}
    .re_border{ border: 1px solid red;}
    .gr_border{ border: 1px solid darkgreen;}
    .bl_border{ border: 1px solid darkblue;}
    .ye_border{ border: 1px solid darkgoldenrod;}

    .bk_bg{ background-color: #777777;}
    .re_bg{ background-color: #ff6767;}
    .gr_bg{ background-color: #78ce5c;}
    .bl_bg{ background-color: #7575ff;}
    .ye_bg{ background-color: #f8c665;}

    .container_table, .container_table > td {
        width: 100%;
    }

    .subtotal td {
        padding-top: 5px;
        color: {{$companyDetails->secondary_color}};
        font-size: 11px;
        font-weight: bold;
    }

    .estimates tr td,.estimates tr th{
        width: {{$breakdownColWidth}}px;
        box-sizing: border-box;
        padding: 7px 3px;
        /*border: 1px solid rgba(128, 128, 128);*/
        /*color: #2e302f;*/
    }

    .estimates tr.breakdown-row td{
        font-size: 13px;
    }
    .estimates tr.annotation-row td{
        font-size: 10px;
    }

    .estimates tr th{
        text-align: left;
        color: #FFFFFF;
        text-transform: uppercase;
    }

    .estimates tr.breakdown-row td{
        text-align: left;
        /*border: 1px solid rgb(0, 173, 10);*/
    }

    .estimates tr th.line-total,
    .estimates tr td.line-total{
        text-align: right;
        padding-right: 10px;
    }


    .estimates tr.subtotal-row td{
        font-size: 14px;
    }

</style>
@php
$subtotals = [];
//$estimates[0]['subtotal'] = 2;


@endphp
@foreach($estimates AS $estKey => $estItem)

<caption class=""
        style="
						font-size: 12px;
						color: black;
						font-weight: bold;
						text-align: left;
						padding: 5px 0px 0px 0px;
					"
>
   {{strtoupper($estItem['category']['name'])}}
</caption>
<table class="estimates"
        style="
				/*width: 400px;*/
				margin: 0 auto 20px auto;
				/*padding: 0px 0px 10px 0px;*/
				/*border: 1px solid rgba(0, 0, 0, 0.193);*/
				border-collapse: collapse;
			"
>
    <thead>
    <tr style="background-color: rgba(46,48,47,255)">
        <th style="text-align: left;
                            padding-left: 10px;">
            Tags
        </th>
        <th>
            Qty
        </th>
        @if($breakdown['units_of_measure'])<th

        >
            UOM
        </th> @endif
        @if($breakdown['material_cost'])<th

        >
            Material
        </th> @endif
        @if($breakdown['labor_cost'])<th

        >
            Labor
        </th> @endif
        @if($breakdown['equipment_cost'])<th

        >
            Equipment
        </th> @endif
       @if($breakdown['supervision_cost']) <th

        >
            Supervision
        </th> @endif
       @if($breakdown['margin_%']) <th

        >
            Margin
        </th> @endif
       @if($breakdown['sales_tax']) <th

        >
            Sales Tax
        </th> @endif
       @if($breakdown['line_item_total']) <th
                class="line-total"
        >
            Total
        </th> @endif
    </tr>
    </thead>
    <tbody>

    @php
        $subTotal = 0;
        $rowCountLimit = 6;
        $rowCount = count($estItem['tags']);
        $estItem['subtotal'] = 0;

        $subtotalBackgroundColor = ($rowCount % 2) > 0 ? "#ffffff"   :    "#f4f4f5";
    @endphp
        @foreach($estItem['tags'] AS $tagsKey => $tagItem)
        @php
            $salesTax    = number_format($tagItem['price']+$projectDetails['sales_tax'],2) > 0 ? number_format($tagItem['price']+$projectDetails['sales_tax'],2) : 0 ;
            //$lineTotal    += ($tagItem['price']* $tagItem['qty']);
            $lineTotal = number_format((($tagItem['price']+$salesTax) * $tagItem['qty']),2);

            $subTotal += (($tagItem['price']+$salesTax) * $tagItem['qty']);
            $estItem['subtotal'] += (($tagItem['price']+$salesTax) * $tagItem['qty']);

            $rowBackgroundColor = ($tagsKey % 2) > 0 ? "#ffffff"  :  "#f4f4f5" /* odd */ ;

        @endphp

        {{-- Breakdown Row--}}
        <tr class="breakdown-row" style="background-color: {{$rowBackgroundColor}};">
            <td
                    style="
                            width:150px;
                            text-align: left;
                            padding-left: 10px;
						"
            >
                <span style="font-weight: bold;">{{$tagItem['name']}}</span>
            </td>
            <td class=""
                    style="
                    width: 10px;
						"
            >
                {{$tagItem['qty']}}
            </td>
              @if($breakdown['units_of_measure'])<td
            >
                {{!empty($tagItem['uom']) ? $tagItem['uom']['title'] : "N/A"}}
            </td> @endif
              @if($breakdown['material_cost'])<td

            >
                ${{number_format($tagItem['material_cost'],2)}}
            </td> @endif
              @if($breakdown['labor_cost'])<td

            >
                ${{number_format($tagItem['labor_cost'],2)}}
            </td> @endif
              @if($breakdown['equipment_cost'])<td

            >
                ${{number_format($tagItem['equipment_cost'],2)}}
            </td> @endif
             @if($breakdown['supervision_cost'])<td

            >
                ${{number_format($tagItem['supervision_cost'],2)}}
            </td> @endif
             @if($breakdown['margin_%'])<td

            >
                {{$tagItem['margin']}}%
            </td> @endif
             @if($breakdown['sales_tax'])<td

            >
                ${{$salesTax}}
            </td> @endif
             @if($breakdown['line_item_total'])<td
                class="line-total"
            >
                ${{$lineTotal}}
            </td> @endif
        </tr>

        {{-- Annotation Row--}}
        <tr class="annotation-row" style="background-color: {{$rowBackgroundColor}};">
            <td
                    style="padding-left: 10px;
							display: inline;
						" colspan="10"
            >
{{--                <span style="font-weight: bold;">{{$tagItem['name']}}</span>--}}
            {{$tagItem['annotation']}} <!--The base layer of the roof, usually made of plywood-->
            </td>
        </tr>

        @if(false)
    <tr>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            <p>Sheathing</p>
            <p>The base layer of the roof, usually made of plywood</p>
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            4
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            Sq. f
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $40
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $30
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $100
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $80
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            25%
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            32.50
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $293
        </td>
    </tr>
    <tr>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            <p>Sheathing</p>
            <p>The base layer of the roof, usually made of plywood</p>
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            4
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            Sq. f
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $40
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $30
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $100
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $80
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            25%
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            32.50
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $293
        </td>
    </tr>
    <tr>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            <p>Sheathing</p>
            <p>The base layer of the roof, usually made of plywood</p>
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            4
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            Sq. f
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $40
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $30
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $100
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $80
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            25%
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            32.50
        </td>
        <td
                style="
							padding: 10px;
							border: 1px solid rgba(128, 128, 128);
							border-top: 0;
							border-left: 0;
							border-collapse: collapse;
						"
        >
            $293
        </td>
    </tr>
    @endif
    @endforeach {{-- End of Tag --}}


    {{-- Subtotal Row --}}
    <tr class="subtotal-row" style="background-color: {{$subtotalBackgroundColor}}; ">
{{--        <td--}}
{{--                colspan="{{$breakdownColCount-1}}"--}}
{{--                style="--}}
{{--                text-align: right;--}}
{{--                font-weight: bold;--}}
{{--            "--}}
{{--        >--}}
{{--            Sub Total--}}
{{--        </td>--}}

        <td
            style="
                text-align: right;
                font-weight: bold;
                padding-right: 10px;
            "
            colspan="{{$breakdownColCount}}"

        >
            Sub Total ${{ number_format($estItem['subtotal'],2) }}
        </td>
    </tr>
    </tbody>
</table>
@if($rowCount >= $rowCountLimit )
    <pagebreak/>
@endif
@endforeach {{-- End of estimates --}}

<table style="
				width: 100%;
				margin: 0 auto 50px auto;
				padding: 0px 0px 20px 0px;
				border: 1px solid rgba(0, 0, 0, 0.193);
				border-collapse: collapse;
">
<tbody>

    <tr style="background-color: #2e302f;">
        <td
                style="
                                padding: 10px;
                                /*border: 1px solid rgba(128, 128, 128);*/
                                border-collapse: collapse;
                                font-weight: bold;
                                display: inline;
                                color: white;
                                width: 120px;
                                text-align: right;
                            "
        >
            Grand Total ${{ number_format(collect($estimates)->sum('subtotal'),2) }}
        </td>
    </tr>
</tbody>
</table>


<pagebreak/>
@php
// dd('$ownerAuthorization',$ownerAuthorization);
@endphp
@include("reports.v3.owner_authorization",['subTotals' => $estimatesTotal ])