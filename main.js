let form = document.forms;

let zip = new JSZip();

let doneCount = 0;

let inputLength = 0;

form.form1.formData.addEventListener( 'change', function(e) {
    form.form1.formData.disabled=true;
    inputLength = this.files.length;
    document.getElementById('total').innerText=inputLength;
    document.getElementById('done').innerText=String(doneCount);
    for (let i = 0; i < inputLength; i++) {
        let fileReader = new FileReader() ;
        let file = this.files[i] ;
        fileReader.readAsDataURL( file ) ;
        fileReader.onload = function () {
            let request = {
                payload:{
                    image:{//リクエスト時　data:image/jpeg;base64,　の部分をカットする必要がある
                        imageBytes:String(this.result).split( ',' )[1]
                    }
                }
            };

            let XHR = new XMLHttpRequest();
            XHR.open("POST", url, true);
            XHR.setRequestHeader('Authorization',`Bearer ${token}`);
            XHR.setRequestHeader('Content-Type','application/json');
            XHR.send(JSON.stringify(request));

            XHR.onreadystatechange=function(){//XHR通信の完了後に発火
                if(XHR.readyState==4 && XHR.status==200){
                    console.log(XHR.responseText);
                    //ファイル+iの部分は取得したラベル名
                    zip.file(JSON.parse(XHR.responseText).payload[0].displayName+"/"+e.target.files[i].name, file);
                    doneCount++;
                    barUpdate();
                    document.getElementById('done').innerText=String(doneCount);
                    console.log(doneCount);//一つ一つのファイル処理
                    if(inputLength===doneCount){
                        alert("completed");
                        document.getElementById("dl").disabled=false;
                        document.getElementById('indicate').innerHTML="完了";
                    }//すべてのファイル処理が終わった場合
                }else if(XHR.readyState==4 && XHR.status==401){
                    console.log(token);
                    alert("401:token error");
                }
            };
        };
    }
});



let token = "";

form.form0.inputToken.addEventListener( 'change', ()=>token = form.form0.inputToken.value);

// console.log(token);
function send() {
    zip.generateAsync({type:"blob"}).then(function(content) {
        // see FileSaver.js
        saveAs(content, "results.zip");
    });
}


let bar = document.querySelectorAll('#prog-bar > .progress-bar')[0];
bar.style.width=0;

function barUpdate() {
    bar.style.width =  ((doneCount/inputLength)*100) + '%';
}





