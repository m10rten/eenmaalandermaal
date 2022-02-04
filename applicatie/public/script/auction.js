// get names for elements in de document
var imageList = document.getElementsByTagName("SECTION");
var closeImg  = document.getElementsByClassName("close-image");
var checkName = document.getElementsByClassName("check-name");
var images = document.getElementsByClassName("image-preview");
var imageSpan = document.getElementsByClassName("text-img-preview");

// simplified for the rest of coding
function create(string){
    return document.createElement(string);
}

// checkt hoeveel images er zijn, en voegt er 1 aan toe voor tellen van 1 tot 4
function checkImages(){
    return closeImg.length + 1;    
}

// add input field on click
document.getElementById("add-image").addEventListener('click', function(){
    if(checkImages() > 3){
        alert("te veel afbeeldingen");
    } 
    else{   
    var divS12 = create("DIV");
    var divS10 = create("DIV");
    var divS2 = create("DIV");
    var divF = create("DIV");
    var img = create("IMG");
    var span = create("SPAN");
    var inputF = create("INPUT");
    var inputT = create("INPUT");
    var li = create("LI");

    img.className = "image-preview";
    span.className = "rounded yellow black-text text-img-preview";
    inputT.className = "file-path validate borderless-input text-path-image";

    inputF.setAttribute("type","file");
    inputF.setAttribute("name", "extraImage"+checkImages());
    inputF.className = "check-name";
    var indexImg = checkImages();
    img.setAttribute("id","auctionImage"+indexImg);
    span.setAttribute("id", "auctionImageText"+indexImg);
    inputF.setAttribute("onchange", "readImg(this,"+indexImg+")");
    inputT.setAttribute("type", "text");
    inputT.setAttribute("placeholder", "kies nog een...");

    li.setAttribute('id', 'close-image');
    li.className = "btn col s12 material-icons rounded center red accent-4";
    li.innerHTML = "close";

    divF.className = "file-path-wrapper rounded z-depth-1";
    divS2.className = "col s2 close-image";
    divS12.className = "col s12"
    divS10.className = "col s10 file-field input-field";

    divS10.appendChild(img);
    divS10.appendChild(inputF);
    divS10.appendChild(span);
    divF.appendChild(inputT);
    divS10.appendChild(divF);
    
    divS2.appendChild(li);

    divS12.appendChild(divS10);
    divS12.appendChild(divS2);
    imageList[0].appendChild(divS12);  
    checkNames();
    changeImgId();
    }  
})
// change attribute name for later use in post
function checkNames() {
    if (checkName) {
        for (var i = 0; i < checkName.length; i++) {
            checkName[i].removeAttribute("onchange");
            checkName[i].removeAttribute("name");
            y = i + 1;
            checkName[i].setAttribute("name", "extraImage" + y);
            checkName[i].setAttribute("onchange", "readImg(this,"+y+")");
        }
    }
}
function changeImgId(){
    if(images){
        for(var i = 0; i < images.length; i++){
            images[i].removeAttribute("id");

            images[i].setAttribute("id", "auctionImage"+i+"");
        }
    }
}
function changeSpanId(){
    if(imageSpan){
        for(var i = 0; i < imageSpan.length; i++){
            imageSpan[i].removeAttribute("id");
            imageSpan[i].setAttribute("id", "auctionImageText"+i+"");
        }
    }
}
function checkClose(){
    if(closeImg){
        for(var i = 0; i < closeImg.length; i++){        
            closeImg[i].addEventListener('click', function(){
                var div = this.parentNode;
                div.remove();
                checkNames();
                changeImgId();
                changeSpanId();
            })
        }
    }
}
// required to check if clicked
setInterval(checkClose, 100);
// setInterval(checkNames, 200);

// change preview image
function readImg(input, nr){
        var imgId = "auctionImage"+nr;
        var textId = "auctionImageText"+nr;
        var img = document.getElementById(imgId);
        var text = document.getElementById(textId);
        if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            img.setAttribute("src", e.target.result);
            img.style.display = "block";
            text.style.display = "none";
        };

        reader.readAsDataURL(input.files[0]);
    }else{
            text.style.display = "block";
            img.style.display = "none";
    }
}
