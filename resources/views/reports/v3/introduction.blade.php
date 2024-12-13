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

    td.introduction{
        font-size: 12px;
        text-align: justify;
    }

    caption.heading{
        color: #ffffff;
        background-color: #2e302f;
        font-size: 12px;
        font-weight: bold;
        text-align: left;
        padding: 8px 0px 8px 10px;
        text-transform: uppercase;
    }
</style>

<caption class="heading"
         style=""
>Introduction</caption>
<table class="" style="width: 100%;padding: 10px 0 0 0;">
    <tbody>
    <tr>
        <td class="introduction"
                style="padding: 0;">
            {!!$introduction[0]['content']!!}
        </td>
    </tr>
    </tbody>
</table>