@extends('frontend.layouts.app')

@section('styles')
    <style>
        .drawing-area{
            position: absolute;
            z-index: 10;
        }

        .canvas-container{
            position: relative; 
            user-select: none;
        }

        .mockup-div{
            width: 450px;
            height: 550px;
            position: relative;
            background-color: #fff;
        }

        #canvas{
            position: absolute;
            left: 0px; 
            top: 0px; 
            user-select: none; 
            cursor: default;
        }
        .loader{
            border-radius: 50%;
            border-top: 3px solid #f8f9fa;
            width: 40px;
            height: 40px;
            -webkit-animation: spin .8s linear infinite; /* Safari */
            animation: spin .8s linear infinite;
        }

        .img-polaroid {
            padding: 0;
            margin: 0;
            border: 2px solid transparent;
            max-height: 92px;
            max-width: 92px;
            min-height: 92px;
            min-width: 92px;

        }

        .img-polaroid:hover {
            cursor: pointer;
            border-color: #00a5f7;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @elseif(Auth::user()->user_type == 'designer')
                            @include('frontend.inc.designer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Start Design')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('collections.index') }}">{{__('Start Design')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <form action="" id="capture_form">
                            <div class="row mt-5">
                                <div class="col-md-3">

                                    {{-- Variation --}}
                                    <div style="background-color: #fff;padding:14px;border-radius: 10px;" class="mb-2">
                                        Variation
                                        <hr>
                                        @if($mockup->preview_1 != null)
                                            <button class="collapsed btn btn-purple" type="button" data-toggle="collapse" data-target="#mockup-div1" aria-expanded="true" aria-controls="mockup-div1">
                                                {{json_decode($mockup->preview_1)->name}}
                                            </button>
                                        @endif
                                        @if($mockup->preview_2 != null)
                                            <button class="collapsed btn btn-info" type="button" data-toggle="collapse" data-target="#mockup-div2" aria-expanded="false" aria-controls="mockup-div2">
                                                {{json_decode($mockup->preview_2)->name}}
                                            </button>
                                        @endif
                                        @if($mockup->preview_3 != null)
                                            <button class="collapsed btn btn-info" type="button" data-toggle="collapse" data-target="#mockup-div3" aria-expanded="false" aria-controls="mockup-div3">
                                                {{json_decode($mockup->preview_3)->name}}
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Colors --}}
                                    <div style="background-color: #fff;padding:14px;border-radius: 10px;" class="mb-2">
                                        <label for="tshirt-color">T-Shirt Color:</label> 
                                        <label for="">Select Colors</label>
                                        <select class="form-control color-var-select" name="colors[]" id="colors" multiple required onchange="selected_colors()">
                                            @foreach (json_decode($mockup->colors) as $key => $color)
                                                <option value="{{ $color }}" @if($listing->colors) <?php if(in_array($color, json_decode($listing->colors))) echo 'selected'?> @endif >{{ $color }}</option>
                                            @endforeach
                                        </select>
                                        <hr>
                                        <ul class="list-inline checkbox-color mb-1">
                                            @foreach (json_decode($mockup->colors) as $key => $color)
                                                <li> 
                                                    <label style="background: {{ $color }};" onclick="changeColor('{{$color}}')"></label>
                                                </li> 
                                            @endforeach
                                        </ul>
                                    </div>

                                    {{-- Images && Texts --}}
                                    <div style="background-color: #fff;padding:14px;border-radius: 10px;" class="mb-2">

                                        <button class="collapsed btn btn-outline-success" type="button" data-toggle="collapse" data-target="#add-image" aria-expanded="true" aria-controls="add-image">
                                            Add image
                                        </button>
                                        <button class="collapsed btn btn-outline-info" type="button" data-toggle="collapse" data-target="#add-text" aria-expanded="true" aria-controls="add-text">
                                            Add Text
                                        </button>

                                        <hr>
                                        <div id="accordionExampleUpload">

                                            {{-- Texts --}}
                                            <div id="add-text"  class="collapse " aria-labelledby="heading0" data-parent="#accordionExampleUpload"> 
                                                <textarea name="" id="textbox" cols="25" rows="3" class="form-control mb-2" placeholder="Enter text Here.."></textarea>
                                                
                                                <div style="display:flex;justify-content:space-between">
                                                    <label for="">Text-Color</label>
                                                    <input type="color" id="fontcolors" class="mb-2">
                                                </div>
                                                <button onclick="addtext()" type="button" class="btn btn-dark">add text</button>
                                            </div>

                                            {{-- images --}}
                                            <div id="add-image" class="collapse show" aria-labelledby="heading0" data-parent="#accordionExampleUpload"> 
                                                <a href="{{route('design.index')}}" class="btn btn-outline-warning">Upload More Designs</a>
                                                <div id="avatarlist" style="max-height: 500px; overflow: scroll;"> 
                                                    @if(count($designs) > 0)
                                                        @foreach($designs as $design)
                                                            <img class="img-polaroid" src="{{ asset($design->design) }}">
                                                        @endforeach 
                                                    @else 
                                                        No designs uploaded yet ..
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div id="accordionExample">
                                        
                                        {{-- Preview 1 --}}
                                        @if($mockup->preview_1 != null)
                                            <div id="mockup-div1" class="collapse show" aria-labelledby="heading1" data-parent="#accordionExample">
                                                <div class="mockup-div" style="background:{{json_decode($mockup->colors)[0] ?? ''}}">
                                                    @php
                                                        $prev_1 = json_decode($mockup->preview_1);
                                                        $image_1 = $prev_1 ? $prev_1->image  : ''; 
                                                        $left_1 = $prev_1 ? $prev_1->left . 'px' : ''; 
                                                        $top_1 = $prev_1 ? $prev_1->top . 'px' : ''; 
                                                        $width_1 = $prev_1 ? $prev_1->width . 'px' : ''; 
                                                        $height_1 = $prev_1 ? $prev_1->height . 'px' : ''; 
                                                    @endphp

                                                    <img id="tshirt-backgroundpicture1" src="{{asset($image_1)}}"/>
                                                
                                                    <div id="drawingArea" class="drawing-area" style="width:{{$width_1}};height:{{$height_1}};top:{{$top_1}};left:{{$left_1}};">					
                                                        <div class="canvas-container" >
                                                            <canvas id="tshirt-canvas" class="tshirt-canvas" width="{{$width_1}}" height="{{$height_1}}"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                        @endif

                                        {{-- Preview 2 --}}
                                        @if($mockup->preview_2 != null)
                                            <div id="mockup-div2"  class="collapse" aria-labelledby="heading2" data-parent="#accordionExample">
                                                <div class="mockup-div" style="background:{{json_decode($mockup->colors)[0] ?? ''}}"> 
                                                    @php
                                                        $prev_2 = json_decode($mockup->preview_2);
                                                        $image_2 = $prev_2 ? $prev_2->image  : ''; 
                                                        $left_2 = $prev_2 ? $prev_2->left . 'px' : ''; 
                                                        $top_2 = $prev_2 ? $prev_2->top . 'px' : ''; 
                                                        $width_2 = $prev_2 ? $prev_2->width . 'px' : ''; 
                                                        $height_2 = $prev_2 ? $prev_2->height . 'px' : ''; 
                                                    @endphp

                                                    <img id="tshirt-backgroundpicture1" src="{{asset($prev_2 ? $prev_2->image : '')}}"/>
                                                
                                                    <div id="drawingArea" class="drawing-area" style="width:{{$width_2}};height:{{$height_2}};top:{{$top_2}};left:{{$left_2}};">					
                                                        <div class="canvas-container" >
                                                            <canvas id="tshirt-canvas2" width="{{$width_2}}" height="{{$height_2}}"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                        @endif

                                        {{-- Preview 3 --}}
                                        @if($mockup->preview_3 != null)
                                            <div id="mockup-div3"  class="collapse" aria-labelledby="heading3" data-parent="#accordionExample">
                                                <div class="mockup-div" style="background:{{json_decode($mockup->colors)[0] ?? ''}}"> 
                                                    @php
                                                        $prev_3 = json_decode($mockup->preview_3);
                                                        $image_3 = $prev_3 ? $prev_3->image  : ''; 
                                                        $left_3 = $prev_3 ? $prev_3->left . 'px' : ''; 
                                                        $top_3 = $prev_3 ? $prev_3->top . 'px' : ''; 
                                                        $width_3 = $prev_3 ? $prev_3->width . 'px' : ''; 
                                                        $height_3 = $prev_3 ? $prev_3->height . 'px' : ''; 
                                                    @endphp

                                                    <img id="tshirt-backgroundpicture1" src="{{asset($image_3)}}"/>

                                                    <div id="drawingArea" class="drawing-area" style="width:{{$width_3}};height:{{$height_3}};top:{{$top_3}};left:{{$left_3}};">					
                                                        <div class="canvas-container" >
                                                            <canvas id="tshirt-canvas3" width="{{$width_3}}" height="{{$height_3}}"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="background-color: #fff;padding:14px;border-radius: 10px;" class="mb-2">
                                        Price
                                        <hr>
                                        <div>
                                            <span style="float: left">Base Price:</span>
                                            <span style="float: right">{{single_price($mockup->purchase_price)}}</span>
                                        </div>
                                        <div style="clear: both"></div>
                                        <div style="padding:15px 0px">
                                            <span style="float: left">Profit:</span>
                                            <span style="float: right" id="profit">{{single_price($listing->profit)}}</span>
                                        </div>
                                        <hr>
                                        <div style="clear: both"></div>
                                        <div>
                                            <span style="float: left">Total:</span>
                                            <span style="float: right" id="total">{{single_price($mockup->purchase_price + $listing->profit)}}</span>
                                        </div>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div class="mt-3">
                                        <input type="number" value="{{$listing->profit}}" min="0" name="profit" id="profit_input" class="form-control" placeholder="Profit" onkeyup="profit_price()" required>
                                        <input type="text" value="{{$listing->design_name}}" name="design_name" class="form-control" id="design_name_input" placeholder="Design Name" required>
                                    </div>
                                    <button id="capture" class="btn btn-danger btn-block mt-3">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection

@section('script')
    <script src="{{asset('fabric.js-4.4.0/dist/fabric.min.js')}}"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>

        function selected_colors(){ 
            var colors = $('#colors').val();
            for(i = 0 ; i < colors.length ; i++){
                
            }
            document.cookie = "colors = " + colors;
            
            @foreach (json_decode($mockup->colors) as $key => $color)
                if(jQuery.inArray('{{$color}}',colors) !== -1){
                    console.log('{{$color}}');
                }
            @endforeach
        }

        function profit_price(){
            var profit = parseInt($('#profit_input').val());
            $('#profit').html('EGP'+profit);
            var total = {{$mockup->purchase_price}} + profit;
            $('#total').html('EGP'+total)
        }

        // store multiple instances in  the array
        var canvasInstances = [];
        

        var fabricCanvasObj = new fabric.Canvas('tshirt-canvas');
        var fabricCanvasObj2 = new fabric.Canvas('tshirt-canvas2');
        var fabricCanvasObj3 = new fabric.Canvas('tshirt-canvas3');

        canvasInstances.push(fabricCanvasObj);
        canvasInstances.push(fabricCanvasObj2);
        canvasInstances.push(fabricCanvasObj3);

        $('.drawing-area').on('mouseenter',function(){
            $(this).css('border','1px dotted purple');
        })

        $('.drawing-area').on('mouseleave',function(){
            $(this).css('border','0px dotted purple');
        })
        
        

        $('#capture_form').on('submit',function(e){
            e.preventDefault();
            window.scrollTo(0,0);
            // Show full page LoadingOverlay
            $.LoadingOverlay("show",{imageColor : "#2E86C1"}); 

            $('#capture').prop("disabled", true);
            $('#capture').html(
                `<div class="loader"></div>`
            );
            $('.drawing-area').css('border','0px');
            canvasInstances.forEach(function(canvas) {
                canvas.discardActiveObject().renderAll();
            }); 

            var colors = $('#colors').val(); 

            // preview_1
            @if($mockup->preview_1 != null)
                var images = {};
                if(!$('#mockup-div1').hasClass('show')){
                    $('#mockup-div1').addClass('show');
                }
                @foreach (json_decode($mockup->colors) as $key => $color)
                    window.scrollTo(0,0);
                    $('.mockup-div').css('backgroundColor','{{$color}}');
                    // preview_1
                    html2canvas(document.getElementById('mockup-div1').getElementsByClassName("mockup-div")[0]).then(function(canvas){
                        if(jQuery.inArray('{{$color}}',colors) !== -1){
                            images['{{$color}}-1'] = canvas.toDataURL("image/jpeg");
                        }
                        @if ($loop->last)
                            @if($mockup->preview_2 != null)
                                if(!$('#mockup-div2').hasClass('show')){
                                    $('#mockup-div2').addClass('show');
                                }
                                @foreach (json_decode($mockup->colors) as $key => $color2)
                                    window.scrollTo(0,0);
                                    $('.mockup-div').css('backgroundColor','{{$color2}}');
                                    //preview_2
                                    html2canvas(document.getElementById('mockup-div2').getElementsByClassName("mockup-div")[0]).then(function(canvas2){
                                        if(jQuery.inArray('{{$color2}}',colors) !== -1){
                                            images['{{$color2}}-2'] = canvas2.toDataURL("image/jpeg");
                                        }
                                        @if ($loop->last)
                                            @if($mockup->preview_3 != null)
                                                if(!$('#mockup-div3').hasClass('show')){
                                                    $('#mockup-div3').addClass('show');
                                                }
                                                @foreach (json_decode($mockup->colors) as $key => $color3)
                                                    window.scrollTo(0,0);
                                                    $('.mockup-div').css('backgroundColor','{{$color3}}');
                                                    // preview_3
                                                    html2canvas(document.getElementById('mockup-div3').getElementsByClassName("mockup-div")[0]).then(function(canvas3){
                                                        if(jQuery.inArray('{{$color3}}',colors) !== -1){
                                                            images['{{$color3}}-3'] = canvas3.toDataURL("image/jpeg");
                                                        }
                                                        @if ($loop->last)
                                                            ajax_capture(images);
                                                        @endif
                                                    });
                                                @endforeach
                                                $('#mockup-div3').removeClass('show');
                                            @else 
                                                ajax_capture(images);
                                            @endif
                                        
                                        @endif
                                    });
                                @endforeach
                                $('#mockup-div2').removeClass('show');

                            @else  
                                ajax_capture(images);
                            @endif
                        @endif
                    });
                    
                @endforeach

            @endif

        });

        function ajax_capture(images){

            var dataset1 = fabricCanvasObj.toJSON();
            var dataset2 = fabricCanvasObj2.toJSON();
            var dataset3 = fabricCanvasObj3.toJSON();

            $.ajax({
                type:"POST",
                url:'{{ route("listings.update",$listing->id) }}',
                data:{   
                        images :images,
                        dataset1: JSON.parse(JSON.stringify(dataset1)).objects,
                        dataset2: JSON.parse(JSON.stringify(dataset2)).objects,
                        dataset3: JSON.parse(JSON.stringify(dataset3)).objects,
                        profit: $('#profit_input').val(),
                        colors: $('#colors').val(),
                        design_name: $('#design_name_input').val(),
                        _token: '{{ @csrf_token() }}',
                        _method:'PATCH',
                    },
                success: function(data){ 
                    console.log(data);
                    $.LoadingOverlay("hide");
                    showFrontendAlert('success','Success!!');
                    $('#capture').prop("disabled", false);
                    $('#capture').html(
                        `capture`
                    );
                }, 
                error: function(request, status, error){
                    $.LoadingOverlay("hide");
                    showFrontendAlert('error',error);
                    $('#capture').prop("disabled", false);
                    $('#capture').html(
                        `capture`
                    );
                }
            });
        }
        

        // Update the TShirt color according to the selected color by the user  
        function changeColor(color){
            $('.mockup-div').css('backgroundColor',color);
        }  
        
        
        $('body').on('click', '.img-polaroid', function (e){ 
            fabric.Image.fromURL(e.target.src, function(image) {
                image.scaleToHeight(300);
                image.scaleToWidth(180); 
                fabricCanvasObj.centerObject(image); 
                if($('#mockup-div1').hasClass('show')){
                    fabricCanvasObj.add(image);
                }else if($('#mockup-div2').hasClass('show')){
                    fabricCanvasObj2.centerObject(image);  
                }else if($('#mockup-div3').hasClass('show')){ 
                    fabricCanvasObj3.add(image);
                }
            }); 
        });	 

        get_active_objects();
        function get_active_objects(){

            @if($listing->dataset1 != 'null')
                @foreach(json_decode($listing->dataset1) as $dataset) 
                    @if($dataset->type == 'image') 
                        fabric.Image.fromURL("{{$dataset->src}}", function(image) {
                            image.set({
                                originX: '{{$dataset->originX}}',
                                originY: '{{$dataset->originY}}', 
                                left: '{{$dataset->left}}',
                                top: '{{$dataset->top}}', 
                                scaleX: '{{$dataset->scaleX}}',
                                scaleY: '{{$dataset->scaleY}}',
                                angle: '{{$dataset->angle}}', 
                            });  
                            fabricCanvasObj.centerObject(image); 
                            fabricCanvasObj.add(image);
                        }); 

                    @elseif($dataset->type == 'text')
                        var text = new fabric.Text('{{$dataset->text}}',{ left: {{$dataset->left}}, top: {{$dataset->top}} });  
                        text.set({ fill: '{{$dataset->fill}}' });
                        fabricCanvasObj.add(text);
                    @endif
                @endforeach
            @endif

            @if($listing->dataset2 != 'null')
                @foreach(json_decode($listing->dataset2) as $dataset) 
                    @if($dataset->type == 'image') 
                        fabric.Image.fromURL("{{$dataset->src}}", function(image) {
                            image.set({
                                originX: '{{$dataset->originX}}',
                                originY: '{{$dataset->originY}}', 
                                left: '{{$dataset->left}}',
                                top: '{{$dataset->top}}', 
                                scaleX: '{{$dataset->scaleX}}',
                                scaleY: '{{$dataset->scaleY}}',
                                angle: '{{$dataset->angle}}', 
                            });  
                            fabricCanvasObj2.centerObject(image); 
                            fabricCanvasObj2.add(image);
                        }); 

                    @elseif($dataset->type == 'text')
                        var text = new fabric.Text('{{$dataset->text}}',{ left: {{$dataset->left}}, top: {{$dataset->top}} });  
                        text.set({ fill: '{{$dataset->fill}}' });
                        fabricCanvasObj2.add(text);
                    @endif
                @endforeach
            @endif

            @if($listing->dataset3 != 'null')
                @foreach(json_decode($listing->dataset3) as $dataset) 
                    @if($dataset->type == 'image') 
                        fabric.Image.fromURL("{{$dataset->src}}", function(image) {
                            image.set({
                                originX: '{{$dataset->originX}}',
                                originY: '{{$dataset->originY}}', 
                                left: '{{$dataset->left}}',
                                top: '{{$dataset->top}}', 
                                scaleX: '{{$dataset->scaleX}}',
                                scaleY: '{{$dataset->scaleY}}',
                                angle: '{{$dataset->angle}}', 
                            });  
                            fabricCanvasObj3.centerObject(image); 
                            fabricCanvasObj3.add(image);
                        }); 

                    @elseif($dataset->type == 'text')
                        var text = new fabric.Text('{{$dataset->text}}',{ left: {{$dataset->left}}, top: {{$dataset->top}} });  
                        text.set({ fill: '{{$dataset->fill}}' });
                        fabricCanvasObj3.add(text);
                    @endif
                @endforeach
            @endif

        }

        function addtext() { 
            var textvalue = document.getElementById('textbox').value;
            var text = new fabric.Text(textvalue, { left: 100, top: 100 });
            var fontColor = document.getElementById("fontcolors").value
            text.set({ fill: fontColor });
            if($('#mockup-div1').hasClass('show')){
                fabricCanvasObj.add(text);
            }else if($('#mockup-div2').hasClass('show')){
                fabricCanvasObj2.add(text);
            }else if($('#mockup-div3').hasClass('show')){
                fabricCanvasObj3.add(text);
            } 
        }

        // When the user selects a picture that has been added and press the DEL key
        // The object will be removed !
        document.addEventListener("keydown", function(e) {
            var keyCode = e.keyCode;

            if(keyCode == 46){
                console.log("Removing selected element on Fabric.js on DELETE key !");
                canvasInstances.forEach(function(canvas) {
                    canvas.remove(canvas.getActiveObject());
                });
            }
        }, false);
        
    </script>
@endsection
