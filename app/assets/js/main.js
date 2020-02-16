window.onload=function() {
    showSocialNetw();
    $('#cart').hide();
    $("body").on("click", "#prod .fa-shopping-bag", addProdToWishlist);
    $('#wishlistLink').click(function(){
        $('#cart').fadeIn();
    });
    getWishlist();
    $("#menuList li a").click(function(e){
        var id = $(this).data('active');
        //$("#menuList li").removeClass('active');
       // $(this).parent().addClass('active');
        /*if(idC) {
            e.preventDefault();
            filterCategory(idC);
        }*/
    })
    $('#logForm').hide();
    $('#registrationForm').hide();
    $('#registrationForm .x').click(function(){
        $('#registrationForm').fadeOut(500);
    });
    $('#logForm .x').click(function(){
        $('#logForm').fadeOut(500);
    });
    $('#logReg #logRegList #logLink').click(function(){
        if($('#logForm').is(':visible')){
            $('#logForm').hide();
        }else{
            $('#logForm').slideToggle();
        }
    });
    $('#logReg #logRegList #regLink').click(function(){
        $('#registrationForm').slideToggle();
        document.querySelector('#btnRegistration').addEventListener("click",checkingRegData);
    });
    /*$('#logForm #logIn').click(function(e){
        e.preventDefault();
    });*/
    //document.getElementById('logForm').addEventListener("submit", logIn);
    $('#price').on('input', function(){
        var value = $(this).val();
        value+='$';
        $('#priceValue').text(value);
        filterProducts();
    });
    $("body").on("click", ".paginationLinks", function(e){
        e.preventDefault();
       // console.log(".paginationLinks klikkkk")
        let limit = $(this).data("limit");
        $('.paginationLinks').removeClass('activePagination');
        $(this).addClass('activePagination');
        $.ajax({
            url: "index.php?page=limit",
            method: "GET",
            dataType: 'json',
            data: {
                limit: limit
            },
            success: function(products){
                console.log(products);
                // console.log(text);
               printProduct(products, 0);
            },
            error: function(error){
                console.log(error);
            }
        });

    });
    var pagination = $('paginationLinks').length;
    if(pagination){
        document.getElementsByClassName('paginationLinks')[0].classList.add('activePagination');
    }

    $(".fa-search").click(function(){
        let value = $("#searchBtn").val();
        var url='';
        if(value == ""){
            var limit = 0;
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
            url = 'index.php?page=search';
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                data: {
                    value: value
                },
                success: function(data,status, xhr){
                    printProduct(data, 1);
                },
                error: function(xhr, status, statusText){
                    console.error('----> ERROR <----');
                    console.log(xhr);
                }
            });
        }

    });
    $("#searchBtn").keyup(function(){
        var value= $(this).val();
        var limit = 0;
        if(value == ''){
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
            })
        }
    });
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
    $("#priceSort").change(filterProducts);
    $("#leftCategories li a").click(function(e){
        e.preventDefault();
        var idC = $(this).data('id');
        //changeActiveCategory(idC);
        $("#leftCategories li").removeClass('activeCategoryLeftSide');
        $(this).parent().addClass('activeCategoryLeftSide');
        filterProducts(idC);
    });
    var priceElement = $("#price");
    if(priceElement){
        $("#price").val(60);
        var price = $("#price").val() + "$";
        $("#priceValue").html(price);
    }
}

/*function filterCategory(idC){
    console.log(idC);
    $.ajax({
        url: "index.php?page=filterCat",
        method: "GET",
        data: {
            categoryId: idC
        },
        dataType: "json",
        success: function(products){
            console.log(products);
            printProduct(products, 1);
        },
        error: function(error){
            console.log(error);
        }
    });

}*/

function getWishlist(){
    $.ajax({
        url: "index.php?page=wishlist",
        method: "GET",
        dataType: "json",
        success: function(products){
           //console.log(products);
           showWishlist(products);
        },
        error: function(error){
            console.log(error);
        }
    });
}

function deleteOrder(idProd){
    //console.log(idProd);
    var idProd = idProd;
    $.ajax({
        url : "index.php?page=deleteOrder",
        method : "GET",
        data : {
            idProd : idProd
        },
        success: function(prod){
            getWishlist();
        },
        error: function(err){
            console.log(err);
        }
    });
}

function showWishlist(products){
    if(products){
        var length = products.length;
        var sum = 0;
        if(length > 0){
            var text = "<table><p id='wishlistHeadline'>My Wishlist <span class='fa fa-heart'></span></p>";
            for(var product of products){
                sum += product.quantity * product.price;
                text += `<tr data-prodid="${product.id}"><td>
                  <img class='prodPicWishlist'alt = '${product.alt}' src = '${product.path}${product.picName}'/></td>
                   <td>${product.name}<br/><span class='prodPrice'>${product.price}<span class='dollar'> $ </span></span>`;
                if (product.oldPrice > 1) {
                    text += `<del>${product.oldPrice}</del>`;
                }
                text+=`</td><td>Quantity : 
                 <input type="number" class="quantity" value="${product.quantity}"/><input type="button" class="removeBtn" value="X"/></td></tr>`;
            }
            text += "</table><p>Your order is : " + sum + " $</p>" +
                "<div id='orderBtns'><input type=\"button\" value=\"Order\" id=\"orderBtn\"/><input type=\"button\" value=\"Close\" id=\"closeCart\"/></div>";
            $('#cart').html(text);
            $('#cart .removeBtn').click(function(){
                var idProd = $(this).parent().parent().data('prodid');
                deleteOrder(idProd);
            });
            $('#cart .quantity').click(function(){
                var quantity = $(this).val();
                var idPr = $(this).parent().parent().data('prodid');
                changeQuantity(quantity, idPr);
            });
            $('#cart').css("margin-left", "-45%");
            $('#closeCart').click(function(){
                $('#cart').fadeOut();
            });
        }else{
            $('#cart').html("<h1>Your shopping cart is empty :(</h1><input type=\"button\" value=\"Close\" id=\"closeCart\"/>");
            $('#closeCart').click(function(){
                $('#cart').fadeOut();
            });
        }
    }

}

function changeQuantity(quantity, prodId){
    if(quantity>0){
        $.ajax({
            url : "index.php?page=changeQuantity",
            method : "GET",
            data : {
                quantity : quantity,
                prodId : prodId
            },
            success: function(prod){
                getWishlist();
            },
            error: function(err){
                console.log(err);
            }
        });
    }else if(quantity <= 0){
        deleteOrder(prodId);
    }

}

function addProdToWishlist(){
    var prodId = $(this).parent().attr('id');
    //console.log(prodId);
    $.ajax({
        url: "index.php?page=addWish",
        method: "GET",
        data: {
            prodId: prodId
        },
        success: function(products){
             console.log("js js js");
             getWishlist();
        },
        error: function(error){
            console.log(error);
        }
    });
}
function filterProducts(){
    //console.log("jdhfhf");
    var index = document.getElementById('priceSort').selectedIndex;
    var priceSort = document.getElementById('priceSort')[index].value;
    var price = $('#price').val();
    var categoryId = $('.activeCategoryLeftSide a').data('id');
    if(categoryId == undefined){
        categoryId = 0;
    }
    var limit = 0;
    $.ajax({
        url: "index.php?page=filter",
        method: "POST",
        data: {
            priceSort: priceSort,
            price: price,
            categoryId: categoryId
        },
        dataType: "json",
        success: function(products){
           // console.log(products);
            printProduct(products, 1);
            //alert("uspeoooooooooooo");
        },
        error: function(error){
            console.log(error);
        }
    });
}

function printProduct(products, shouldPrintPag){
    var i = 0;
    var text="";
    var resultLength = products.length;
    for(var product of products) {
        i++;
        if(i <=12){
            var shouldOpen = (i - 1) % 4 == 0; // ako je 0 otvori
            //console.log(shouldOpen);
            if ((i == 1) || shouldOpen) {
                text += `<div class='prodRow'>`;
            }
            text += `<div class='product' id='${product.id}'>`;
            var hot= Number(product.hot);
            if (hot) {
                text += `<div class='hot'>Hot !</div>`;
                console.log("hot");
            }

            text += `<span class='fa fa-shopping-bag'></span><img class='prodPic'alt = '${product.alt}' src = '${product.path}${product.picName}'/><div class = 'prodData' ><h2 class = 'prodName' >${product.name}</h2><span class='prodPrice'>${product.price}<span class='dollar'> $ </span></span>`;
            if (product.oldPrice > 1) {
                text += `<del>${product.oldPrice}</del>`;
            }
            text+=`</div></div>`
            if ((i % 4) == 0) {
                text += `</div>`;
            } else if ((resultLength % 4 != 0) && (i == resultLength)) {
                text += `</div>`;
            }
        }

    }
    $('#prod').html(text);
    if(shouldPrintPag){
        printPagination(products);
    }

}

function printPagination(products){
    //console.log(products);
    var length = products.length;
    var i;
    var offset = 12;
    var paginationHtml ="";
    var numOfPagLinks = Math.ceil(length/offset);
    for(i = 0; i < numOfPagLinks; i++){
        paginationHtml += `<li>
    <a href="#" class="filteredPagination" data-limit="${i}"> ${i+1}</a>
    </li>`;
    }
    $('#paginationDiv #pagination').html(paginationHtml);
    $('#paginationDiv #pagination .filteredPagination').click(function(e){
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
    var offset = 12;
    var printStart = limit * offset + 1;
    var printEnd = printStart + (offset - 1);
    console.log(printStart);
    console.log(printEnd);
    var i = 0;
    var text = '';
    for(product of products){
        i++;
        if((i == printStart) || ((i>printStart) && (i<=printEnd))){
            var shouldOpen = (i - 1) % 4 == 0; // ako je 0 otvori
            //console.log(shouldOpen);
            if ((i == 1) || shouldOpen) {
                text += `<div class='prodRow'>`;
            }
            text += `<div class='product'>`;
            var hot= Number(product.hot);
            if (hot) {
                text += `<div class='hot'>Hot !</div>`;
            }

            text += `<span class='fa fa-shopping-bag'></span><img class='prodPic'alt = '${product.alt}' src = '${product.path}${product.picName}'/><div class = 'prodData' ><h2 class = 'prodName' >${product.name}</h2><span class='prodPrice'>${product.price}<span class='dollar'> $ </span></span>`;
            if (product.oldPrice < 1) {
                text += `<del>44$</del>`;
            }
            text+=`</div></div>`
            if ((i % 4) == 0) {
                text += `</div>`;
            } //else if ((products.length % 4 != 0) && (i == products.length)) {
               // text += `</div>`;
           // }
        }
    }
    console.log(text);
    $('#prod').html(text);
}

function logIn(){
    //e.preventDefault();
    console.log("uslo u login funkciju");
    var reUsername= new RegExp(/\b^[A-Za-z][a-z]{5,15}[\d]{1,5}$\b/);
    var username=$('#logUsername').val().trim();
    var usernameError=$('#logUsernameError');
    var pass=$('#logPass').val().trim();
    var rePass=new RegExp(/\b[\d\w]{5,13}\b/);
    var passError=$('#logPassError');
    var errors = [];
    if(username==""){
        usernameError.html('Enter your username.');
       errors.push('Enter your username.');
    }
    else if(!reUsername.test(username)){
        usernameError.html('Username must contain at least 6 characters and at least 1 number.');
        errors.push('Username must contain at least 6 characters and at least 1 number.');
    }
    else{
        usernameError.html('');
    }
    if(pass==""){
        passError.html('Enter your password.');
       errors.push('Enter your password.');
    }
    else if(!rePass.test(pass)){
        passError.html('Invalid password.');
        errors.push('Invalid password.');
    }
    else{
        passError.html('');
    }
   if(errors.length > 0){
       return false;
   }else{
       return true;
   }

}
function checkingRegData(){
    var name = document.querySelector("#regName").value.trim();
    var pass = document.querySelector("#regPass").value.trim();
    var pass1 = document.querySelector("#regPass1").value.trim();
    var email = document.querySelector("#email").value.trim();
    var tel = document.querySelector("#tel").value.trim();
    var town = document.querySelector("#town").value.trim();
    var genderArray=document.getElementsByName('gender');
    if(document.querySelector('#chbMail').checked){
        var sendViaMail=document.querySelector('#chbMail').value;
    }else{
        sendViaMail=null;
    }

    var nameError = document.querySelector("#nameError");
    var passError = document.querySelector("#passError");
    var pass1Error = document.querySelector("#regPass1Error");
    var emailError = document.querySelector("#mailError");
    var telError = document.querySelector("#telError");
    var genderError = document.querySelector("#genderError");
    var townError = document.querySelector("#townError");

    var reName=/^[A-Za-z][a-z]{5,15}[\d]{1,5}$/;
    var rePass=/^[\d\w]{4,13}$/;
    var reEmail=/^\w+([\.-]?\w+)*\@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
    var reTel=/^06[\d]\-[\d]{3}\-[\d]{3,4}$/;
    var reTown=/^[A-Z][a-z]{3,}(\s[A-Z][a-z]{2,})*$/;


    var valid=true;
    if(name==""){
        nameError.innerHTML="Username field is required!";
        valid=false;
    }else if(!reName.test(name)){
        nameError.innerHTML="Username must contain at least 6 characters and at least 1 number.";
        valid=false;
    }else{
        nameError.innerHTML="";
        valid=true;
    }

    if(pass==""){
        passError.innerHTML="Password field is required.";
        valid=false;
    }else if(!rePass.test(pass)){
        passError.innerHTML="Password is not in valid format.";
        valid=false;
    }else{
        passError.innerHTML="";
    }

    if(pass1!=pass){
        valid=false;
        pass1Error.innerHTML="You must type in your password again.";
    }else if(pass1==""){
        pass1Error.innerHTML="You must type in your password again.";
        valid=false;
    }else{
        pass1Error.innerHTML="";
    }

    if(email==""){
        emailError.innerHTML="Email field is required!";
        valid=false;
    }else if(!reEmail.test(email)){
        emailError.innerHTML="Email is not in valid format.";
        valid=false;
    }else{
        emailError.innerHTML="";
    }

    if(tel==""){
        telError.innerHTML="Tel field is required!";
        valid=false;
    }else if(!reTel.test(tel)){
        telError.innerHTML="Tel needs to be in this format 06*-***-****.";
        valid=false;
    }else{
        telError.innerHTML="";
    }

    var selectedGender="";
    for(var i=0;i<genderArray.length;i++){
        if(genderArray[i].checked){
            selectedGender=genderArray[i].value;
            break;
        }
    }
    if(selectedGender==""){
        genderError.innerHTML="Choose gender.";
        valid=false;
    }else{
        genderError.innerHTML="";
    }

    if(town==""){
        townError.innerHTML="";
    }else if(!reTown.test(town)){
        townError.innerHTML="Town is not in valid format.";
        valid=false;
    }else{
        townError.innerHTML="";
    }
    if(valid==true){
        $.ajax({
            url:"index.php?page=registration",
            method: "post",
            data: {
                send: true,
                name: name,
                pass: pass,
                pass1: pass1,
                email: email,
                tel: tel,
                town: town,
                selectedGender: selectedGender,
                sendViaMail: sendViaMail
            },
            success: function(data){
                successReg();
            },
            error:function(xhr, textStatus, errorThrown){
                var error="An error occurred.";
                switch(xhr.status){
                    case 404:
                        error="Page not found.";
                        break;
                    case 422:
                        error="Your data is not in valid format.";
                        break;
                    case 500:
                        error="There was an error.Try again.";
                        break;
                    case 409:
                        error="<span class='fa fa-exclamation-triangle' aria-hidden='true'> </span> Someone with same username or email already exists.";
                        break;

                }
                console.log(error);
                document.querySelector("#registrationForm .success").innerHTML=error;
                document.querySelector("#registrationForm .success").classList.add('errorRegistration');
            }
        });
    }else{
        document.querySelector("#registrationForm .success").innerHTML="";
    }
    function successReg(){
        document.querySelector("#registrationForm .success").innerHTML='<span class="fa fa-check"></span> You are successfully registrated.';
        if($("#registrationForm .success").hasClass('errorRegistration'))
            document.querySelector("#registrationForm .success").classList.remove('errorRegistration');
        disappearReg=setInterval('disappear()',3000);
    }
}
function disappear(){
    $("#registrationForm").fadeOut();
    clearInterval(disappearReg);
}

function showSocialNetw() {
    var socials = [
        {
            "href": "https://www.facebook.com",
            "class": "fa fa-facebook-square"
        },
        {
            "href": "https://www.instagram.com/?hl=en",
            "class": "fa fa-instagram"
        },
        {
            "href": "https://twitter.com/?lang=en",
            "class": "fa fa-twitter-square"
        },
        {
            "href": "https://www.youtube.com/",
            "class": "fa fa-youtube-square"
        }
    ]
    let text="";
    for (var s of socials) {
        text += `<li><a href="${s.href}"><span class="${s.class}"></span></a></li>`;
    }
    document.getElementById("socialList").innerHTML = text;
    $("#socialList .fa").hover(function(){
        $(this).addClass("faDesign");
    },function(){
        $(this).removeClass("faDesign");
    });
    var html = $('#footCont').html();
    html+= "<div class='information'><h4>Follow Us :)</h4><ul>" + text + "</ul></div>";
    $('#footCont').html(html);
}

/*function changeActiveCategory(id){
    var menuList = document.querySelectorAll("#menuList li a");
    let i = 0;
    for(i ; i< menuList.length; i++){
        menuList[i].parentElement.classList.remove('active');
        if(menuList[i].dataset.id == id){
            menuList[i].parentElement.classList.add('active');
        }
    }
    var catList = document.querySelectorAll("#leftCategories li a");
    let j = 0;
    for(j ; j< catList.length; j++){
        catList[j].parentElement.classList.remove('activeCategoryLeftSide');
        if(catList[j].dataset.id == id){
            catList[j].parentElement.classList.add('activeCategoryLeftSide');
        }
    }
}*/
