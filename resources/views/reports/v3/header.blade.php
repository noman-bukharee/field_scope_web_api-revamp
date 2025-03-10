<table class="" style="border-collapse: collapse; width:100%;">
    <thead>
    <tr
            style="
            {{--background-image: url({{url()}});--}}
            /*        background-repeat: no-repeat;*/
            /*        background-size: contain;*/
            /*        background-position: right;*/
            /*        height: 123px;*/
                    "
    >
        <td  class="" style="
        padding-top: 18px;
        /*border: 1px solid red;*/
        vertical-align: middle;
        ">
            <img style="width:10%; padding: 0; margin-top:10px"
                 src="{{$companyDetails->logo_path}}"
{{--                    src="https://dummyimage.com/3:1x700&text=Company Logo"--}}
                 alt="" />
            <p style="padding-top: 18px;">
                {{$companyDetails->name}}
            </p>     
        </td>
        <td class=""
                style="
                text-align: center;
                /*border: 1px solid red;*/
                vertical-align: bottom;
                padding-top: 18px;
                font-size: 12px;
            ">
            <p style="">
                Inspection Date: {{$project['inspection_date']}}
            </p>
        </td>
    </tr>
    
    </thead>
</table>