{{--Noman--}}
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

    table {
    }

    .image_row td{
        /*padding: 10px 0px;*/
    }
    .image_row td.serial{
        width: 6%;
        text-align: right;
        vertical-align: top;
        /*font-size: 15px;*/
        text-align: center;
    }

    .image_row td.thumbnail{
        width: 50%;
        background-color: #f4f4f5;
        text-align: center;
    }

    .image_row td.details{
        padding-left: 20px;

    }
    
    td.details table tr{
            text-align:left; 
            vertical-align: top;
    }
    td.details table { border-collapse: collapse;}
    td.details table td{
        padding: 4px 2px;
        color: #2e302f;
        font-size: 12px;
        /*border: 1px solid rgb(255, 0, 0);*/
    }

    td.details table td.label{
        font-weight: bold;
        text-align: left;
    }

    /*p.project_name, p.project_date, p.project_inspector,*/
    /*p.project_claim_num, p.project_qty,p.project_tags,*/
    /*p.project_note, p.project_area {*/
    /*    padding-bottom: 10px;*/
    /*}*/

    p.project_note {
        width: 20%;
        font-weight: bold;
    }

    div.thumb_image_container {
        text-align: center;
    }
    img.thumb_image{
        max-height: 350px;
        width: 49.5%;
    }
    /*.page3-tbody-data > p {*/
    /*    text-align: right;*/
    /*    margin-right: 4rem;*/
    /*    font-size: 18px;*/
    /*    color: rgb(73,73,75);*/

    /*}*/
    /*.page3-footer-heading > p {*/
    /*    color: rgb(88,90,90);*/
    /*    font-size: 3rem;*/
    /*    font-weight: bold;*/
    /*    margin: 5rem 0 1rem 2rem;*/
    /*}*/
    hr {
        margin: 0 auto;
        border-top: 5px solid rgb(176,210,76);
        width: 96%;
        margin-left: 2rem;
        margin-top: 1rem;
    }
    /*.annotations-alignment{*/
    /*    text-align:left; */
    /*    vertical-align: top;*/
    /*}*/
    .pdf-annotation {
        text-align: left !important;
        vertical-align: top !important;
    }
    .pdf-annotation td {
            vertical-align: top; /* Align annotation text to the top */
        }
    .watermark_date{
        position:relative;
        top:30% !important;
        margin-top:30% !important;
        z-index:100;
    }
</style>
<!-- Debugging Output -->
<pre>
    
</pre>
<table style="width: 100%; border-collapse: collapse;">
    <tbody>
    @php
    
        $projectMedia = $projectMedia->groupBy('target.type');
        $type = [
            1 => 'Required Photos',
            2 => 'Inspection Areas',
            3 => 'Additional Photos',
        ];
        
    @endphp
    @foreach($type as $typeKey => $typeName)
        
        @if($projectMedia->has($typeKey) && $projectMedia[$typeKey]->isNotEmpty())
        
            <tr>
                <td style="color: #ffffff; background-color: #2e302f; font-size: 12px; font-weight: bold; text-align: left; padding: 8px 0px 8px 10px; text-transform: uppercase;" colspan="3">
                    {{ $typeName }}
                </td>
            </tr>

            @foreach($projectMedia[$typeKey] as $mediaKey => $mediaItem)
                @php
                    // Check if tags data is available
                    //dd($mediaItem['tags']);

                    $tags = collect($mediaItem['tags'])->sortBy('id');
                    $tagsImploded = $tags->pluck('name')->implode(', ');
                    $qtyImploded = $tags->pluck('qty')->implode(', ');
                @endphp
                <tr>
                    <td style="height: 10px;"></td>
                </tr>
                <tr class="image_row">
                    <td class="serial" style="padding: 0;">
                        <table style="border-collapse: collapse; width: 100%;">
                            <tr>
                                <td style="padding: 8px 7px; background-color: #2e302f; color:white; font-weight: bold;">
                                    <p>{{ $mediaKey+1 }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    
                    <td class="thumbnail">
                        
                        <span class="watermark_date" style="padding: 8px 7px;font-weight: bold; background-color:#fff;font-size:10px;">
                            Date: {{ print_r($project['inspection_date'], true) }}, Lat: {{ print_r($project['latitude'], true) }}, Long: {{ print_r($project['longitude'], true) }}
                            
                        </span>
                        <div class="thumb_image_container">
                            <!--<div class="watermark" style="width:150px;background-color:#fff;">-->
                                
                                <!--<div class="watermark_loc" style="font-size:10px">-->
                                    
                                <!--</div>-->
                            <!--</div>-->
                            <img class="thumb_image"
                                 src="{{ $mediaItem->image_url }}"
                                 alt=""
                            />
                        </div>
                    </td>
                    <td class="details">
                        <table>
                            <tr>
                                <td class="label">Photo Area:</td>
                                <td>{{ $mediaItem->target->name }}</td>
                            </tr>
                            @if(in_array($mediaItem->target->type, [2]))
                                <tr>
                                    <td class="label">Photo Tag:</td>
                                    <td>{{ $tagsImploded }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Quantity:</td>
                                    <td>{{ $qtyImploded }}</td>
                                </tr>
                            @endif
                            <tr class="pdf-annotation">
                                <td class="label">Annotation:</td>
                                <td>{{ $mediaItem->note }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="height: 10px;"></td>
                </tr>
            @endforeach
        @elseif($typeKey == 3)
            @if(request('additional') == 1)
                <tr>
                    <td style="color: #ffffff; background-color: #2e302f; font-size: 12px; font-weight: bold; text-align: left; padding: 8px 0px 8px 10px; text-transform: uppercase;" colspan="3">
                        {{ $typeName }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px;">
                        <p>No additional photos added yet.</p>
                    </td>
                </tr>
            @else
                <!-- Add a new page with the title "Additional Photos" -->
                <tr>
                    <td colspan="3" style="page-break-before: always; color: #ffffff; background-color: #2e302f; font-size: 12px; font-weight: bold; text-align: left; padding: 8px 0px 8px 10px; text-transform: uppercase;">
                        {{ $typeName }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px;">
                        <p>No additional photos added yet.</p>
                    </td>
                </tr>
            @endif
        @endif
    @endforeach
    </tbody>
</table>