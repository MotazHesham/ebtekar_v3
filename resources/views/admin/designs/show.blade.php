<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">الديزاين</b> </h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body"> 
        <div class="row">
            <div class="col-md-9"> 
                @if($design->status == 'accepted')
                    @foreach($design->product->photos as $media)
                        <a href="{{$media->getUrl('preview2')}}" target="_blanc">
                            <img src="{{$media->getUrl('preview2')}}" width="200" height="200"  alt="">
                        </a>
                    @endforeach
                @else 
                    @foreach($design->design_images as $raw)
                        <a href="{{asset($raw->image)}}" target="_blanc">
                            <img src="{{asset($raw->image)}}" width="200" height="200"  alt="">
                        </a>
                    @endforeach
                @endif
            </div>
            <div class="col-md-3">
                <h5>الصور المستخدمة</h5>
                @if($design->dataset1 != 'null')
                    @foreach(json_decode($design->dataset1) as $dataset)
                        @if($dataset->type == 'image')
                        <a href="{{asset($dataset->src)}}" target="_blanc">
                            <img src="{{asset($dataset->src)}}" width="200" height="200"  alt="" style="margin-bottom:10px">
                        </a> 
                        @endif
                    @endforeach
                @endif

                @if($design->dataset2 != 'null')
                    @foreach(json_decode($design->dataset2) as $dataset)
                        @if($dataset->type == 'image')
                        <a href="{{asset($dataset->src)}}" target="_blanc">
                            <img src="{{asset($dataset->src)}}" width="200" height="200"  alt="" style="margin-bottom:10px">
                        </a> 
                        @endif
                    @endforeach
                @endif

                @if($design->dataset3 != 'null')
                    @foreach(json_decode($design->dataset3) as $dataset) 
                        @if($dataset->type == 'image')
                        <a href="{{asset($dataset->src)}}" target="_blanc">
                            <img src="{{asset($dataset->src)}}" width="200" height="200"  alt="" style="margin-bottom:10px">
                        </a> 
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div> 
</div> 
