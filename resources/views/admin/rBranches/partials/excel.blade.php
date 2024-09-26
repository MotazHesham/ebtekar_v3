<table>

    <thead>
        <tr>
            <th>
                الأسم    
            </th>  
        </tr>
    </thead>

    

    <tbody>  
        @foreach($raws as $raw)  
            <tr>
                <td>{{ $raw->name }}</td>  
            </tr>
        @endforeach   
    </tbody>
</table>