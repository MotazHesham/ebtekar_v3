@extends('frontend.layout.app')

@section('styles')
    <style>
        .drawing-area {
            position: absolute;
            z-index: 10;
        }

        .canvas-container {
            position: relative;
            user-select: none;
        }

        .mockup-div {
            width: 450px;
            height: 550px;
            position: relative;
            background-color: #fff;
        }

        #canvas {
            position: absolute;
            left: 0px;
            top: 0px;
            user-select: none;
            cursor: default;
        }

        .loader {
            border-radius: 50%;
            border-top: 3px solid #f8f9fa;
            width: 40px;
            height: 40px;
            -webkit-animation: spin .8s linear infinite;
            /* Safari */
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

        .checkbox-color label {
            width: 2.25rem;
            height: 2.25rem;
            float: left;
            padding: 0.375rem;
            margin-right: 0.375rem;
            display: block;
            font-size: 0.875rem;
            text-align: center;
            opacity: 0.7;
            border: 2px solid #d3d3d3;
            border-radius: 50%;
            -webkit-transition: all 0.3s ease;
            -moz-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            transition: all 0.3s ease;
            transform: scale(0.95);
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-main ">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="breadcrumb-contain">
                        <div>
                            <h2> حسابي</h2>
                            <ul>
                                <li><a href="{{ route('frontend.dashboard') }}">لوحة التحكم</a></li>
                                <li><i class="fa fa-angle-double-left"></i></li>
                                <li><a href="{{ route('frontend.mockups.categories') }}">أبدا التصميم</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- section start -->
    <section class="cart-section order-history section-big-py-space"> 
        <form action="" id="capture_form"> 
            <div class="custom-container">
                <div class="row">  
                    <div class="col-md-4">
                        {{-- Variation --}}
                        <div style="background-color: #fff;padding:14px;border-radius: 10px;" class="mb-2">
                            الأوجه
                            <hr>
                            
                            <button class="btn btn-warning" onclick="download('tshirt-canvas')"> download front</button>
                            @if ($mockup->preview_1 != null)
                                <button class="collapsed btn btn-success" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#mockup-div1" aria-expanded="true" aria-controls="mockup-div1">
                                    {{ json_decode($mockup->preview_1)->name }}
                                </button>
                            @endif
                            @if ($mockup->preview_2 != null)
                                <button class="collapsed btn btn-info" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#mockup-div2" aria-expanded="false" aria-controls="mockup-div2">
                                    {{ json_decode($mockup->preview_2)->name }}
                                </button>
                            @endif
                            @if ($mockup->preview_3 != null)
                                <button class="collapsed btn btn-danger" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#mockup-div3" aria-expanded="false" aria-controls="mockup-div3">
                                    {{ json_decode($mockup->preview_3)->name }}
                                </button>
                            @endif
                        </div>

                        {{-- Colors --}}
                        <div style="background-color: #fff;padding:14px;border-radius: 10px;" class="mb-2"> 
                            <label for="tshirt-color">الألوان</label>
                            <select class="form-control color-var-select" name="colors[]" id="colors" multiple required disabled style="display: none">
                                @foreach (json_decode($mockup->colors) as $key => $color)
                                    <option value="{{ $color }}" selected>{{ $color }}</option>
                                @endforeach
                            </select>
                            <hr> 
                            <ul class="list-inline checkbox-color mb-1">
                                @foreach (json_decode($mockup->colors) as $key => $color)
                                    <li >
                                        <label style="background: {{ $color }}; cursor: pointer "
                                            onclick="changeColor('{{ $color }}')"></label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Images && Texts --}}
                        <div style="background-color: #fff;padding:14px;border-radius: 10px;" class="mb-2">
                            <button class="collapsed btn btn-outline-success" type="button" data-bs-toggle="collapse"
                                data-bs-target="#add-image" aria-expanded="true" aria-controls="add-image">
                                image
                            </button>
                            <button class="collapsed btn btn-outline-info" type="button" data-bs-toggle="collapse"
                                data-bs-target="#add-text" aria-expanded="true" aria-controls="add-text">
                                Text
                            </button>

                            <hr>
                            <div id="accordionExampleUpload">

                                {{-- Texts --}}
                                <div id="add-text" class="collapse " aria-labelledby="heading0"
                                    data-bs-parent="#accordionExampleUpload">
                                    <textarea name="" id="textbox" cols="25" rows="3" class="form-control mb-2"
                                        placeholder="Enter text Here.."></textarea>

                                    <div style="display:flex;justify-content:space-between">
                                        <label for="">Text-Color</label>
                                        <input type="color" id="fontcolors" class="mb-2">
                                    </div>
                                    <button onclick="addtext()" type="button" class="btn btn-dark">add
                                        text</button>
                                </div>

                                {{-- images --}}
                                <div id="add-image" class="collapse show" aria-labelledby="heading0"
                                    data-bs-parent="#accordionExampleUpload">
                                    <a href="{{ route('frontend.designer-images.index') }}"
                                        class="btn btn-outline-warning">Upload More Designs</a>
                                    <div id="avatarlist" style="max-height: 500px; overflow: scroll;">
                                        @if (count($designer_images) > 0)
                                            @foreach ($designer_images as $design)
                                                <img class="img-polaroid" src="{{ asset($design->image) }}">
                                            @endforeach
                                        @else
                                            No designs uploaded yet ..
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="accordion" id="accordionExample">

                            {{-- Preview 1 --}}
                            @if ($mockup->preview_1 != null) 
                                <div id="mockup-div1" class="collapse show" aria-labelledby="heading1"
                                    data-bs-parent="#accordionExample">
                                    <div class="mockup-div"
                                        style="background:{{ json_decode($mockup->colors)[0] ?? '' }}">
                                        @php
                                            $prev_1 = json_decode($mockup->preview_1);
                                            $image_1 = $prev_1 ? $prev_1->image : '';
                                            $left_1 = $prev_1 ? $prev_1->left - 315 . 'px' : '';
                                            $top_1 = $prev_1 ? $prev_1->top . 'px' : '';
                                            $width_1 = $prev_1 ? $prev_1->width . 'px' : '';
                                            $height_1 = $prev_1 ? $prev_1->height . 'px' : '';
                                        @endphp

                                        <img id="tshirt-backgroundpicture1" src="{{ asset($image_1) }}" />

                                        <div id="drawingArea" class="drawing-area"
                                            style="width:{{ $width_1 }};height:{{ $height_1 }};top:{{ $top_1 }};left:{{ $left_1 }};">
                                            <div class="canvas-container">
                                                <canvas id="tshirt-canvas" class="tshirt-canvas"
                                                    width="{{ $width_1 }}"
                                                    height="{{ $height_1 }}"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            @endif

                            {{-- Preview 2 --}}
                            @if ($mockup->preview_2 != null) 
                                <div id="mockup-div2" class="collapse" aria-labelledby="heading2"
                                    data-bs-parent="#accordionExample">
                                    <div class="mockup-div"
                                        style="background:{{ json_decode($mockup->colors)[0] ?? '' }}">
                                        @php
                                            $prev_2 = json_decode($mockup->preview_2);
                                            $image_2 = $prev_2 ? $prev_2->image : '';
                                            $left_2 = $prev_2 ? $prev_2->left - 315 . 'px' : '';
                                            $top_2 = $prev_2 ? $prev_2->top . 'px' : '';
                                            $width_2 = $prev_2 ? $prev_2->width . 'px' : '';
                                            $height_2 = $prev_2 ? $prev_2->height . 'px' : '';
                                        @endphp

                                        <img id="tshirt-backgroundpicture1"
                                            src="{{ asset($prev_2 ? $prev_2->image : '') }}" />

                                        <div id="drawingArea" class="drawing-area"
                                            style="width:{{ $width_2 }};height:{{ $height_2 }};top:{{ $top_2 }};left:{{ $left_2 }};">
                                            <div class="canvas-container">
                                                <canvas id="tshirt-canvas2" width="{{ $width_2 }}"
                                                    height="{{ $height_2 }}"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            @endif

                            {{-- Preview 3 --}}
                            @if ($mockup->preview_3 != null) 
                                <div id="mockup-div3" class="collapse" aria-labelledby="heading3"
                                    data-bs-parent="#accordionExample">
                                    <div class="mockup-div"
                                        style="background:{{ json_decode($mockup->colors)[0] ?? '' }}">
                                        @php
                                            $prev_3 = json_decode($mockup->preview_3);
                                            $image_3 = $prev_3 ? $prev_3->image : '';
                                            $left_3 = $prev_3 ? $prev_3->left - 315 . 'px' : '';
                                            $top_3 = $prev_3 ? $prev_3->top . 'px' : '';
                                            $width_3 = $prev_3 ? $prev_3->width . 'px' : '';
                                            $height_3 = $prev_3 ? $prev_3->height . 'px' : '';
                                        @endphp

                                        <img id="tshirt-backgroundpicture1" src="{{ asset($image_3) }}" />

                                        <div id="drawingArea" class="drawing-area"
                                            style="width:{{ $width_3 }};height:{{ $height_3 }};top:{{ $top_3 }};left:{{ $left_3 }};">
                                            <div class="canvas-container">
                                                <canvas id="tshirt-canvas3" width="{{ $width_3 }}"
                                                    height="{{ $height_3 }}"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            @endif

                        </div>
                    </div>  
                    <div class="col-md-4">
                        <div style="background-color: #fff;padding:14px;border-radius: 10px;" class="mb-2">
                            Price
                            <hr>
                            <div>
                                <span style="float: left">Base Price:</span>
                                <span style="float: right">{{ $mockup->purchase_price }}</span>
                            </div>
                            <div style="clear: both"></div>
                            <div style="padding:15px 0px">
                                <span style="float: left">Profit:</span>
                                <span style="float: right" id="profit"></span>
                            </div>
                            <hr>
                            <div style="clear: both"></div>
                            <div>
                                <span style="float: left">Total:</span>
                                <span style="float: right" id="total">{{ $mockup->purchase_price }}</span>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                        <div class="mt-3">
                            <div class="form-group">
                                <input type="number" min="0" name="profit" id="profit_input"
                                    class="form-control" placeholder="Profit" onkeyup="profit_price()" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="design_name" class="form-control" id="design_name_input"
                                    placeholder="Design Name" required>
                            </div>
                        </div>
                        <button id="capture" class="btn btn-danger btn-block mt-3">Save</button>
                    </div> 
                </div> 
            </div>
        </form>  
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('fabric.js-4.4.0/dist/fabric.min.js') }}"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    {{-- loading plugin --}}
    <script src="{{ asset('js/jquery-loading-overlay.min.js') }}"></script>  
    <script>
        function download(canvas_id){
            var canvas = document.getElementById(canvas_id);
            var dataURL    = canvas.toDataURL("image/png");
            const downloadLink = document.createElement('a');
            downloadLink.href = dataURL;
            downloadLink.download = 'canvas_image.png'; 
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
        function selected_colors() {
            var colors = $('#colors').val();
            for (i = 0; i < colors.length; i++) {

            }

            @foreach (json_decode($mockup->colors) as $key => $color)
                if (jQuery.inArray('{{ $color }}', colors) !== -1) {
                    console.log('{{ $color }}');
                }
            @endforeach
        }

        function profit_price() {
            var profit = parseInt($('#profit_input').val());
            $('#profit').html('EGP' + profit);
            var total = {{ $mockup->purchase_price }} + profit;
            $('#total').html('EGP' + total)
        }

        // store multiple instances in  the array
        var canvasInstances = [];


        var fabricCanvasObj = new fabric.Canvas('tshirt-canvas');
        var fabricCanvasObj2 = new fabric.Canvas('tshirt-canvas2');
        var fabricCanvasObj3 = new fabric.Canvas('tshirt-canvas3');

        canvasInstances.push(fabricCanvasObj);
        canvasInstances.push(fabricCanvasObj2);
        canvasInstances.push(fabricCanvasObj3);

        $('.drawing-area').on('mouseenter', function() {
            $(this).css('border', '1px dotted purple');
        })

        $('.drawing-area').on('mouseleave', function() {
            $(this).css('border', '0px dotted purple');
        })



        $('#capture_form').on('submit', function(e) {
            e.preventDefault();
            window.scrollTo(0, 0);
            // Show full page LoadingOverlay
            $.LoadingOverlay("show", {
                imageColor: "#2E86C1"
            });

            $('#capture').prop("disabled", true);
            $('#capture').html(
                `<div class="loader"></div>`
            );
            $('.drawing-area').css('border', '0px');
            canvasInstances.forEach(function(canvas) {
                canvas.discardActiveObject().renderAll();
            });



            var images = {};
            preview_1(images);

            // $.when(images = ).then(
            //     $.when(images = preview_2(images)).then(preview_3(images))
            // );
        });

        function preview_1(images) {
            var colors = $('#colors').val();

            @if ($mockup->preview_1 != null)
                if (!$('#mockup-div1').hasClass('show')) {
                    $('#mockup-div1').addClass('show');
                }
                z = 0;
                for (i = 0; i < colors.length; i++) {
                    console.log('first0');
                    window.scrollTo(0, 0);
                    $('.mockup-div img').css('backgroundColor', colors[i]);
                    // preview_1
                    html2canvas(document.getElementById('mockup-div1').getElementsByClassName("mockup-div")[0]).then(
                        function(canvas) {
                            console.log('first1 ' + z);
                            images[colors[z] + '-1'] = canvas.toDataURL("image/jpeg");
                            if (z == colors.length - 1) {
                                console.log('first_return');
                                preview_2(images);
                            }
                            z++;
                        });
                }
            @else
                return images;
            @endif

        }

        function preview_2(images) {
            var colors = $('#colors').val();
            @if ($mockup->preview_2 != null)
                if (!$('#mockup-div2').hasClass('show')) {
                    $('#mockup-div2').addClass('show');
                }
                y = 0;
                for (i = 0; i < colors.length; i++) {
                    console.log('second0');
                    window.scrollTo(0, 0);
                    $('.mockup-div img').css('backgroundColor', colors[i]);
                    //preview_2
                    html2canvas(document.getElementById('mockup-div2').getElementsByClassName("mockup-div")[0]).then(
                        function(canvas2) {
                            console.log('second1 ' + y);
                            images[colors[y] + '-2'] = canvas2.toDataURL("image/jpeg");
                            if (y == colors.length - 1) {
                                console.log('second_return');
                                preview_3(images);
                            }
                            y++;
                        });
                }
                $('#mockup-div2').removeClass('show');
            @else
                ajax_capture(images);
            @endif
        }

        function preview_3(images) {
            @if ($mockup->preview_3 != null)
                if (!$('#mockup-div3').hasClass('show')) {
                    $('#mockup-div3').addClass('show');
                }
                p = 0;
                for (i = 0; i < colors.length; i++) {
                    console.log('third0');
                    window.scrollTo(0, 0);
                    $('.mockup-div img').css('backgroundColor', colors[i]);
                    // preview_3
                    html2canvas(document.getElementById('mockup-div3').getElementsByClassName("mockup-div")[0]).then(
                        function(canvas3) {
                            console.log('third1 ' + p);
                            images[colors[p] + '-3'] = canvas3.toDataURL("image/jpeg");
                            if (p == colors.length - 1) {
                                ajax_capture(images);
                            }
                            p++;
                        });
                }
                $('#mockup-div3').removeClass('show');
            @else
                ajax_capture(images);
            @endif
        }

        function ajax_capture(images) {

            var dataset1 = fabricCanvasObj.toJSON();
            var dataset2 = fabricCanvasObj2.toJSON();
            var dataset3 = fabricCanvasObj3.toJSON();

            console.log('finish');
            $.ajax({
                type: "POST",
                url: '{{ route('frontend.designs.store') }}',
                data: {
                    mockup_id: '{{ $mockup->id }}',
                    images: images,
                    dataset1: JSON.parse(JSON.stringify(dataset1)).objects,
                    dataset2: JSON.parse(JSON.stringify(dataset2)).objects,
                    dataset3: JSON.parse(JSON.stringify(dataset3)).objects,
                    profit: $('#profit_input').val(), 
                    design_name: $('#design_name_input').val(),
                    _token: '{{ @csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    $.LoadingOverlay("hide");
                    showAlert('success', 'تم أرسال الديزاين للأدارة وسيتم مراجعته','');
                    $('#capture').prop("disabled", false);
                    $('#capture').html(
                        `capture`
                    );
                },
                error: function(request, status, error) {
                    $.LoadingOverlay("hide");
                    showAlert('error', error,'');
                    $('#capture').prop("disabled", false);
                    $('#capture').html(
                        `capture`
                    );
                }
            });
        }


        // Update the TShirt color according to the selected color by the user 
        function changeColor(color) {
            $('.mockup-div').css('backgroundColor', color);
        }


        $('body').on('click', '.img-polaroid', function(e) {
            fabric.Image.fromURL(e.target.src, function(image) {
                image.scaleToHeight(300);
                image.scaleToWidth(180);
                fabricCanvasObj.centerObject(image);
                if ($('#mockup-div1').hasClass('show')) {
                    fabricCanvasObj.add(image);
                } else if ($('#mockup-div2').hasClass('show')) {
                    fabricCanvasObj2.add(image);
                } else if ($('#mockup-div3').hasClass('show')) {
                    fabricCanvasObj3.add(image);
                }
            });
        });

        function get_active_objects() {
            var datatest = fabricCanvasObj.toJSON();
            console.log(JSON.stringify(datatest));
        }

        function addtext() {
            var textvalue = document.getElementById('textbox').value;
            var text = new fabric.Text(textvalue, {
                left: 100,
                top: 100
            });
            var fontColor = document.getElementById("fontcolors").value
            text.set({
                fill: fontColor
            });
            if ($('#mockup-div1').hasClass('show')) {
                fabricCanvasObj.add(text);
            } else if ($('#mockup-div2').hasClass('show')) {
                fabricCanvasObj2.add(text);
            } else if ($('#mockup-div3').hasClass('show')) {
                fabricCanvasObj3.add(text);
            }
        }

        // When the user selects a picture that has been added and press the DEL key
        // The object will be removed !
        document.addEventListener("keydown", function(e) {
            var keyCode = e.keyCode;

            if (keyCode == 46) {
                console.log("Removing selected element on Fabric.js on DELETE key !");
                canvasInstances.forEach(function(canvas) {
                    canvas.remove(canvas.getActiveObject());
                });
            }
        }, false);
    </script>
@endsection
