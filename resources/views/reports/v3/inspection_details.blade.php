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

    tr.title-row td{
        text-align: left;
    }
</style>
<table class="" style="border-collapse: collapse; width: 100%; margin: 0 auto; padding: 0;">
    <tbody>

    @foreach($surveyGrouped AS $surveyGroupKey => $surveys)
        @php
            // $paddingTopOfTitle = $loop->last ? 20 : 9;
        @endphp
        <tr class="title-row">
            <td style="
                    /*color: #2e302f;*/
                    color: #ffffff;
                    background-color: #2e302f;
                    font-size: 12px;
                    font-weight: bold;
                    text-align: left;
                    padding: 8px 0px 8px 10px;
                    text-transform: uppercase;
                ">
                INSPECTION DETAIL - {{strtoupper($surveyGroupKey)}}
            </td>
        </tr>
        <tr><td style="height: 10px;"></td></tr>
        @foreach($surveys AS $surveyKey => $surveyItem)
            @php
                $paddingBottomOfAnswer = $loop->last ? 20 : 9;
            @endphp
            <tr>
                <td class=""
                        style="
							padding: 6px 0px 6px 10px;
							font-size: 14px;
							font-weight: bold;
							background-color: #f4f4f5;
							color: #2e302f;
						"
                >
                    Q{{$surveyKey+1}} - {{$surveyItem['query']}}
                </td>
            </tr>
            <tr>
                <td style="font-size: 12px; padding: 3px 10px {{$paddingBottomOfAnswer}}px 10px; color: #2e302f;">
                    @if($surveyItem['type'] == 'sign')
                        <p>
                            <img style="width: 250px; border: solid 1px #a9a9a9;" src="{{url("uploads/media/".$surveyItem['pivot']['response'])}}" />
                        </p>
                    @else
                        {{$surveyItem['pivot']['response'] ? $surveyItem['pivot']['response'] : "N/A"}}
                    @endif


                </td>
            </tr>

        @endforeach
    @endforeach



{{--    <tr>--}}
{{--        <td style="padding-top: 30px; font-size: 20px; padding-bottom: 10px">--}}
{{--            Q2-WHAT TYPE OF SIDING IS THERE ON THE STRUCTURE?--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td style="font-size: 20px; padding-bottom: 80px; color: #000000cb">--}}
{{--            This is a popular and cost-effective option. it is durable,--}}
{{--            low-maintenance, and available in various colors and styles.--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td style="font-size: 20px; padding-bottom: 10px">--}}
{{--            Q3- IS THERE A HISTORY OF LEAKS OR OTHER ISSUES?--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td style="font-size: 20px; padding-bottom: 120px; color: #000000cb">--}}
{{--            No--}}
{{--        </td>--}}
{{--    </tr>--}}
    </tbody>
</table>