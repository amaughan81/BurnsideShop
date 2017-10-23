
<div class="form-group">
    <label for="product-name">Category Name:</label>
    <input
        type="text"
        name="name"
        id="product-name"
        value="{{ isset($category) ? $category->name : old('name') }}"
        class="form-control"
        required
    >
</div>