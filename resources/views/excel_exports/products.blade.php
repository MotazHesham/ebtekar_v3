
<table>

    <thead>
        <tr>
            <th> 
                name
            </th>
            <th> 
                category_id
            </th> 
            <th>
                subcategory_id
            </th> 
            <th>
                subsubcategory_id
            </th> 
            <th>
                brand_id
            </th> 
            <th>
                unit_price
            </th> 
            <th>
                purchase_price
            </th> 
            <th>
                unit
            </th> 
            <th>
                current_stock
            </th> 
            <th>
                video_provider
            </th> 
            <th>
                video_link
            </th> 
            <th>
                meta_title
            </th> 
            <th>
                meta_description
            </th> 
        </tr>
    </thead> 

    <tbody> 

        @foreach($products as $product)  
            <tr>
                <td>{{ $product->name }}</td> 
                <td>{{ $product->category ? $product->category->name : '' }}</td> 
                <td>{{ $product->subcategory ? $product->subcategory->name : '' }}</td> 
                <td>{{ $product->subsubcategory ? $product->subsubcategory->name : '' }}</td> 
                <td>{{ $product->brand ? $product->brand->name : '' }}</td> 
                <td>{{ $product->unit_price }}</td> 
                <td>{{ $product->purchase_price }}</td> 
                <td>{{ $product->unit }}</td> 
                <td>{{ $product->current_stock }}</td> 
                <td>{{ $product->video_provider }}</td> 
                <td>{{ $product->video_link }}</td> 
                <td>{{ $product->meta_title }}</td> 
                <td>{{ $product->meta_description }}</td> 
            </tr>
        @endforeach  
    </tbody>
</table>