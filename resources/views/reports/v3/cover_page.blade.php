<style>
    .b_border{  border: 1px solid;}
    .re_border{  border: 1px solid red;}
    .gr_border{  border: 1px solid #346b00;}
    .bl_border{ border: 1px solid blue;}
    .ye_border{  border: 1px solid yellow;}
    /*@page {*/
    /*    margin: 5mm 10mm 5mm 10mm;*/
    /*}*/
/*    .services table{
        width: 100%;
        vertical-align: bottom;
        !*border-collapse: collapse;*!
    }
    .services table td{
        font-size: 20px;
        font-weight: bold;
        letter-spacing: 5px;
    }*/

    .bg-overlay::after {
        /*content: '';*/
        /*width: 100%;*/
        /*height: 100%;*/
        /*background-color: rgba(149, 181, 89, 0.6);*/
        /*left: 0;*/
        /*position: absolute;*/
        /*z-index: -1;*/
    }
</style>
<setpageheader value="off"></setpageheader>

{{--<div style="--}}
{{--        width: 100%;--}}
{{--        height: auto;--}}
{{--        /*z-index: 0;*/--}}
{{--        /*position: relative;*/--}}

{{--        background-image: url('{{url("image/report/banner-bg-full.png")}}');--}}
{{--        /*clip-path: polygon(*/--}}
{{--        /*	77% -19%,*/--}}
{{--        /*	224% 71%,*/--}}
{{--        /*	100% 100%,*/--}}
{{--        /*	-51% 41%,*/--}}
{{--        /*	-41% 7%*/--}}
{{--        /*);*/--}}
{{--        background-size: cover;--}}
{{--        background-position: center;--}}
{{--        background-repeat: no-repeat;--}}

{{--        background-color: rgba(200, 200, 200, 0.5);--}}
{{--        ">--}}
{{--    <p--}}
{{--            style="--}}
{{--                /*margin: -300px 30px 0 0;*/--}}
{{--                font-size: 90px;--}}
{{--                color: #fff;--}}
{{--                /*z-index: 999;*/--}}
{{--                height: 900px;--}}
{{--                font-weight: bold;--}}
{{--            "--}}
{{--    >--}}
{{--        Inspection--}}
{{--    </p>--}}
{{--    <p style="--}}
{{--								margin: 0 30px 0 0;--}}
{{--								font-size: 90px;--}}
{{--								color: #fff;--}}
{{--								font-weight: bold;--}}
{{--							">--}}
{{--        Report--}}
{{--    </p>--}}
{{--    <p style="margin: 30px 30px 0 0; font-size: 30px; color: #fff">--}}
{{--        June 8th,2024--}}
{{--    </p>--}}
{{--</div>--}}



<table   class=""      style="
				width: 100%;
				/*display: block;*/
				/*margin: 0 auto;*/
				border-collapse: collapse;
				padding: 0;
			">

    {{--  Banner Row  --}}
    <tr
            class="bg-overlay"
            style="
                    /*z-index: 0;*/
                    /*position: relative;*/
                    /*clip-path: polygon(*/
                    /*	77% -19%,*/
                    /*	224% 71%,*/
                    /*	100% 100%,*/
                    /*	-51% 41%,*/
                    /*	-41% 7%*/
                    /*);*/
                    /*height: 500px;*/

                    {{--background-image: url('{{url("image/report/banner-bg-full.png")}}');--}}
                    {{--background-size: cover;--}}
                    {{--/*background-position: center;*/--}}
                    {{--background-repeat: no-repeat;--}}

                    background-color: #e8e8e8;
                    "
    >
        <td class="" style="
                /*z-index: 999;*/
                /*width: auto;*/
                height: 700px;
                padding: 0;
        /*text-align: left;*/

        {{--background-image:url('{{$companyDetails['report_cover_image']}}');--}}
        {{--background-image:url('{{url("image/report/banner-bg-full.png")}}');--}}
        {{--background-image:url('{{url("https://dummyimage.com/800x700&text=Cover 800x700")}}');--}}
        {{--background-position:center;--}}
        {{--background-repeat:no-repeat;--}}
        {{--background-origin: border-box;--}}
        {{--background-size: cover;--}}
                " colspan="4">

{{--        <img style="--}}
{{--                width: 70%;--}}
{{--                height: auto;--}}
{{--                /*max-height: 600px;*/--}}
{{--                /*background-color: rgba(200, 200, 200, 0.5);*/--}}
{{--             "--}}
{{--                src="{{url("image/report/banner-bg-full.png")}}" />--}}

            <table class="" style="width:100%; text-align: right; vertical-align: top; border-collapse: collapse;">
                <tr>
                    <td class="" style="
                            padding: 20px 40px;
                            height:700px;
                            text-transform:uppercase;
                            background-image:url('{{$companyDetails['report_cover_image']}}');
                            {{--background-image:url('{{url("https://dummyimage.com/800x700&text=Cover 800x700")}}');--}}
                            background-position:center;
                            background-repeat:no-repeat;
                            background-origin: border-box;
                            background-size: cover;
                        ">
                        <p
                                style="
								font-size: 90px;
								color: #fff;
								font-weight: bold
							"
                                class=""
                        >Inspection</p>
                        <p  class=""
                                style="
								font-size: 90px;
								color: #fff;
								font-weight: bold;
							"
                        >Report</p>
                        <p   class="" style="
                                    font-size: 30px;
                                    color: #fff;
                                    font-weight: bold;
                        ">{{Carbon\Carbon::parse($projectDetails['inspection_date'])->format('F jS, Y')}}</p>
                    </td>
                </tr>
{{--                <tr>--}}
{{--                    <td>--}}

{{--                    </td>--}}
{{--                </tr>--}}
            </table>
        </td>
    </tr>
    {{--  Details Row  --}}
    <tr style="">
        <td class=""
            style="width: 8%;"
        ></td>

        <td class="" style="width: 55%;height:423px; padding: 0;">
            <table class="left-panel " style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td class="" style="padding: 20px 0 20px 0px; ">
                        <img style="height: 100px;"
                             src="{{$companyDetails['logo_path']}}"
{{--                             src="https://dummyimage.com/3:1x700&text=Company Logo"--}}

                             alt="" />
                    </td>
                </tr>
                <tr >
                    <td
                            style="
							font-size: 32px;
							padding: 40px 0 20px 0px;
							/*width: 60%;*/
							font-weight: bold;

						"
                    >
                        {{strtoupper($projectDetails['name'])}}
                    </td>

                </tr>
                <tr>
                    <td style="padding-left: 0px">
                        <p>{{$projectDetails['address1']}}</p>
                    </td>

                </tr>
                <tr>
                    <td style="padding: 10px 0px 10px 0px;">
                        <p style="margin: 0">
                            Claim <span style="font-style: italic">#</span> {{$projectDetails['claim_num']}}
                        </p>
                    </td>

                </tr>
                <tr>
                    <td style="padding-left: 0px">

                        <p style="margin: 0">Location Lat: {{$projectDetails['latitude']}}, Long: {{$projectDetails['longitude']}}</p>
                    </td>

                </tr>
            </table>
        </td>
        <td class="" style=" background-color: {{$companyDetails['primary_color']}}; ">
            <table class="right-panel "
                   style="
                   margin: 0;

                   border-collapse: collapse;
                   /*height: 800px;*/
                   /*width: 100%;*/
                   background-clip: content-box;
                ">
<!--                <tr>
                    <td
                            style="display: block; /*margin-top: -124px;*/ /*height: 179px;*/ border: 0"
                    ></td>
                </tr>-->
                <tr>
                    
                    @if(!empty($companyDetails['is_footer_user_name']))
                    <td class=""
                            style="
							padding: 70px 0px 5px 15px;
							/*padding-bottom: 18px;*/
							font-size: 25px;
							font-weight: bold;
							color: #fff;
							/* width: 70%; */
							/*display: block;*/
						"
                    >
                        @php
                            // $projectDetails['assigned_user']['last_name'] = "Mailinatorasdjhasdhjk ashd";
                            // dd(strlen($projectDetails['assigned_user']['first_name']." ".$projectDetails['assigned_user']['last_name']));
                        @endphp
                        {{ucwords($projectDetails['assigned_user']['first_name'])}} {{ucwords($projectDetails['assigned_user']['last_name'])}}
                    </td>
                    @endif
                </tr>
                <tr>
                    @if(!empty($companyDetails['is_footer_user_phone']))
                    <td
                            style="
							/*background-color: #17232d;*/
							color: #fff;
							padding-left: 15px;
							/* width: 70%; */
							/*display: block;*/
						"
                    >
                        {{$projectDetails['assigned_user']['mobile_no']}}
                    </td>
                    @endif
                </tr>
                <tr>
                    @if(!empty($companyDetails['is_footer_user_email']))
                    <td
                            style="
							padding-bottom: 20px;
							color: #fff;
							padding-left: 15px;
						"
                    >
                        {{$projectDetails['assigned_user']['email']}}
                    </td>
                    @endif
                </tr>


                <tr>
                    <td class=""
                            style="
							padding: 20px 15px;
							color: #fff;
							/*padding-left: 15px;*/
						"
                    >
                        {{$companyDetails['address']}}
                    </td>
                </tr>

                <tr>
                    <td class=""
                            style="
							padding: 30px 0px 5px 15px;
						"
                    >
                        <p
                                style="
								margin: 0;
								padding: 0;
								color: #fff;
								padding-top: 20px;
								padding-bottom: 4px;
								font-weight: bold;
								font-size: 18px;
							"
                        >
                            {{$companyDetails['phone']}}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td  class=""
                            style="
							padding: 5px 0px 50px 15px;
						">

                        <p style="margin: 0; color: #fff; padding-bottom: 4px">
                            {{$companyDetails['email']}}
                        </p>
                        <p style="margin: 0; color: #fff; padding-bottom: 20px">
                            {{$companyDetails['website']}}
                        </p>
                    </td>
                </tr>
            </table>
        </td>

        <td class=""
                style="width: 8%;"
        ></td>

    </tr>
</table>


@if(FALSE)
    <table
            style="
				width: 900px;
				margin: 0 auto;
				border-collapse: collapse;
				padding: 0;
			"
            border="0"
            cellspacing="0"
            cellpadding="0"
    >

        <tbody>

        </tbody>
        <tfoot>
        <tr style="background-color: #d1d3d4">
            <td colspan="2">
                <p style="padding: 20px; margin: 0">
                    Powered by :<span style="font-weight: bold">FIELDSCPOE</span>
                </p>
            </td>
        </tr>
        </tfoot>
    </table>




<div  style="display: flex; flex-direction: row; ">
    {{--  Left Section  --}}
    <div
         style="margin-left:60px;width:50.5%; height: 150px;
            background-image: url('{{asset('assets/images/reportimage.png')}}');background-repeat: no-repeat;background-size: 100% 100%;float:left;">

        <div class="g_border" style="height: 200px;">
            <h1 class="" style="
            padding-top: 150px;
    font-family: OpenSans;
    font-size: 40px;
    font-weight: bold;
    line-height: 1.2;
    letter-spacing: 6px;
    text-align: left;
    color: #fff;
    padding-left: 25px;"> {{strtoupper($companyDetails['report_name'])}} </h1>
            {{--        INSPECTION REPORT --}}
            {{--        {{strtoupper($companyDetails['report_name'])}} --}}
            <p style="font-family: OpenSans;
                    font-size: 22px;
                    font-weight: 600;
                    line-height: 1.21;
                    letter-spacing: 2.03px;
                    text-align: left;
                    color: {{$companyDetails->primary_color}};
                    padding-left: 32px;
                    /* padding-right: 100px; */
                    padding-bottom: 300px;">
                {{$projectDetails['inspection_date']}}
            </p>
        </div>
    </div>

    {{--  Right Section  --}}
    <div class="r_border" style="width:40%;  " >
        <img src="{{$companyDetails['logo_path']}}" style="width: 250px; height:80px; margin: 70px 0px 40px 30px;"/>
        <h1 style="margin-left: 30px; font-family: OpenSans;
            font-size: 30px;
            font-weight: 800;
            line-height: 0.39;
            color: {{$companyDetails->secondary_color}};">{{ucwords($projectDetails['name'])}}</h1>
        <p style="margin-left: 30px;
                font-size: 20px;
                color: {{$companyDetails->secondary_color}};
                line-height: 1.3;">
            {{$projectDetails['address1']}}
        </p>
        <p style="margin-left: 30px;
                font-size: 20px;
                color: {{$companyDetails->secondary_color}};
                line-height: 1.3;
                ">
            Claim# {{$projectDetails['claim_num']}} <br>
            Location Lat:{{$projectDetails['latitude']}}, Long: {{$projectDetails['longitude']}}
        </p>
    </div>
</div>

{{-- Below Cover Image Block--}}
<div
        class=""
        style=" margin:-190px 0px 0px 0px;
                background-image: url('{{$companyDetails['report_cover_image']}}');background-repeat: no-repeat;background-size: 100% 100%;
                /*height:200px;*/
              "
    >
    <div class="y_border"
            style="width: 100%; height: 420px;"
    >
        <div class="g_border" style="width: 400px; margin: -50px 0px 0px 450px; padding-top: 0px;color: #fff;">
            <h1 style="margin: 0px;font-size: 30px;">{{$userDetails['first_name']}} {{$userDetails['last_name']}}</h1>
            <p style="margin: 0px;font-size: 18px;">{{$userDetails['phone']}}</p>
            <p style="margin: 0px;font-size: 18px;">{{$userDetails['email']}}</p>
            <p style="margin: 20px 0px;margin-right: 100px;font-size: 18px;">{{$projectDetails['address1']}}</p>
            <p style="margin: 0px;font-size: 18px;">{{$companyDetails['phone']}}</p>
            <P style="margin: 0px;font-size: 18px;">{{$companyDetails['email']}}</P>
            <p style="margin: 0px;font-size: 18px;">{{$companyDetails['website']}}</p>
        </div>

        {{-- Services Block --}}
        <div class="services r_border" style="width: 100%;text-align: center; margin-top: 20px; padding: 0px 40px;
                display: inline; color:{{$companyDetails->primary_color}};">

        <table class="bl_border" >
            <tr><td style="height: 155px;">

                    <table>

                        @php
                            $servicesArr = explode(',',$companyDetails['address']);
                            //dd($companyDetails->primary_color);
                            $serviceCounter = 0;
                        @endphp

                        @foreach($servicesArr AS $servItem)
                            @if($serviceCounter == 0)
                                <tr style="color:grey;" >
                                    @endif
                                <td style="color:{{$companyDetails->primary_color}};" class="y_border">&nbsp;{{strtoupper($servItem)}}</td>
                                    @php  $serviceCounter++; @endphp
                                        @if($serviceCounter == 4)
                                            @php  $serviceCounter = 0@endphp
                                    </tr>
                                @endif

                                @endforeach

                    </table>

                </td></tr>
        </table>


            {{--                <span style="color:#b0d24c;">    &nbsp;SIDING</span>--}}

        </div>

        {{-- Powered By Block --}}
        <div style="width: 100%; text-align: right; margin-top: 40px;" class="y_border">
            <span style="display: inline-block;color: white;
        font-size: 10px; vertical-align: baseline;">Powered By:</span>
            <img src="{{asset('assets/images/footer-logo.png')}}" alt="" style="width: 90px;
        height: 15px;
        margin-right: 20px;vertical-align: middle;">
        </div>
    </div>

{{--    <div class="r_border" style="height: 130px; background-color: #0abca6"></div>--}}
</div>
@endif


{{--<setpagefooter value="off"></setpagefooter>--}}