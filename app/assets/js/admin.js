window.onload = function(){
    $('#updateForm').hide();
    $('#insertForm').hide();
    $("body").on("click", ".btnUpd", function(){
        var id = $(this).data('id');
        updateProd(id);
    });
    $("body").on("click", ".btnDel", function(){
        var id = $(this).data('id');
        deleteProd(id);
    });
    $('#updateForm .fa-close').click(function(){
        $('#updateForm').fadeOut();
    });
    $('#insertDish').click(function(){
        $('#insertForm').fadeIn();
    });
    $('#insertForm .fa-close').click(function(){
        $('#insertForm').fadeOut();
    });
    $('.paginationLinksA').click(function(e){
        e.preventDefault();
        var limit = $(this).data('limit');
        $('.paginationLinksA').removeClass('activePaginationA');
        $(this).addClass('activePaginationA');
        paginationAdmin(limit);
    });
    $("#searchBtnA").keyup(function(){
        var value= $(this).val();
        if(value == ""){
            limit= 0;
            $.ajax({
                url: "index.php?page=limit",
                method: "GET",
                dataType: 'json',
                data: {
                    limit: limit
                },
                success: function(products){
                    console.log(products);
                    printProduct(products);
                    getInitialPagination();
                },
                error: function(error){
                    console.log(error);
                }
            });
        }else{
            url = 'admin.php?page=search';
            $.ajax({
                url: url,
                method: "GET",
                data: {
                    value: value
                },
                dataType: 'json',
                success: function(products){
                    console.log(products);
                    printProduct(products, 1);
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

    });
}

function getInitialPagination(){
    $.ajax({
        url: "index.php?page=pag",
        dataType: "json",
        success: function(data){
            // console.log(data);
            var numOfPagLinks = data;
            console.log(numOfPagLinks);
            var paginationHtml= ``;
            var i;
            for(i = 0; i < numOfPagLinks; i++){
                paginationHtml += `<li>
    <a href="#" class="paginationLinks" data-limit="${i}"> ${i+1}</a>
    </li>`;
            }
            $('#paginationDiv #pagination').html(paginationHtml);
        },
        error : function(err){
            console.log(err);
        }
    })
}

function deleteProd(id){
    $.ajax({
        url:"admin.php?page=delete",
        method: "POST",
        data: {
            send: true,
            id:id
        },
        /*dataType: 'json',*/
        success: function(data,xhr){
            //alert(data);
           console.log(data);
        },
        error:function(xhr, textStatus, errorThrown){
            console.log(textStatus);
            console.log(errorThrown);

        }
    });
}

function paginationAdmin(limit){
    $.ajax({
        url: "admin.php?page=limit",
        method: "GET",
        dataType: 'json',
        data: {
            limit: limit
        },
        success: function(products){
            console.log(products);
            printProduct(products);
        },
        error: function(error){
            console.log(error);
        }
    });
}

function printProduct(products, shouldPrintPag= 0){
    let text = "";
    var i = 0;
    for (let p of products){
        i++;
        if(i <= 5){
            text+= `<tr class='p'>
                <td class='idProd'>${p.id}</td>
                <td><div class="picHold"><img src='${p.path}${p.picName}' alt='${p.alt}' class='smallPic'/>`;
            var hot = Number(p.hot);
            if(hot) {
                text += `<div class="hotAdm">Hot !</div>`;
            }
            text+=`</div></td><td>
                    <p class='prodNameA'>${p.name}</p></td><td><p class='price'>${p.price}$</p>`;
            if(p.oldPrice > 1){
                text+=`<del>${p.oldPrice}</del>`;
            }
            text+=`<td><input type='button' class='btnUpd' data-id='${p.id}' value='Update'/><br/>
                    <input type='button' class='btnDel' data-id='${p.id}' value='Delete'/>
                </td>
            </tr>`;
        }
    }
    let final = "<tr>\n" +
        "                <th>Id</th>\n" +
        "                <th>Dish</th>\n" +
        "                <th>Name</th>\n" +
        "                <th>Price</th>\n" +
        "                <th>Modify or Delete?</th>\n" +
        "            </tr>" + text;
    $('#tableProd').html(final);
    if(shouldPrintPag){
        printPagination(products);
    }
}
function printPagination(products){
    //console.log(products);
    var length = products.length;
    console.log(length);
    var i;
    var offset = 5;
    var paginationHtml ="";
    var numOfPagLinks = Math.ceil(length/offset);
    for(i = 0; i < numOfPagLinks; i++){
        paginationHtml += `<li>
    <a href="#" class="filteredPagination" data-limit="${i}"> ${i+1}</a>
    </li>`;
    }
    $('#paginationA ul').html(paginationHtml);
    $('#paginationA ul .filteredPagination').click(function(e){
        e.preventDefault();
        var limit = $(this).data('limit');
        clickedPaginationFiltered(products, limit)
    });
}

function clickedPaginationFiltered(products, limit){
    console.log("kliknuto");
    // console.log(products);
    var limit = limit;
    console.log(limit);
    var offset = 5;
    var printStart = limit * offset + 1;
    var printEnd = printStart + (offset - 1);
    console.log(printStart);
    console.log(printEnd);
    var i = 0;
    var text = '<tr>\n' +
        '                <th>Id</th>\n' +
        '                <th>Dish</th>\n' +
        '                <th>Name</th>\n' +
        '                <th>Price</th>\n' +
        '                <th>Modify or Delete?</th>\n' +
        '            </tr>';
    for(p of products){
        i++;
        if((i == printStart) || ((i>printStart) && (i<=printEnd))){
            text+= `<tr class='p'>
                <td class='idProd'>${p.id}</td>
                <td><div class="picHold"><img src='${p.path}${p.picName}' alt='${p.alt}' class='smallPic'/>`;
            var hot = Number(p.hot);
            if(hot) {
                text += `<div class="hotAdm">Hot !</div>`;
            }
            text+=`</div></td><td>
                    <p class='prodNameA'>${p.name}</p></td><td><p class='price'>${p.price}$</p>`;
            if(p.oldPrice > 1){
                text+=`<del>${p.oldPrice}</del>`;
            }
            text+=`<td><input type='button' class='btnUpd' data-id='${p.id}' value='Update'/><br/>
                    <input type='button' class='btnDel' data-id='${p.id}' value='Delete'/>
                </td>
            </tr>`;
        }
    }
    //console.log(text);
    $('#tableProd').html(text);
}
function updateProd(id){
    //console.log(id);
    document.getElementById('idProd').value=id;
    $('#updateForm').fadeIn();
    $.ajax({
        url:"admin.php?page=clickUpdate",
        method: "post",
        data: {
            send: true,
            id:id
        },
        success: function(data,xhr){
           // console.log(data);
            var catId = data.cat_id;
            let i = 0;
            for(i; i<5 ; i++){
                var element =  document.getElementById('prodCat').options[i];
                if(element.value == catId){
                    //console.log("ima");
                    document.getElementById('prodCat').options[i].selected='selected';
                }
            }
            document.getElementById('prodName').value=data.name;
            document.getElementById('prodPrice').value=data.price;
            document.getElementById('oldPrice').value=data.oldPrice;
            var hot=data.hot;
            if(!hot){
                document.getElementById('hotProd').options[0].selected='selected';
            }else{
                document.getElementById('hotProd').options[1].selected='selected';
            }
        },
        error:function(xhr, textStatus, errorThrown){
            console.log(xhr);
            switch(xhr.status){
                case 500:
                    error="There was an error";
                case 404:
                    error="Page not found";

            }
            //alert(error);
        }
    });

}

function checkUpdate(){
    var ok=true;
    var error=[];
    //var id=document.getElementById('dishidDish').value;
    var name=document.getElementById('prodName').value;
    var cat = document.getElementById("prodCat").options[document.getElementById("prodCat").selectedIndex].value;
    console.log(cat);
    var price=document.getElementById('prodPrice').value;
    var oldPrice = document.getElementById('oldPrice').value;
    //var newDish=document.getElementById('newDish')[document.getElementById('newDish').selectedIndex].value;

    console.log(price);
    console.log(oldPrice);
    var reName=/./g;
    var rePrice=/^\d*\.?\d*$/;
    if(cat=='-1'){
        ok=false;
        error.push("Choose category.");
    }
    if(!reName.test(name)){
        ok=false;
        error.push("Name is not valid.");
    }
    if(price==""){
        ok=false;
        error.push("Price is not valid.");
    }
    if(!rePrice.test(price)){
        ok = false;
        error.push("Price is not valid.");
    }

    if( (oldPrice=='')){
        ok=false;
        error.push("Old price is not valid.");
    }
    if(!rePrice.test(oldPrice)){
        ok = false;
        error.push("Old price is not valid.");
    }

    console.log(ok);
    var showErr="";
    if(error.length){
        for(var er of error){
            showErr+=er+"<br/>";
        }
    }
    $('#updateForm .error').html(showErr);
    if(error.length>0){
        return false;
    }else{
        return true;
    }
}

function checkInsert(){
    var ok=true;
    var error=[];
    //var id=document.getElementById('dishidDish').value;
    var catid=document.getElementById('categoryId').value;
    var name=document.getElementById('prodNameI').value;
    var price=document.getElementById('prodPriceI').value;
    var cat = document.getElementById("prodCatI").options[document.getElementById("prodCatI").selectedIndex].value;
    //var newDish=document.getElementById('newDish')[document.getElementById('newDish').selectedIndex].value;
    var oldPrice=document.getElementById('oldPriceI').value;
    var reName=/./g;
    var rePrice=/^\d*\.?\d*$/;
    if(cat=='-1'){
        ok=false;
        error.push("Choose category.");
    }
    if(!reName.test(name)){
        ok=false;
        error.push("Name is not valid.");
    }
    if(price==""){
        ok=false;
        error.push("Price is not valid.");
    }
    if(!rePrice.test(price)){
        ok = false;
        error.push("Price is not valid.");
    }

    if( (oldPrice=='')){
        ok=false;
        error.push("Old price is not valid.");
    }
    if(!rePrice.test(oldPrice)){
        ok = false;
        error.push("Old price is not valid.");
    }
    console.log(ok);
    var showErr="";
    for(var er of error){
        showErr+=er+"<br/>";
    }
    $('#insertForm .error').html(showErr);
    console.log(error);
    /*return ok;*/
    if(error.length>0){
        return false;
    }else{
        return true;
    }
}