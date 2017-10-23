<div class="row">
    <div class="col-md-6">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="category">Category:</label>
            <select name="category_id" id="category" class="form-control" required>
                <option value="">Please Select</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @isset($product) @if($product->category_id == $category->id) selected @endisset @endif>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="product-name">Product Name:</label>
            <input type="text" name="name" id="product-name" value="{{ isset($product) ? $product->name : old('name') }}" class="form-control" required >
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control">{{ isset($product) ? $product->description : old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" name="price" id="price" value="{{ isset($product) ? $product->price : old('price') }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="text" name="quantity" id="quantity" value="{{ isset($product) ? $product->quantity : old('quantity') }}" class="form-control" required>
        </div>


    </div>
    <div class="col-md-6">

    </div>
</div>