 <div class="shipping-options row">
	@if(isset($shipment_options))
	    @foreach($shipment_options as $options)
	    <div class="col-md-4 col-12 mt-1">
            <a href="javascript:void(0)" data-value="{{ $options->id }}" price-value="{{ $options->price }}" id="option{{ $options->id }}" class="shipping-card  row align-items-between w-100">
            	<div class="col-5" style="text-align: center;"> 
            		<h4 class="shipping-icon">
                	<ion-icon name="car-sport-outline"></ion-icon>
            		</h4>
            	</div>

            	<div class="col-7"> 
            		<label class="title-shipping">
                	{{ $options->name }}
            		</label>
            		<p class="mb-1" class="delivery-time">
                	{{ $options->delivery_time }}
            		</p>
            		@if($options->price != 0)
            		<h6 class="price">
                	${{ $options->price }}
            		</h6>
            		@else
            		<h6 class=" price price-free">
                	GRATIS
            		</h6>
            		@endif
            	</div>
            </a>
         </div>
        @endforeach
    @else
    
    @endif
</div>
